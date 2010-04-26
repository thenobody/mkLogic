<?php

class Questionnaire extends QuestionnaireModel
{
	private
		$_status,
		$_questions;
	
	public function getQuestionnaireStatus()
	{
		return $this->_status;
	}
	
	public function setQuestionnaireStatus( QuestionnaireStatus $status )
	{
		$this->_status = $status;
	}
	
	public function getQuestions()
	{
		if( is_null( $this->_questions ) )
			$this->setQuestions( new Collection() );
		return $this->_questions ;
	}
	
	public function setQuestions( Collection $questions )
	{
		$this->_questions = $questions;
	}
	
	public function getQuestionByName( $name, $default = false )
	{
		foreach( $this->getQuestions() as $question )
		{
			if( $question->Name == $name )
				return $question;
		}
		return $default;
	}

}