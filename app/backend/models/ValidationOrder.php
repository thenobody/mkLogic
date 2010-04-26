<?php

class ValidationOrder extends QuestionnaireModel
{
	private
		$_question,
		$_constraint,
		$_nextConstraint,
		$_logicalOperator;
	
	public function getQuestion()
	{
		return $this->_question;
	}

	public function setQuestion( Question $question )
	{
		$this->_question = $question;
	}
	
	public function getConstraint()
	{
		return $this->_constraint;
	}

	public function setConstraint( Constraint $constraint )
	{
		$this->_constraint = $constraint;
	}
	
	public function getNextConstraint()
	{
		return $this->_nextConstraint;
	}

	public function setNextConstraint( Constraint $nextConstraint )
	{
		$this->_nextConstraint = $nextConstraint;
	}
	
	public function getLogicalOperator()
	{
		return $this->_logicalOperator;
	}

	public function setLogicalOperator( LogicalOperator $logicalOperator )
	{
		$this->_logicalOperator = $logicalOperator;
	}

}