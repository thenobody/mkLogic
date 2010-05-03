<?php

class FilteringOrder extends Model
{
	private
		$_constraint,
		$_nextConstraint,
		$_logicalOperator;
		
	
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
	
	public function setLogicalOperator( LogicalOperator $operator )
	{
		$this->_logicalOperator = $operator;
	}
	
}