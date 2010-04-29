<?php

class Questionnaire extends Model
{
	private
		$_status,
		$_questions,
		$_questionnaireOrders;
	
	public function getQuestionnaireStatus()
	{
		return $this->_status;
	}
	
	public function setQuestionnaireStatus( QuestionnaireStatus $status )
	{
		$this->_status = $status;
	}
	
	public function getQuestions()
	{
		return $this->_questions;
	}
	
	public function setQuestions( Collection $questions )
	{
		$this->_questions = $questions;
	}
	
	private function getOrderBuilder()
	{
		return $this->_orderBuilder;
	}
	
	private function setOrderBuilder( QuestionnaireOrderBuilder $builder )
	{
		$this->_orderBuilder = $builder;
	}
	
	public function getQuestionnaireOrders()
	{
		return $this->_questionnaireOrders;
	}
	
	public function setQuestionnaireOrders( Collection $questionnaireOrders )
	{
		$this->_questionnaireOrders = $questionnaireOrders;
	}
	
}