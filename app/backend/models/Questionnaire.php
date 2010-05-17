<?php

class Questionnaire extends QuestionnaireModel
{
	private
		$_status,
		$_questions,
		$_tokens;
	
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

	public function getTokens()
	{
		return $this->_tokens;
	}

	public function setTokens( Collection $tokens )
	{
		$this->_tokens = $tokens;
	}
	
	public function getJSON()
	{
		$props = $this->serializeProperties();
		return json_encode( $props );
	}

}