<?php

class Answer extends Model
{
	private
		$_answerGroup,
		$_userAnswers;
	
	public function getAnswerGroup()
	{
		return $this->_answerGroup;
	}
	
	public function setAnswerGroup( AnswerGroup $answerGroup )
	{
		$this->_answerGroup = $answerGroup;
	}
	
	public function getUserAnswers()
	{
		return $this->_userAnswers;
	}
	
	public function setUserAnswers( Collection $userAnswers )
	{
		$this->_userAnswers = $userAnswers;
	}
	
	public function getUserAnswer( $token, $default = false )
	{
		$userAnswers = $this->getUserAnswers();
		foreach( $userAnswers as $userAnswer )
			if( $userAnswer->getToken() == $token )
				return $userAnswer;
		return $default;
	}
}