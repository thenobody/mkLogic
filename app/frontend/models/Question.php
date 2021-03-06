<?php

class Question extends Model
{
	private
		$_questionnaire,
		$_questionGroups,
		$_nextQuestions,
		$_validationOrders,
		$_filteringOrders,
		$_answers;
	
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
	
	public function getQuestionGroupByName( $name, $default = false )
	{
		foreach( $this->getQuestionGroups() as $questionGroup )
			if( $questionGroup->Name == $name )
				return $questionGroup;
		return $default;
	}
	
	public function getNextQuestions()
	{
		if( is_null( $this->_nextQuestions ) )
		{
			$this->_nextQuestions = new Collection();
			$outlet = $this->getDB();
			$orders = $outlet->
				from( 'QuestionnaireOrder' )->
				where( '{QuestionnaireOrder.QuestionId} = ?', array(
					$this->Id,
				))->
				find();
			foreach( $orders as $order )
				$this->_nextQuestions->add( $order->getNextQuestion() );
		}
		return $this->_nextQuestions;
	}
	
	public function setNextQuestions( Collection $nextQuestions )
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
	
	public function getAnswers()
	{
		if( is_null( $this->_answers ) )
			$this->_answers = $this->collectAnswers();
		return $this->_answers;
	}
	
	public function setAnswers( Collection $answers )
	{
		$this->_answers = $answers;
	}
	
	private function collectAnswers()
	{
		$result = new Collection();
		foreach( $this->getQuestionGroups() as $questionGroup )
			foreach( $questionGroup->getAnswerGroups() as $answerGroup )
				foreach( $answerGroup->getAnswers() as $answer )
					$result->add( $answer );
		return $result;
	}
	
	public function hasUserAnswers( Token $token )
	{
		foreach( $this->getAnswers() as $answer )
			if( $answer->getUserAnswer( $token, false ) != false )
				return true;
		return false;
	}
}