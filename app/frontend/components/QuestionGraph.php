<?php

/*
	QuestionGraph class
	- creates graph representiation of current Questionnaire ( with QuestionGraphNode nodes )
	- used for:
		- seeking the current (first unanswered by Token) Question
		- inserting and validating new answers of Token, if Request answers are present
		- seeking next question according to Token answers and filters
	- graph is directed; nodes contain edges to other nodes
*/

class QuestionGraph
{
	private
		$_nodeMap,
		$_questionnaire,
		$_rootNode;
	
	public function __construct( Questionnaire $questionnaire )
	{
		$this->setQuestionnaire( $questionnaire );
		$this->populateNodes();
	}
	
	public function getQuestionnaire()
	{
		return $this->_questionnaire;
	}
	
	public function setQuestionnaire( Questionnaire $questionnaire )
	{
		$this->_questionnaire = $questionnaire;
	}
	
	public function getRootNode()
	{
		return $this->_rootNode;
	}
	
	public function setRootNode( QuestionGraphNode $root )
	{
		$this->_rootNode = $root;
	}
	
	public function getCurrentNodeFor( Token $token )
	{
		$root = $this->getRootNode();
		if( !$root->getQuestion()->hasUserAnswers( $token ) )
			return $root;
		else
			return $root->getLastAnsweredChild( $token );
	}
	
	private function populateNodes()
	{
		$questionnaire = $this->getQuestionnaire();
		foreach( $questionnaire->getQuestions() as $question )
		{
			$node = $this->getNodeByQuestionName( $question->Name );
			if( !$node )
			{
				$node = new QuestionGraphNode( $question );
				$this->addNode( $node );
			}
			
			foreach( $question->getNextQuestions() as $nextQuestion )
			{
				$nextNode = $this->getNodeByQuestionName( $nextQuestion->Name );
				if( !$nextNode )
				{
					$nextNode = new QuestionGraphNode( $nextQuestion );
					$this->addNode( $nextNode );
				}
				$node->addNeighbour( $nextNode );
			}
			if( $question->First )
			{
				if( !is_null( $this->getRootNode() ) )
					throw new EMultipleRootNodes();
				$this->setRootNode( $node );
			}
		}
	}
	
	public function getNodeMap()
	{
		if( is_null( $this->_nodeMap ) )
			$this->_nodeMap = array();
		return $this->_nodeMap;
	}
	
	public function getNodeByQuestionName( $name, $default = false )
	{
		$map = $this->getNodeMap();
		if( isset( $map[ $name ] ) )
			return $map[ $name ];
		return $default;
	}
	
	public function getNodeByAnswer( Answer $answer, $default = false )
	{
		$question = $answer->getAnswerGroup()->getQuestionGroup()->getQuestion();
		return $this->getNodeByQuestionName( $question->Name );
	}
	
	private function addNode( QuestionGraphNode $node )
	{
		$questionName = $node->getQuestion()->Name;
		if( $this->getNodeByQuestionName( $questionName, false ) != false )
			return;
		
		$this->_nodeMap[ $questionName ] = $node;
	}
}

class EMultipleRootNodes extends Exception { }