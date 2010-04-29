<?php

class QuestionGroup extends Model
{
	private
		$_question;

	public function getQuestion()
	{
		return $this->_question;
	}

	public function setQuestion( Question $question )
	{
		$this->_question = $question;
	}
}