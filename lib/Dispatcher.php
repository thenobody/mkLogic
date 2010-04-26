<?php

class Dispatcher extends BaseComponent
{

	private
		$_template,
		$_defaultTemplate;

	/*
		initialize with configuration array
	*/
	public function init( $config )
	{
		$this->_defaultTemplate = $config[ 'defaultTemplate' ];
		
	}
	
	/*
		returns global application templating object
	*/
	public function getTemplate()
	{
		if( $this->_template == null )
		{
			$this->_template = new ViewTemplate();
			$this->_template->setTemplateFile( 
				Core::getApp()->getAppDir() . '/views/' . $this->getDefaultTemplate()
			);
		}
		return $this->_template;
	}
	
	/*
		main loop for executing Controller's actions
	*/
	public function executeDispatch()
	{
		$app = Core::getApp();
		$request = $app->getComponent( 'Request' );
		$controllerName = $request->getControllerName();
		$actionName = $request->getActionName();
		
		while( true )
		{
			try
			{
				$variables = ( isset($controller) && $controller instanceof Controller ) ? $controller->getVariables() : null;
				$controller = $this->createController( $controllerName, $variables );
				
				$controller->executeAction( Controller::PREEXECUTE_ACTION );
				$controller->executeAction( $actionName );
				break;
			}
			catch( ENotFound $e )
			{
				$request = $this->getRequest();
				$controllerName = $request->get404ControllerName();
				$actionName = $request->get404ActionName();
			}
			catch( ForwardBreakpoint $break )
			{
				$controllerName = $break->getControllerName();
				$actionName = $break->getActionName();
			}
		}
		
		$controller->getView()->render();
		$this->decorate( $controller->getView()->getContent() );
	}
	
	/*
		helper method to instantiate Controller objects
	*/
	private function createController( $controllerName, ParameterHolder $variables = null )
	{
		if ( ! $this->getApplication()->hasController( $controllerName ) )
			throw new ENotFound( 'Controller ' . $controllerName . ' was not found' );
		
		if( !in_array($controllerName, get_declared_classes()) )
			include Core::getApp()->getAppDir() . '/controllers/' . $controllerName . '.php';
			
		$controller = new $controllerName();
		if( $variables != null )
			$controller->setVariables( $variables );
		
		return $controller;
	}
	
	/*
		decorate view with global template
	*/
	private function decorate( $content )
	{
		$template = $this->getTemplate();
		if( $template->getTemplateFile() === false )
			$template->setContent( $content );
		else
		{
			$template->setInputVariables( 
				array(
					'content' => $content
				)
			);
			$template->render();
		}
	}
	
	/*
		return's default template file location
	*/
	public function getDefaultTemplate()
	{
		return $this->_defaultTemplate;
	}
	
	/*
		hard header redirect to controller/action?params
	*/
	public function redirect( $controller, $action, $parameters = array() )
	{
		$path = '/' . $controller . '/' . $action . '/';
		if( count( $parameters ) > 0 )
		{
			$path .= '?';
			foreach( $parameters as $key => $value )
				$path .= $key . '=' . $value . '&';
			trim( $path, '&' );
		}
		header( 'Location: ' . $path );
		exit( 0 );
	}
	
	/*
		internal forward without hard redirect
	*/
	public function forward( $controller, $action )
	{
		$break = new ForwardBreakpoint();
		$break->setControllerName( $controller );
		$break->setActionName( $action );
		
		throw $break;
	}
	
	/*
		internal forward to 404 http status
	*/
	public function forward404()
	{
		$request = $this->getRequest();
		$this->forward( $request->get404ControllerName(), $request->get404ActionName() );
	}
}

/*
	controller not found
*/
class ENotFound extends Exception { }

/*
	forward execution raises exception to stop actual controller method
*/
class ForwardBreakpoint extends Exception
{
	private
		$_controllerName,
		$_actionName;
	
	/*
		set new controller name
	*/
	public function setControllerName( $controller )
	{
		$this->_controllerName = ucfirst($controller);
	}
	
	/*
		get new controller name
	*/
	public function getControllerName()
	{
		return $this->_controllerName;
	}
	
	/*
		set new action name
	*/
	public function setActionName( $action )
	{
		$this->_actionName = $action;
	}
	
	/*
		get new action name
	*/
	public function getActionName()
	{
		return $this->_actionName;
	}
}