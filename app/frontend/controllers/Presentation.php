<?php

class Presentation extends AppController
{
	public function _index()
	{
		if( !$this->getUser()->getToken() )
			$this->forward( 'Main', 'login' );
		
		$questionnaire = $this->initQuestionnaire();
		if( !$questionnaire->isAvailable() )
		{
			$this->getRequest()->addError( 'questionnaire-unavailable',
											$questionnaire->getQuestionnaireStatus()->generateMessage() );
			
			return Controller::ERROR;
		}
		$this->meh = "meh";
		$this->forward( 'Presentation', $questionnaire->getCurrentQuestion()->Template );
	}
	
	public function _radio_table()
	{
	
	}
	
	public function _radio_vertical()
	{

	}
	
	public function _lines()
	{

	}
	
	public function _table()
	{

	}
	
	private function initQuestionnaire()
	{
		$token = $this->getUser()->getToken();
		$tokenModel = $this->getOutlet()->
			from( 'Token' )->
			where( '{Token.Token} = ?', array( $token ) )->
			findOne();
		
		$questionnaire = $tokenModel->getQuestionnaire();
		return $questionnaire;
	}
	
}