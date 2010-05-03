<?php

class Main extends AppController
{
	public function _index()
	{
		if( $filename = $this->getRequest()->getFileName( 'uploadedfile' ) )
		{
			$config = $this->getApp()->getConfig();
			$this->getRequest()->moveFile(
				'uploadedfile',
				$config[ 'questionnaireSchemasDir' ].'/'.$filename
			);
			$builder = new QuestionnaireBuilder();
			$builder->parseXmlAndSaveQuestionnaire( $config[ 'questionnaireSchemasDir' ].'/'.$filename, true );
		}
		
		return Controller::SUCCESS;
	}
	
	public function _getProjects()
	{
		if( !$this->getRequest()->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );
		$this->projects = $this->getOutlet()->from('Questionnaire')
			->where('1', array())->find();
		
		return self::SUCCESS;
	}
}