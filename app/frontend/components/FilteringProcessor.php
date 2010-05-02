<?php

/*
	Class FilteringProcessor
	- builds Constraint tree for given question and
	- evaluates filtering predicates on domain
	of answers previously submitted by respondent
	- respondent is identified by Token object
*/

class FilteringProcessor
{
	private
		$_token;
		
	public function __construct( Token $token )
	{
		$this->setToken( $token );
	}
	
	public function getToken()
	{
		return $this->_token;
	}
	
	public function setToken( Token $token )
	{
		$this->_token = $token;
	}
	
	/*
		returns true if all constraints were matched, otherwise false
		method receives Question model as argument,
		ConstraintTree is built with filtering constraints of this Question;
		Subsequently, this ConstraintTree is evaluated according
		the previous answers given by the respondent
	*/
	public function evaluateQuestion( Question $question )
	{
		$token = $this->getToken();
		$tree = $this->buildTree( $question );
		return $tree->evaluate( $token );
	}
	
	private function buildTree( Question $question )
	{
		$tree = new ConstraintTree( $question );
		foreach( $question->getFilteringOrders() as $order )
		{
			$node = new ConstraintTreeNode;
			$node->buildFromOrder( $order );
			$tree->addNode( $node );
		}

		return $tree;
	}
	
}
