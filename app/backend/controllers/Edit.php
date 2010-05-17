<?php

class Edit extends AppController
{
	public function _preexecute()
	{
		if( !$this->getRequest()->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );

		$this->getTemplate()->setTemplateFile( false );
	}
	
	public function _index()
	{
		$this->project = $this->getRequest()->get( 'project' );
	}
	
	public function _attributes()
	{
		$this->projectName = $this->getRequest()->get( 'project' );
		$project = $this->getOutlet()->from( 'Questionnaire' )
			->where( '{Questionnaire.Name} = ?', array(
				$this->projectName
			))->
			findOne();
		$projectStatuses = $this->getOutlet()->from( 'QuestionnaireStatus' )
			->where( '1' )->find();
		
		$this->projectStatus = $project->getQuestionnaireStatus();
		$this->projectStatuses = $projectStatuses;
		$this->projectQuestionCount = count( $project->getQuestions() );
		$this->projectTokenTotalCount = 0;
		$this->projectTokenFinishedCount = 0;
		foreach( $project->getTokens() as $token )
		{
			if( $token->getTokenStatus()->Value == 'used' )
				$this->projectTokenFinishedCount++;
			$this->projectTokenTotalCount++;
		}
		
	}
	
	public function _questions()
	{
		$this->projectName = $this->getRequest()->get( 'project' );
		$project = $this->getOutlet()->from( 'Questionnaire' )
			->where( '{Questionnaire.Name} = ?', array(
				$this->projectName
			))->
			findOne();
		$this->questions = $project->getQuestions();
		$this->answerTypes = $this->getOutlet()->from( 'AnswerType' )
			->where( '1' )->find();
	}
	
	public function _order()
	{
		$this->project = 'order - ' . $this->getRequest()->get( 'project' );
	}
	
	public function _tokens()
	{
		$this->project = 'tokens - ' . $this->getRequest()->get( 'project' );
	}
}