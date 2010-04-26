<?php

/*
	handle user Authentication
*/
class Auth extends AppController
{
	/*
		login action
	*/
	public function _login()
	{
		if( $this->getRequest()->getMethod() == Request::POST )
		{
			$user = new User();
			$user->Login = $this->getRequest()->get( 'login', '' );
			$user->Password = $this->getRequest()->get( 'password', '' );
			if( $user->validate() )
			{
				$user->hashPassword();
				$model = $this->getOutlet()->from('User')
					->where('{User.Login} = ? AND {User.Password} = ? AND {User.Active} = TRUE', array(
						$user->Login,
						$user->Password,
					))->limit(1)->find();
				if( count( $model ) == 1 )
				{
					$this->getUser()->setAuthenticated( true );
					$this->getUser()->setId( $model[0]->Id );
					$this->redirect('home', 'index');
				}
				
			}
		}
		$this->redirect( 'auth', 'form' );
	}
	
	/*
		logout user
	*/
	public function _logout()
	{
		$this->getuser()->setAuthenticated( false );
		$this->redirect( 'home', 'index' );
	}
	
	/*
		login form
	*/
	public function _form()
	{
		if( $this->getUser()->isAuthenticated() )
			$this->redirect( 'home', 'index' );
	}
	
	/*
		debug method for inserting new static user
	*/
	public function _insert()
	{
		$user = new User();
		$user->Login = 'john@gmail.com';
		$user->Password = 'doedoe';
		$user->hashPassword();
		$user->setCreatedAtNow();
		$user->generateToken();
		$user->Active = 0;
		$this->getOutlet()->save( $user );
	}
	
	/*
		register new user
	*/
	public function _signup()
	{
		if( $this->getUser()->isAuthenticated() )
			$this->redirect( 'home', 'index' );

		if( $this->getRequest()->getMethod() == Request::POST )
		{
			$user = new User();
			$user->Login = $this->getRequest()->get( 'login', '' );
			$user->Password = $this->getRequest()->get( 'password', '' );
			if( $user->validate() && ( $user->Password == $this->getRequest()->get( 'password-confirm', '' ) ) )
			{
				$user->hashPassword();
				$models = $this->getOutlet()->from('User')
					->where('{User.Login} = ?', array(
						$user->Login,
					))->limit(1)->find();
				if( count( $models ) == 0 )
				{
					$user->setCreatedAtNow();
					$user->Active = 0;
					$user->generateToken();
					$this->getOutlet()->save( $user );
					
					$this->user = $user;
					return Controller::SUCCESS;
				}
			}
		}
		return 'Form';
	}
	
	/*
		user confirmation after registering
	*/
	public function _confirm()
	{
		$token = $this->getRequest()->get( 'confirm' );
		if( !$token )
			return Cotroller::ERROR;
		
		$users = $this->getOutlet()->from( 'User' )
			->where( '{User.Token} = ? AND {User.Active} = FALSE', array( $token ) )
			->limit(1)
			->find();
		
		if( count($users) < 1 )
			return Controller::ERROR;
		
		$user = $users[0];
		
		$user->Active = 1;
		$this->getOutlet()->save( $user );
	}
	
}