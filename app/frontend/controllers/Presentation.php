<?php

class Presentation extends AppController
{
	private
		$_tokenModel;
	
	public function _index()
	{
		if( !$this->getUser()->getToken() )
			$this->forward( 'Main', 'login' );
		
		$tokenModel = $this->getTokenModel();
		
		$questionnaireDispatcher = new QuestionnaireDispatcher( $tokenModel );
		$questionnaireDispatcher->prepare();
		
		if( $questionnaireDispatcher->hasErrors() )
		{
			$errors = $questionnaireDispatcher->getErrors();
			foreach( $errors->getParameterNames() as $key )
				$this->getRequest()->addError( $key, $errors->get( $key ) );
			
			return Controller::ERROR;
		}
		
		$this->question = $questionnaireDispatcher->getNextQuestion();
		$this->forward( 'Presentation', $this->question->Template );
	}
	
	public function _radio_table()
	{
	
	}
	
	public function _radio_vertical()
	{

	}
	
	public function _checkbox_vertical()
	{

	}
	
	public function _text()
	{

	}
	
	public function _table()
	{

	}
	
	private function getTokenModel()
	{
		if( is_null( $this->_tokenModel ) )
		{
			$token = $this->getUser()->getToken();
			$this->_tokenModel = $this->getOutlet()->
				from( 'Token' )->
				where( '{Token.Token} = ?', array( $token ) )->
				findOne();
			if( !$this->_tokenModel )
				$this->forward( 'Main', 'login' );
		}
		return $this->_tokenModel;
	}

}