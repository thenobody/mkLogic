<?php

class Token
{
	private
		$_questionnaire,
		$_status;
	
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
}