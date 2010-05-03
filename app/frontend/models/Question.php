<?php

class Question extends Model
{
	private
		$_questionnaire,
		$_questionGroups,
		$_nextQuestions,
		$_validationOrders,
		$_filteringOrders;
	
	public function getQuestionnaire()
	{
		return $this->_questionnaire;
	}
	
	public function setQuestionnaire( Questionnaire $questionnaire )
	{
		$this->_questionnaire = $questionnaire;
	}
	
	public function getQuestionGroups()
	{
		return $this->_questionGroups;
	}
	
	public function setQuestionGroups( Collection $questionGroups )
	{
		$this->_questionGroups = $questionGroups;
	}
	
	public function getQuestions()
	{
		return $this->_nextQuestions;
	}
	
	public function setQuestions( Collection $nextQuestions )
	{
		$this->_nextQuestions = $nextQuestions;
	}
	
	public function getValidationOrders()
	{
		return $this->_validationOrders;
	}
	
	public function setValidationOrders( Collection $validationOrders )
	{
		$this->_validationOrders = $validationOrders;
	}
	
	public function getFilteringOrders()
	{
		return $this->_filteringOrders;
	}
	
	public function setFilteringOrders( Collection $filteringOrders )
	{
		$this->_filteringOrders = $filteringOrders;
	}
}