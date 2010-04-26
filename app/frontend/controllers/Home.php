<?php

/*
	Index controller
*/
class Home extends AppController
{

	/*
		index function
	*/
	public function _index()
	{
		$token = $this->getTokenModel();
		if( !$token )
			return AppController::ERROR;
		
		$this->token = $token;
		$this->forward( 'home', 'question' );
	}
	
	public function _question()
	{
		$token = $this->getTokenModel();
		$questionnaire = $token->getQuestionnaire();
		$questionnaire->setToken( $token );
		$questionnaire->populateFromXml();
		$this->questionnaire = $questionnaire;
	}
	
	private function getUpdatedToken()
	{
		$token = $this->getRequest()->get( 'token' );
		if( $token )
			$this->getUser()->setToken( $token );
		else
			$token = $this->getUser()->getToken();
		return $token;
	}

	private function getTokenModel()
	{
		$token = $this->getUpdatedToken();
		
		if( $token )
		{
			$token = $this->getOutlet()
				->from( 'Token' )
				->where( '{Token.Token} = ?', array(
					$token
				) )
				->findOne();
		}

		return $token;
	}
	
}