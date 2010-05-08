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
	
	public function addUserAnswer( UserAnswer $userAnswer )
	{
		$userAnswers = $this->getUserAnswers();
		if( is_null( $userAnswers ) )
		{
			$userAnswers = new Collection();
			$this->setUserAnswers( $userAnswers );
		}
		$userAnswers->add( $userAnswer );
	}
	
	public function addUserAnswerByToken( Token $token, $txtValue = null )
	{
		$userAnswer = $this->getUserAnswer( $token, false );
		if( $userAnswer )
		{
			$userAnswer->TextValue = $txtValue;
			return;
		}
		
		$userAnswer = new UserAnswer();
		$userAnswer->setToken( $token );
		$userAnswer->setAnswer( $this );
		$userAnswer->TextValue = $txtValue;
		$this->addUserAnswer( $userAnswer );
	}
}