<?php

/*
	Helper class
	- contains useful procedures
*/

abstract class Helper extends BaseComponent
{
	static public function includePartial( $file, $args = array() )
	{
		$request = self::getRequest();
		$app = self::getApplication();
		
		$curDir = ucfirst( $request->getControllerName() );
		$partial = '_' . lcfirst( $file ) . '.php';

		$template = new ViewTemplate();
		$template->setTemplateFile( $app->getAppDir() . '/views/' . $curDir . '/' . $partial );
		$template->setInputVariables( $args );
		
		$template->render();
		echo $template->getContent();
		return;
	}
	
}