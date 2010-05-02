<?php

class Answer extends Model
{
	private
		$_answerGroup;
	
	public function getAnswerGroup()
	{
		return $this->_answerGroup;
	}
	
	public function setAnswerGroup( AnswerGroup $answerGroup )
	{
		$this->_answerGroup = $answerGroup;
	}

}