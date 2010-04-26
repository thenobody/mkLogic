<?php

/*
	application specific user session abstraction
*/
class UserSession extends BaseComponent
{

	public function isAuthenticated()
	{
		return $this->getSession()->get( "authenticated" );
	}
	
	public function setAuthenticated( $auth = false )
	{
		$this->getSession()->set( "authenticated", $auth );
	}
	
	public function setId( $id )
	{
		$this->getSession()->set( 'userId', $id );
	}
	
	public function getId()
	{
		return $this->getSession()->get( 'userId', -1 );
	}
}
