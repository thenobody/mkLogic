<?php

class AnswerGroup extends Model
{
	private
		$_questionGroup,
		$_answers,
		$_answerType;

	public function getQuestionGroup()
	{
		return $this->_questionGroup;
	}

	public function setQuestionGroup( QuestionGroup $questionGroup )
	{
		$this->_questionGroup = $questionGroup;
	}
	
	public function getAnswers()
	{
		return $this->_answers;
	}
	
	public function setAnswers( Collection $answers )
	{
		$this->_answers = $answers;
	}
	
	public function getAnswerType()
	{
		return $this->_answerType;
	}
	
	public function setAnswerType( AnswerType $answerType )
	{
		$this->_answerType = $answerType;
	}
}