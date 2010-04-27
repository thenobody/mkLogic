<?php

class Main extends AppController
{

	public function _index()
	{
		$token = $this->getUser()->getToken();
		if( !$token )
			$this->forward( 'Main', 'login' );
		else
			$this->forward( 'Presentation', 'index' );
	}
	
	public function _login()
	{
		if( $this->getRequest()->getMethod() == Request::POST )
		{
			$token = $this->validateLoginFromRequest();
			if( !$token )
				return;
			
			$this->getUser()->setToken( $token );
			$this->forward( 'Main', 'index' );
		}
	}
	
	public function _logout()
	{
		$this->getUser()->setToken( false );
		$this->forward( 'Main', 'index' );
	}
	
	private function validateLoginFromRequest()
	{
		$token = $this->getRequest()->get( 'token', false );
		
		if( !$token )
		{
			$this->getRequest()->addError( 'no-token-provided', 'Please provide your token.' );
			return false;
		}
		
		$tokenModel = $this->getOutlet()->
			from( 'Token')->
			where( '{Token.Token} = ?', array( $token ) )->
			findOne();
		
		if( !$tokenModel )
		{
			$this->getRequest()->addError( 'invalid-token', 'Specified token is not valid!' );
			return false;
		}
		
		if( $tokenModel->getTokenStatus()->Value == 'used' )
		{
			$this->getRequest()->addError( 'used-token', 'Specified token is no longer valid!' );
			return false;
		}
		
		return $token;
	}
}