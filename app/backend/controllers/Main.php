<?php

class Main extends AppController
{

	public function _index()
	{
		
	}
	
	public function _navigMenu()
	{
		if( !$this->getRequest()->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );

		$this->getTemplate()->setTemplateFile( false );
	}

	public function _upload()
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
	
	public function _getProjectInfo()
	{
		if( !$this->getRequest()->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );

		$this->getTemplate()->setTemplateFile( false );
		$projectName = $this->getRequest()->get( 'project', false );
		if( !$projectName )
			return Controller::ERROR;
		
		$project = $this->getOutlet()->from('Questionnaire')
			->where('{Questionnaire.Name} = ?', array(
				$projectName
			))->
			findOne();
		if( !$project )
			return Controller::ERROR;
		
		$this->project = $project->getJSON();
		return Controller::SUCCESS;
	}
}