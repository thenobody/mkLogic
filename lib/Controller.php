<?php

class Controller extends BaseComponent
{
	const
		SUCCESS = "Success",
		ERROR = "Error",
		PREEXECUTE_ACTION = "preexecute";
	
	private
		$_name,
		$_variables,
		$_view;


	/*
		set a variable for templating purposes
	*/
	public function __set( $key, $value )
	{
		$this->getVariables()->setByReference( $key, $value );
	}
	
	/*
		returns variable from internal holder
	*/
	public function & __get( $key )
	{
		return $this->getVariables()->get( $key );
	}
	
	/*
		checks for variable in internal holder
	*/
	public function & __isset( $key )
	{
		return $this->getVariables()->has( $key );
	}
	
	/*
		deletes variable from internal holder
	*/
	public function __unset( $key )
	{
		$this->getVariables()->delete( $key );
	}
	
	/*
		returns actual Controller name
	*/
	public function getName()
	{
		return get_class($this);
	}
	
	/*
		returns variable holder object
	*/
	public function getVariables()
	{
		if( $this->_variables == null )
			$this->_variables = new ParameterHolder();
		return $this->_variables;
	}
	
	/*
		set current variable holder
	*/
	
	public function setVariables( ParameterHolder $variables )
	{
		$this->_variables = $variables;
	}
	
	/*
		returns ViewTemplate for actual Controller
	*/
	public function getView()
	{
		if( $this->_view == null )
			$this->_view = new ViewTemplate();
		return $this->_view;
	}
	
	/*
		returns global Application template
	*/
	public function getTemplate()
	{
		return Core::getApp()->getComponent( 'Dispatcher' )->getTemplate();
	}
	
	/*
		runs a specific web action, apply view template on controller
	*/
	public function executeAction( $actionName )
	{
		// test for public method (_index, _add, ..)
		$functionName = '_' . $actionName;		
		if( ! method_exists( $this, $functionName ) )
			throw new ENotFound( );
		
		// load and populate controller's view template
		$viewName = $this->$functionName();
		if( !$viewName )
			$viewName = Controller::SUCCESS;
		$viewName = $actionName . $viewName . ".php";
		
		$this->getView()->setTemplateFile(
			Core::getApp()->getAppDir() . '/views/' . $this->getName() . "/" . $viewName
		);
		
		$this->getView()->setInputVariables( $this->getVariables()->getAll() );
	}
	
	/*
		call dispatcher's redirect
	*/
	public function redirect( $controller, $action, $parameters = array() )
	{
		$this->getDispatcher()->redirect( $controller, $action, $parameters );
	}
	
	/*
		call dispatcher's forward
	*/
	public function forward( $controller, $action )
	{
		$this->getDispatcher()->forward( $controller, $action );
	}
	
	/*
		call disparcher's forward 404 status
	*/
	public function forward404()
	{
		$this->getDispatcher()->forward404();
	}
	
	/*
		default empty preexecute action
	*/
	public function _preexecute()
	{
		//
	}
	
}