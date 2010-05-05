<?php

class UserAnswer extends Model
{
	private
		$_token,
		$_answer;
	
	
	public function getToken()
	{
		return $this->_token;
	}

	public function setToken( Token $token )
	{
		$this->_token = $token;
	}
	
	public function getAnswer()
	{
		return $this->_answer;
	}
	
	public function setAnswer( Answer $answer )
	{
		$this->_answer = $answer;
	}

}