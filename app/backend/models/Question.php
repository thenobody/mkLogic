<?php

class Question extends QuestionnaireModel
{

	private
		$_questionnaire,
		$_questionGroups,
		$_questions,
		$_validationOrder,
		$_filteringOrder;
	
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
		if( is_null( $this->_questionGroups ) )
			$this->setQuestionGroups( new Collection() );
		return $this->_questionGroups ;
	}

	public function setQuestionGroups( Collection $questionGroups )
	{
		$this->_questionGroups = $questionGroups;
	}
	
	public function getQuestionGroupByName( $name, $default = false )
	{
		foreach( $this->getQuestionGroups() as $questionGroup )
		{
			if( $questionGroup->Name == $name )
				return $questionGroup;
		}
		return $default;
	}
	
	public function getQuestions()
	{
		if( is_null( $this->_questions ) )
			$this->_questions = new Collection();
		return $this->_questions;
	}
	
	public function setQuestions( Collection $questions )
	{
		$this->_questions = $questions;
	}
	
	public function getValidationOrders()
	{
		if( is_null( $this->_validationOrder ) )
			$this->_validationOrder = new Collection();
		return $this->_validationOrder;
	}
	
	public function setValidationOrders( Collection $validationOrder )
	{
		$this->_validationOrder = $validationOrder;
	}
	
	public function getFilteringOrders()
	{
		if( is_null( $this->_filteringOrder ) )
			$this->_filteringOrder = new Collection();
		return $this->_filteringOrder;
	}

	public function setFilteringOrders( Collection $filteringOrder )
	{
		$this->_filteringOrder = $filteringOrder;
	}

}