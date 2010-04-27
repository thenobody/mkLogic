<?php

/*
	internal Controller variable holder for templating purposes
*/
class ParameterHolder
{

	private 
		$_parameters = array();

	/*
		clear all variables
	*/
	public function clear()
	{
		$this->_parameters = array();
	}
	
	/*
		returns variable as a reference
	*/
	public function & get( $key, $default = null )
	{
		if( array_key_exists( $key, $this->_parameters ) )
			$value = & $this->_parameters[ $key ];
		else
			$value = $default;	
		return $value;
	}
	
	/*
		returns reference to all variables (array)
	*/
	public function & getAll()
	{
		return $this->_parameters;
	}
	
	/*
		checks for variable existence
	*/
	public function has( $key )
	{
		return array_key_exists( $key, $this->_parameters );
	}
	
	/*
		removes variable from parameters
	*/
	public function delete( $key, $default = null )
	{
		$retval = $default;
		if( array_key_exists( $key, $this->_parameters ) )
		{
			$retval = $this->_parameters[ $key ];
			unset( $this->_parameters[ $key ] );
		}
		return $retval;
	}
	
	/*
		assigns new variable
	*/
	public function set( $key, $value )
	{
		$this->_parameters[ $key ] = $value;
	}
	
	/*
		returns true if ParameterHolder does not contain any value
	*/
	public function isEmpty()
	{
		return ( count( $this->getAll() ) < 1 );
	}
	
	/*
		assign new variable by reference
	*/
	public function setByReference( $key, & $value )
	{
		$this->_parameters[ $key ] = & $value;
	}
	
	/*
		instantiate parameter holder from array
	*/
	public function setFromArray( $array )
	{
		$this->_parameters = $array;
	}
	
	/*
		returns array of parameter names in this ParameterHolder
	*/
	public function getParameterNames()
	{
		return array_keys( $this->getAll() );
	}
}
