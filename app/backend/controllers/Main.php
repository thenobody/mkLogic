<?php

class Main extends AppController
{

	public function _index()
	{
		
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
	
	public function _getLeftMenuElement()
	{
		//if( !$this->getRequest()->isAjax() )
		//	$this->forward( 'HttpStatus', 'noAjax' );

		$this->getTemplate()->setTemplateFile( false );
	}
	
	public function _getProjects()
	{
		if( !$this->getRequest()->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );
		
		$this->getTemplate()->setTemplateFile( false );
		$projects = $this->getOutlet()->from('Questionnaire')
			->where('1', array())->find();
		
		$config = $this->getApp()->getConfig();
		$projectsJson = array();
		foreach( $projects as $project )
		{
			$tmpJson = array();
			$tmpJson[ 'label' ] = $project->Name;
			$tmpJson[ 'templateUrl' ] = "/admin.php/main/getLeftMenuElement";
			$tmpJson[ 'targetUrl' ] = "/admin.php/main/getProjectInfo";
			$tmpJson[ 'targetElement' ] = $config[ 'mainContent' ];
			
			$projectsJson[] = $tmpJson;
		}
		
		$this->json = $projectsJson;
		
		return self::SUCCESS;
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
		
		$result = array();
		$result[ 'name' ] = $project->Name;
		$result[ 'file' ] = $project->File;
		$result[ 'status' ] = $project->getQuestionnaireStatus()->Value;
		$result[ 'tokens' ] = count( $project->getTokens() );
		
		$this->project = $result;
	}
}