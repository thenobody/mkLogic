<?php

class Question extends Model
{
	private
		$_questionnaire,
		$_nextQuestions;
	
	public function getQuestionnaire()
	{
		return $this->_questionnaire;
	}
	
	public function setQuestionnaire( Questionnaire $questionnaire )
	{
		$this->_questionnaire = $questionnaire;
	}
	
	public function getQuestions()
	{
		return $this->_nextQuestions;
	}
	
	public function setQuestions( Collection $nextQuestions )
	{
		$this->_nextQuestions = $nextQuestions;
	}
}