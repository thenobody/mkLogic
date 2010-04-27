<?php

class Questionnaire extends Model
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
		return $this->_questions;
	}
	
	public function setQuestions( Collection $questions )
	{
		$this->_questions = $questions;
	}
	
	public function isAvailable()
	{
		return ( $this->getQuestionnaireStatus()->Value == 'active' );
	}
	
	public function getCurrentQuestion()
	{
		$questions = $this->getQuestions()->toArray();
		return $questions[0];
	}
}