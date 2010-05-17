<?php

class Home extends AppController
{
	public function _preexecute()
	{
		if( !$this->getRequest()->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );

		$this->getTemplate()->setTemplateFile( false );
	}
	
	public function _index()
	{
	
	}
	
	public function _getProjects()
	{
		$this->projects = $this->getOutlet()->from('Questionnaire')
			->where('1', array())->find();
	}
}