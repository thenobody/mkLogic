<?php

class AnswerGroup extends Model
{
	private
		$_questionGroup;

	public function getQuestionGroup()
	{
		return $this->_questionGroup;
	}

	public function setQuestionGroup( QuestionGroup $questionGroup )
	{
		$this->_questionGroup = $questionGroup;
	}
}