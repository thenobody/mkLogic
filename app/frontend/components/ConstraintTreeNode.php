<?php

/*
	Constraint Tree Node
	valid values:
		1.	constraint model
		2.	logical operator model
*/

class ConstraintTreeNode extends BaseComponent
{
	private
		$_parent,
		$_lChild,
		$_rChild,
		$_value;
	
	public function buildFromOrder( $order )
	{
		if( $order->getConstraint() == $order->getNextConstraint() )
			$this->setValue( $order->getConstraint() );
		else
		{
			$logOp = $order->getLogicalOperator();
			$lChild = new ConstraintTreeNode();
			$lChild->setValue( $order->getConstraint() );
			$rChild = new ConstraintTreeNode();
			$rChild->setValue( $order->getNextConstraint() );

			$this->setValue( $logOp );
			$this->setLeftChild( $lChild );
			$this->setRightChild( $rChild );
		}
	}
	
	public function getParent()
	{
		return $this->_parent;
	}
	
	public function setParent( ConstraintTreeNode $parent )
	{
		$this->_parent = $parent;
	}
	
	public function hasParent()
	{
		return !is_null( $this->getParent() );
	}
	
	public function getLeftChild()
	{
		return $this->_lChild;
	}
	
	public function setLeftChild( $lChild )
	{
		$this->_lChild = $lChild;
		if( $this->hasLeftChild() )
			$this->_lChild->setParent( $this );
	}
	
	public function hasLeftChild()
	{
		return !is_null( $this->getLeftChild() );
	}
	
	public function isLeftChild()
	{
		if( !$this->hasParent() )
			return false;
		return ( $this->getParent()->getLeftChild() == $this );
	}
	
	public function removeLeftChild()
	{
		unset( $this->_lChild );
		$this->setLeftChild( null );
	}
	
	public function getRightChild()
	{
		return $this->_rChild;
	}
	
	public function setRightChild( $rChild )
	{
		$this->_rChild = $rChild;
		if( $this->hasRightChild() )
			$this->_rChild->setParent( $this );
	}
	
	public function hasRightChild()
	{
		return !is_null( $this->getRightChild() );
	}
	
	public function isRightChild()
	{
		if( !$this->hasParent() )
			return false;
		return ( $this->getParent()->getRightChild() == $this );
	}
	
	public function removeRightChild()
	{
		unset( $this->_rChild );
		$this->setRightChild( null );
	}
	
	public function hasChildren()
	{
		return ( $this->hasLeftChild() || $this->hasRightChild() );
	}
	
	public function getValue()
	{
		return $this->_value;
	}
	
	public function setValue( $value )
	{
		$this->_value = $value;
	}
	
	public function holdsLogicalOperator()
	{
		return ( get_class( $this->getValue() ) == 'LogicalOperator' ) || ( get_class( $this->getValue() ) == 'LogicalOperator_OutletProxy' );
	}
	
	public function holdsConstraint()
	{
		return ( get_class( $this->getValue() ) == 'Constraint' ) || ( get_class( $this->getValue() ) == 'Constraint_OutletProxy' );
	}
	
	public function getChildByConstraint( Constraint $constraint, $default = false )
	{
		if( $this->getValue() == $constraint )
			return $this;
		
		if( $this->hasLeftChild() )
		{
			$node = $this->getLeftChild()->getChildByConstraint( $constraint, $default );
			if( $node != $default )
				return $node;
		}
		if( $this->hasRightChild() )
		{
			$node = $this->getRightChild()->getChildByConstraint( $constraint, $default );
			if( $node != $default )
				return $node;
		}
		
		return $default;
	}
	
	public function merge( ConstraintTreeNode $node )
	{
		$child = $this->getChildByConstraint( $node->getLeftChild()->getValue() );
		if( !$child )
			$child = $this->getChildByConstraint( $node->getRightChild()->getValue() );
		if( !$child )
			throw new EConstraintNotFound();
		
		if( $child->hasChildren() )
			throw new ENotASingleLevelNode();
		$child->setValue( $node->getValue() );
		$child->setLeftChild( $node->getLeftChild() );
		$child->setRightChild( $node->getRightChild() );
	}
	
	public function evaluate( Token $token )
	{
		if( $this->hasChildren() )
			return $this->evaluateByLogicalOperator( $token );
		else
			return $this->evaluateByConstraint( $token );
	}
	
	private function evaluateByLogicalOperator( Token $token )
	{
		$result = false;
		$logOp = $this->getValue();
		switch( $logOp->Name )
		{
			case 'AND':
				$left = $this->getLeftChild()->evaluate( $token );
				$right = $this->getRightChild()->evaluate( $token );
				$result = $left && $right;
				break;
			
			case 'OR':
				$left = $this->getLeftChild()->evaluate( $token );
				$right = $this->getRightChild()->evaluate( $token );
				$result = $left || $right;
				break;
			
			case 'IF':
				$left = $this->getLeftChild()->evaluate( $token );
				$right = $this->getRightChild()->evaluate( $token );
				$result = ( $left && ( !$right ) ) ? false : true;
				break;
		}
		return $result;
	}
	
	private function evaluateByConstraint( Token $token )
	{
		$result = false;
		$constraint = $this->getValue();
		$userAnswer = $this->getDB()->from( 'UserAnswer' )->
						where( '{UserAnswer.AnswerId} = ? AND {UserAnswer.TokenId} = ?',
							array(
								$constraint->getAnswer()->Id,
								$token->Id,
							)
						)->findOne();
		
		switch( $constraint->getConstraintRule()->Name )
		{
			case 'not-empty':
				$result = is_object( $userAnswer );
				break;
			
			case 'empty':
				$result = !is_object( $userAnswer );
				break;
		}
		
		return $result;
	}
	
	public function toString( $count )
	{
		$spaces = "";
		for ( $i=0; $i < $count; $i++ )
			$spaces .= "&nbsp;&nbsp;&nbsp;";

		if( get_class( $this->getValue() ) == 'LogicalOperator_OutletProxy' )
		{
			$leftChild = ( is_null( $this->getLeftChild() ) ) ? "null" : $this->getLeftChild()->toString( $count+1 );
			$rightChild = ( is_null( $this->getRightChild() ) ) ? "null" : $this->getRightChild()->toString( $count+1 );
			return $this->getValue()->Name . "<br />$spaces" . $leftChild . "<br />\n$spaces" . $rightChild ;
		}
		else
			return "Constraint: " . $this->getValue()->getAnswer()->Name . " - " . $this->getValue()->getConstraintRule()->Name . "\n";
	}
}

class ENotASingleLevelNode extends Exception { }
class EConstraintNotFound extends Exception { }