<?php

/*
	Session management frawework component
*/
class Session extends BaseComponent
{

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
		instantiate component with Configuration array
	*/
	public function init( $config )
	{
		session_start();
		session_name( $config[ 'name' ] );
	}

}