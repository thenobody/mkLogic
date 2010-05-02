<?php

/*
	Internal constraint tree builder
	uses logical operators AND, OR, IF to connect constraints
*/

class ConstraintTree extends BaseComponent
{
	private
		$_root,
		$_schemeType,
		$_logicalOperators,
		$_constraintRules;
	
		const
			VALIDATION = 1,
			FILTERING = 2;
	
	public function __construct()
	{
		$this->fetchLogicalOperators();
		$this->fetchConstraintRules();
	}
	
	public function getRootNode()
	{
		return $this->_root;
	}
	
	public function setRootNode( ConstraintTreeNode $root )
	{
		$this->_root = $root;
	}
	
	public function getSchemeType()
	{
		return $this->_schemeType;
	}
	
	public function setSchemeType( $schemeType )
	{
		$this->_schemeType = $schemeType;
	}
	
	private function fetchLogicalOperators()
	{
		$outlet = $this->getDB();
		$model = $outlet->from( 'LogicalOperator' )->find();
		foreach( $model as $op )
			$this->_logicalOperators[ strtolower( $op->Name ) ] = $op;
	}
	
	public function getLogicalOperators()
	{
		return $this->_logicalOperators;
	}
	
	private function fetchConstraintRules()
	{
		$outlet = $this->getDB();
		$model = $outlet->from( 'ConstraintRule' )->find();
		foreach( $model as $op )
			$this->_constraintRules[ strtolower( $op->Name ) ] = $op;
	}

	public function getConstraintRules()
	{
		return $this->_constraintRules;
	}
	
	public function addNode( ConstraintTreeNode $node )
	{
		if( is_null( $this->getRootNode() ) )
			$this->setRootNode( $node );
		else
			$this->getRootNode()->merge( $node );
	}
	
	public function evaluate( Token $token )
	{
		$root = $this->getRootNode();
		if( is_null( $root ) )
			return true;
		return $root->evaluate( $token );
	}
}

class EConstraintMerging extends Exception
{
	public function __construct()
	{
		$this->message = "Error merging constraints! No constraints provided.";
	}
}