<?php

class QuestionnaireOrder extends Model
{
	private
		$_questionnaire,
		$_question,
		$_nextQuestion;
	
	public function getQuestionnaire()
	{
		return $this->_questionnaire;
	}
	
	public function setQuestionnaire( Questionnaire $questionnaire )
	{
		$this->_questionnaire = $questionnaire;
	}
	
	public function getQuestion()
	{
		return $this->_question;
	}
	
	public function setQuestion( Question $question )
	{
		$this->_question = $question;
	}
	
	public function getNextQuestion()
	{
		return $this->_nextQuestion;
	}
	
	public function setNextQuestion( Question $nextQuestion )
	{
		$this->_nextQuestion = $nextQuestion;
	}
}