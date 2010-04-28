<?php

/*
	Request handling component (URI, GET, POST)
*/
class Request extends BaseComponent
{
	const
		POST = 1,
		GET = 2;
		

	private 
		$_merged,
		$_files,
		$_scriptName,
		$_controllerName,
		$_actionName,
		$_defaults,
		$_errors,
		$_httpErrors;
	

	/*
		returns variable from merged container
	*/
	public function get( $key, $default = false )
	{
		return ( $this->getParameters()->has($key) ) ? $this->getParameters()->get($key) : $default;
	}
	
	/*
		rewrite or assign new variable
	*/
	public function set( $key, $value )
	{
		$this->getParameters()->set($key, $value);
	}
	
	/*
		checks for variable existence
	*/
	public function has( $key )
	{
		return $this->getParameters()->has($key);
	}
	
	/*
		initialize component with Configuration array
	*/
	public function init( $config )
	{
		$this->_defaults = $config[ 'defaults' ];
		$this->_httpErrors = $config[ 'errors' ];
		
		$this->_merged = new ParameterHolder();
		$this->_merged->setFromArray(
			$this->mergeInputs( array( $_GET, $_POST ) )
		);
		
		$this->_files = new ParameterHolder();
		$this->_files->setFromArray(
			$this->populateUploadedFiles( $_FILES )
		);
		
		$this->parseUri( $_SERVER[ 'REQUEST_URI' ] );
	}
	
	/*
		set internal uri representation from @string uri
	*/
	public function parseUri( $uri )
	{
		$uri = trim( $uri, '/' );
		
		$this->_scriptName = ( preg_match( '/^.*\.[^\/]+/', $uri, $matches ) ) ? $matches[0] : 'index.php';
		// remove any script name before parameters
		$uri = preg_replace( '/^.*\.[^\/]+/', '', $uri );
		$uri = trim( $uri, '/' );
		
		$withoutGet = preg_replace( '/\?.*/', '', $uri );
		$parts = ( strlen( $withoutGet ) == 0 ) ? array() : explode( '/', $withoutGet );
		
		if( count( $parts ) == 0 )
		{
			$this->_controllerName = $this->getDefaultControllerName();
			$this->_actionName = $this->getDefaultActionName();
		}
		elseif( count( $parts ) == 1 )
		{
			if( Core::getApp()->hasController( $parts[ 0 ] ) )
			{
				$this->_controllerName = ucfirst( $parts[ 0 ] );
				$this->_actionName = $this->getDefaultActionName();
			}
			else
			{
				$this->_controllerName = $this->getDefaultControllerName();
				$this->_actionName = $parts[ 0 ];
			}
		}
		else
		{
			$this->_controllerName = ucfirst( $parts[ 0 ] );
			$this->_actionName = $parts[ 1 ];
		}
	}
	
	/*
		merge GET, POST inputs into one holder
	*/
	private function mergeInputs( $array = array() )
	{
		$merged = array();
		foreach( $array as $iter)
		{
			foreach( $iter as $key => $val )
			{
				$merged[ $key ] = $val;
			}
		}
		
		return $merged;
	}
	
	/*
		populate uploaded files wrappers
	*/
	private function populateUploadedFiles( $array = array() )
	{
		$files = array();
		foreach( $array as $name => $props )
		{
			$file = new UploadedFile();
			$file->populateFromArray( $props );
			$files[ $name ] = $file;
		}
		
		return $files;
	}
	
	/*
		returns uploaded files properties
	*/
	public function getFiles()
	{
		return $this->_files;
	}

	/*
		return file specified by id
	*/
	public function getFile( $file, $default = false )
	{
		return $this->getFiles()->get( $file, $default );
	}

	/*
		returns specified file's file name
	*/
	public function getFileName( $file )
	{
		$file = $this->getFile( $file );
		return ( $file ) ? $file->getFileName() : false;
	}

	/*
		moves specified request file to given folder (if FS permissions allow this action)
	*/
	public function moveFile( $file, $targetPath )
	{
		$file = $this->getFile( $file );

		if( !$file )
			throw new EFileNotFound();

		if( !copy( $file->getTemporaryFileName(), $targetPath ) )
			throw EUnableToMoveFile();

		return unlink( $file->getTemporaryFileName() );
	}
	
	/*
		returns Script name
	*/
	public function getScriptName()
	{
		return $this->_scriptName;
	}
	
	/*
		returns Controller name
	*/
	public function getControllerName()
	{
		return $this->_controllerName;
	}
	
	/*
		returns Action name
	*/
	public function getActionName()
	{
		return $this->_actionName;
	}
	
	/*
		returns default Controller name
	*/
	public function getDefaultControllerName()
	{
		return $this->_defaults[ 'controller' ];
	}
	
	/*
		returns default Action name
	*/
	public function getDefaultActionName()
	{
		return $this->_defaults[ 'action' ];
	}
	
	/*
		returns merged input parameters
	*/
	public function getParameters()
	{
		return $this->_merged;
	}
	
	/*
		returns HTTP errors config array
	*/
	
	public function getHttpErrors()
	{
		return $this->_httpErrors;
	}
	
	/*
		returns request user-specified errors
	*/
	public function getErrors()
	{
		if( is_null( $this->_errors ) )
			$this->_errors = new ParameterHolder();
		return $this->_errors;
	}
	
	/*
		returns true if Request contains errors
	*/
	public function hasErrors()
	{
		return !$this->getErrors()->isEmpty();
	}

	/*
		retrieves particular error message with specified name
	*/
	public function getError( $name, $default = false )
	{
		return $this->getErrors()->get( $name, $default );
	}

	/*
		adds new error into request with specified name
	*/
	public function addError( $name, $value )
	{
		$this->getErrors()->set( $name, $value );
	}
	
	/*
		returns 404 error Controller name
	*/
	public function get404ControllerName()
	{
		$errors = $this->getHttpErrors();
		return $errors[ '404' ][ 0 ];
	}
	
	/*
		returns 404 error Action name
	*/
	public function get404ActionName()
	{
		$errors = $this->getHttpErrors();
		return $errors[ '404' ][ 1 ];
	}
	
	/*
		returns true, when request was made by JS
	*/
	public function isAjax()
	{
		return isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && ($_SERVER[ 'HTTP_X_REQUESTED_WITH' ] == 'XMLHttpRequest');
	}
	
	/*
		returns Request::GET, Request::POST
	*/
	public function getMethod()
	{
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			return Request::POST;
		return Request::GET;
	}
	
}