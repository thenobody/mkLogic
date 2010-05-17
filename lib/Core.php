<?php

/*
	Framework singleton object, which serves as autoloader and initializer for application
*/
class Core
{

	static private 
		$_app = null;


	/*
		returns Application object
	*/
	static public function getApp()
	{
		return self::$_app;
	}
	
	/*
		singleton method for creating new Application from Configuration array
	*/
	static public function createApp( $config = array() )
	{
		if( self::$_app == null )
			self::$_app = new Application( $config );
		
		return self::getApp();
	}
	
	/*
		include directory in global include scope
	*/
	static public function includeDir( $path, $recursive = false )
	{
		if( $recursive )
			foreach( scandir($path) as $file )
				if( is_dir( $path . $file ) && !preg_match( '/^\.*$/', $file ) )
					set_include_path( get_include_path() . PATH_SEPARATOR . $path . $file );
		set_include_path( get_include_path() . PATH_SEPARATOR . $path );
	}

	/*
		autoloader method for including classes
	*/
	static public function autoload( $class )
	{
		if( isset( self::$_classes[ $class ] ) )
			include self::$_classes[ $class ];
		else
			include "$class.php";
	}
	
	/*
		Core framework objects holder
	*/
	static private 
		$_classes = array (
			'BaseComponent'	=> 'BaseComponent.php',
			'Request' 		=> 'Request.php',
			'Application' 	=> 'Application.php',
			'Session' 		=> 'Session.php',
			'DataBase'		=> 'DataBase.php',
			'Outlet'		=> 'outlet/Outlet.php',
			'Model'			=> 'Model.php',
			'Controller'	=> 'Controller.php',
			'Dispatcher'	=> 'Dispatcher.php',
			'ViewTemplate'	=> 'ViewTemplate.php',
		);

}

// register autoload method
$autoloadMethod = array( 'Core', 'autoload' );
if( !spl_autoload_register( $autoloadMethod ) )
	die( 'Unable to register autoload as an autoloading method.' );

include 'outlet/Outlet.php';