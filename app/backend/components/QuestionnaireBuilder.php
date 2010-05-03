<?php

/*
	Internal questionnaire parser and builder
	- parses XML input
	- converts it's structure into internal DB model and
	- commits questionnaire model into DB
*/

class QuestionnaireBuilder extends BaseComponent
{
	private
		$_xml,
		$_questionnaire;
	
	private function createOrUpdateQuestionnaire()
	{
		$questionnaire = $this->getQuestionnaire();
		$this->getDB()->save( $questionnaire );
		$questionnaire->setXml( $this->getQuestionnaire()->getXml() );
		$this->setQuestionnaire( $questionnaire );
	}
	
	public function getQuestionnaire()
	{
		return $this->_questionnaire;
	}
	
	private function setQuestionnaire( Questionnaire $questionnaire )
	{
		$this->_questionnaire = $questionnaire;
	}
	
	public function getXml()
	{
		return $this->_xml;
	}
	
	public function setXml( SimpleXMLElement $xml )
	{
		$this->_xml = $xml;
	}
	
	public function parseXmlAndSaveQuestionnaire( $xml, $isFile )
	{
		$root = new SimpleXMLElement( $xml, null, $isFile );
		if( !$root )
			throw new EInvalidXml();

		$this->setXml( $root );

		if( $root->getName() != 'questionnaire' )
			throw new EInvalidElement( "Root element has to be 'questionnaire'; '{$root->getName()}' given" );
		
		$this->parseQuestionnaire( ( $isFile ) ? basename( $xml ) : null );
		// parsing filtering and validation constraints requires questionnaire to be submitted to DB first
		$this->createOrUpdateQuestionnaire();
		
		$this->createQuestionOrder( $this->getQuestionnaire() );
	//	$this->createOrUpdateQuestionnaire();
		
		$this->parseQuestionnaireValidationAndFiltering( $this->getQuestionnaire() );
		$this->createOrUpdateQuestionnaire();
	}
	
	private function parseQuestionnaire( $filename )
	{
		$node = $this->getXml();
		
		// assign attributes
		$attributes = $node->attributes();
		
		$questionnaire = new Questionnaire();
		$questionnaire->Name = (string) $attributes[ 'name' ];
		$questionnaire->File = $filename;
		$questionnaire->Continuous = ( (string) $attributes[ 'continuous' ] == 'true' );
		$questionnaire->StatusId = 1;
		$questionnaire->setXml( $node );
		
		$this->setQuestionnaire( $questionnaire );
		
		// parse children
		$this->parseQuestions( $questionnaire );
	}
	
	private function parseQuestions( Questionnaire $questionnaire )
	{
		$node = $questionnaire->getXml();

		foreach ( $node->question as $question )
		{
			// assign attributes
			$attributes = $question->attributes();
			
			$questionModel = new Question();
			$questionModel->Name = (string) $attributes[ 'name' ];
			$questionModel->QuestionText = trim( (string) $question );
			$questionModel->Template = (string) $attributes[ 'template' ];
			$questionModel->setXml( $question );
			
			$questionnaire->getQuestions()->add( $questionModel );
			
			$this->parseQuestionGroups( $questionModel );
		}
	}
	
	private function parseQuestionGroups( Question $question )
	{
		$node = $question->getXml();
		$outlet = $this->getDB();
		foreach ( $node->{'question-group'} as $questionGroup )
		{
			// assign attributes
			$attributes = $questionGroup->attributes();
			
			$questionGroupModel = new QuestionGroup();
			$questionGroupModel->Name = (string) $attributes[ 'name' ];
			$questionGroupModel->Label = (string) $attributes[ 'label' ];
			$questionGroupModel->setXml( $questionGroup );
			
			$question->getQuestionGroups()->add( $questionGroupModel );
			
			$this->parseAnswerGroups( $questionGroupModel );
		}
	}
	
	private function parseAnswerGroups( QuestionGroup $questionGroup )
	{
		$node = $questionGroup->getXml();
		$outlet = $this->getDB();
		
		// retrieve types
		$rs = $outlet->from( 'AnswerType' )->find();
		$types = array();
		foreach ( $rs as $type )
			$types[ $type->Name ] = $type;

		foreach ( $node->{'answer-group'} as $answerGroup )
		{
			// assign attributes
			$attributes = $answerGroup->attributes();
			
			$answerGroupModel = new AnswerGroup();
			$answerGroupModel->Name = (string) $attributes[ 'name' ];
			$answerGroupModel->Label = (string) $attributes[ 'label' ];
			$answerGroupModel->TypeId = $types[ (string) $attributes[ 'type' ] ]->Id;
			$answerGroupModel->setXml( $answerGroup );
			
			$questionGroup->getAnswerGroups()->add( $answerGroupModel );
			
			$this->parseAnswers( $answerGroupModel );
		}
	}
	
	private function parseAnswers( AnswerGroup $answerGroup )
	{
		$node = $answerGroup->getXml();
		$outlet = $this->getDB();
		
		foreach ( $node->answer as $answer )
		{
			// assign attributes
			$attributes = $answer->attributes();
			
			$answerModel = new Answer();
			$answerModel->Name = (string) $attributes[ 'name' ];
			$answerModel->Text = ( (string) $attributes[ 'text' ] == 'true' );
			$answerModel->Value = (string) $attributes[ 'value' ];
			$answerModel->Label = (string) $attributes[ 'label' ];
			$answerModel->Limit = (int) $attributes[ 'limit' ];
			
			$answerModel->setXml( $answer );
			
			$answerGroup->getAnswers()->add( $answerModel );
		}
	}
	
	private function createQuestionOrder( Questionnaire $questionnaire )
	{
		foreach( $questionnaire->getXml()->question as $question )
		{
			$attributes = $question->attributes();
			$questionName = (string) $attributes[ 'name' ];
			$questionModel = $questionnaire->getQuestionByName( $questionName );
			$nextQuestions = $question->{'next-questions'};
			
			if( !$nextQuestions )
				continue;
			
			foreach( $nextQuestions->question as $nextQuestion )
			{
				$attributes = $nextQuestion->attributes();
				$nextQuestionName = (string) $attributes[ 'name' ];
				$nextQuestionModel = $questionnaire->getQuestionByName( $nextQuestionName );

				if( !$nextQuestionModel )
					throw new EInvalidElement( "Error creating linkage between '$questionName' and '$nextQuestionName'. Linked question does not exist in DB." );

				$questionModel->getQuestions()->add( $nextQuestionModel );
			}
		}
	}
	
	private function parseQuestionnaireValidationAndFiltering( Questionnaire $questionnaire )
	{
		$questionsXml = $questionnaire->getXml()->question;
		
		foreach( $questionsXml as $questionXml )
		{
			$attributes = $questionXml->attributes();
			$questionModel = $questionnaire->getQuestionByName( (string) $attributes[ 'name' ] );
			
			$validationXml = $questionXml->validation;
			if( $validationXml )
			{
				$validationTree = new ConstraintTree();
				$validationTree->setQuestionnaire( $questionnaire );
				$validationTree->setXml( $questionXml->validation );
				$validationTree->parse();
				
				$validationScheme = $validationTree->generateOutletCollection( $questionModel );
				$questionModel->setValidationOrders( $validationScheme );
			}
			
			$filteringXml = $questionXml->filtering;
			if( $filteringXml )
			{
				$filteringTree = new ConstraintTree();
				$filteringTree->setQuestionnaire( $questionnaire );
				$filteringTree->setXml( $filteringXml );
				$filteringTree->parse();
	
				$filteringScheme = $filteringTree->generateOutletCollection( $questionModel );
				$questionModel->setFilteringOrders( $filteringScheme );
			}
		}
	}
	
	private function parseQuestionValidation( Question $question )
	{
		$node = $question->getXml();
		$outlet = $this->getDB();
		if( isset( $node->validation[0] ) )
		{
			$notEmptyRule = $this->getDB()->from( 'ConstraintRule' )->where( "{ConstraintRule.Name} = 'not-empty'" )->findOne();
			$andOp = $this->getDB()->from( 'LogicalOperator' )->where( "{LogicalOperator.Name} = 'AND'" )->findOne();
			$answer = $question->getQuestionGroupByName( 'qg1' )->getAnswerGroupByName( 'ag1' )->getAnswerByName( 'a1' );

			$constraint1 = new Constraint();
			$constraint1->setConstraintRule( $notEmptyRule );
			$answer->getConstraints()->add( $constraint1 );
			
			$constraint2 = new Constraint();
			$constraint2->setConstraintRule( $notEmptyRule );
			$answer->getConstraints()->add( $constraint2 );

			$valOrder = new ValidationOrder();
			$valOrder->QuestionnaireId = 1;
			$valOrder->setQuestion( $question );
			$valOrder->setConstraint( $constraint1 );
			$valOrder->setNextConstraint( $constraint2 );
			$valOrder->setLogicalOperator( $andOp );
			
			$question->getValidationOrders()->add( $valOrder );
			
			//print_r( $question );
			/*$validation = new ConstraintTree();
			$validation->setQuestionnaire( $this->getQuestionnaire() );
			$validation->setXml( $node->validation[0] );
			$validation->parse();*/
		}
	}
	
}

/*
	invalid/not well-formed XML exception
*/
class EInvalidXml extends Exception { }

/*
	invalid or unexpected XML element exception
*/

class EInvalidElement extends EInvalidXml { }