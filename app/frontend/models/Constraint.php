<?php

class Constraint extends Model
{
	private
		$_answer,
		$_constraintRule;

	public function getAnswer()
	{
		return $this->_answer;
	}

	public function setAnswer( Answer $answer )
	{
		$this->_answer = $answer;
	}

	public function getConstraintRule()
	{
		return $this->_constraintRule;
	}

	public function setConstraintRule( ConstraintRule $constraintRule )
	{
		$this->_constraintRule = $constraintRule;
	}

}