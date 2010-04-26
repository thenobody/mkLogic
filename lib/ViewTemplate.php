<?php

/*
	Templating object
*/
class ViewTemplate
{
	private
		$_templateFile,
		$_inputVariables,
		$_content;
		
	/*
		set file to include in render
	*/
	public function setTemplateFile( $template )
	{
		$this->_templateFile = $template;
	}
	
	/*
		returns file to include in render
	*/
	public function getTemplateFile()
	{
		return $this->_templateFile;
	}
	
	/*
		set @array variables to pass into template file
	*/
	public function setInputVariables( array $input )
	{
		$this->_inputVariables = $input;
	}
	
	/*
		return @array variables to pass into template file
	*/
	public function getInputVariables()
	{
		return $this->_inputVariables;
	}
	
	/*
		pass variables to template file
	*/
	public function render()
	{
		ob_start();
		extract( $this->getInputVariables() );
		
		include( $this->getTemplateFile() );
		$this->setContent( ob_get_clean() );
	}
	
	/*
		returns @string rendered content
	*/
	public function getContent()
	{
		return $this->_content;
	}
	
	/*
		set @string content
	*/
	public function setContent( $content )
	{
		$this->_content = $content;
	}
}