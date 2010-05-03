<?php

/*
	Session management frawework component
*/
class Session extends BaseComponent
{
	private
		$_config;

	/*
		returns variable from session storage, default when variable was not found
	*/
	public function get( $key, $default = false )
	{
		if( $this->has($key) )
			return $_SESSION[ $key ];
		else
			return $default;
	}
	
	/*
		set a session variable
	*/
	public function set( $key, $value )
	{
		$_SESSION[ $key ] = $value;
	}
	
	/*
		remove variable from session storage
	*/
	public function delete( $key )
	{
		if( $this->has( $key ) )
			$this->set( $key, null );
	}
	
	/*
		test for variable existence in session storage
	*/
	public function has( $key )
	{
		return isset( $_SESSION[ $key ] );
	}
	
	/*
		deletes every value in this Session
	*/
	public function clear()
	{
		$_SESSION = array();
	}
	
	/*
		returns configuration array for PHP Session
	*/
	public function getConfig()
	{
		return $this->_config;
	}
	
	/*
		sets current session config
		- needs startSession() to be called to take effect
	*/
	public function setConfig( $config )
	{
		$this->_config = $config;
	}
	
	/*
		instantiate component with Configuration array
	*/
	public function init( $config )
	{
		$this->setConfig( $config );
		$this->startSession();
	}
	
	/*
		starts session
		- if expiry time has been reached, session cleared and restarted
	*/
	private function startSession()
	{
		$config = $this->getConfig();
		session_name( $config[ 'name' ] );
		session_start();
		
		if( $this->hasExpired() )
		{
			$this->destroySession();
			session_start();
		}
		else
			$this->prolongSession();
	}
	
	/*
		returns true if this Session has reached it's validity time limit
	*/
	
	public function hasExpired()
	{
		$config = $this->getConfig();
		$default = 1800;	// default session validity is set to 30 minutes;
		$now = time();
		
		$expirationTime = ( isset( $config[ 'validityPeriod' ] ) ) ? $config[ 'validityPeriod' ] : $default;
		
		if( !$this->get( 'lastActivity', false ) )
			$this->set( 'lastActivity', $now );
		
		return ( ( $this->get( 'lastActivity' ) + $expirationTime ) < $now  );
	}
	
	/*
		if validity period hasn't expired since last activity,
		update 'lastActivity' time stamp of this Session with current time
	*/
	private function prolongSession()
	{
		$this->set( 'lastActivity', time() );
	}
	
	/*
		empties and closes current Session
	*/
	
	private function destroySession()
	{
		session_destroy();
		$this->clear();
	}

}