<?php

class Token extends Model
{
	private
		$_questionnaire,
		$_status,
		$_userAnswers;
	
	public function getQuestionnaire()
	{
		return $this->_questionnaire;
	}
	
	public function setQuestionnaire( Questionnaire $questionnaire )
	{
		$this->_questionnaire = $questionnaire;
	}
	
	public function getTokenStatus()
	{
		return $this->_status;
	}
	
	public function setTokenStatus( $status )
	{
		$this->_status = $status;
	}
	
	public function getUserAnswers()
	{
		return $this->_userAnswers;
	}
	
	public function setUserAnswers( Collection $userAnswers )
	{
		$this->_userAnswers = $userAnswers;
	}
	

}