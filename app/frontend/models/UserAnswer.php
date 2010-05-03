<?php

class UserAnswer extends Model
{
	private
		$_answer;
	
	public function getAnswer()
	{
		return $this->_answer;
	}
	
	public function setAnswer( Answer $answer )
	{
		$this->_answer = $answer;
	}

}