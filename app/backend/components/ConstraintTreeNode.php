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
		$_tree,
		$_parent,
		$_lChild,
		$_rChild,
		$_value;
	
	public function __construct( $tree )
	{
		$this->setTree( $tree );
	}
	
	public function parseNode( SimpleXMLElement $node )
	{
		if(	( $node->getName() == 'and' ) ||
			( $node->getName() == 'or' ) ||
			( $node->getName() == 'if' )
		)
			$this->parseLogicalOperatorNode( $node );
		elseif ( $node->getName() == 'constraint' )
			$this->parseConstraintNode( $node );
		else
			throw new EInvalidElement( "Root element has to be 'and', 'or', 'if' or 'constraint'; '{$node->getName()}' given" );
	}
	
	private function parseLogicalOperatorNode( SimpleXMLElement $node )
	{
		$logicalOperators = $this->getTree()->getLogicalOperators();
		$this->setValue( $logicalOperators[ $node->getName() ] );

		$children = array();
		foreach( $node->children() as $childNode )
		{
			$nextNode = new ConstraintTreeNode( $this->getTree() );
			$nextNode->parseNode( $childNode );
			$children[] = $nextNode;
		}

		if( count( $children ) < 2 )
			throw new EInvalidElement( "Element was recognized as 'Logical operator', but operand count is less than 2!" );

		$this->setLeftChild( $children[0] );
		array_shift( $children );
		if( count( $children ) == 1 )
			$this->setRightChild( $children[0] );
		else
		{
			$tmpChild = $this;
			foreach( $children as $child )
			{
				$tmpNode = new ConstraintTreeNode( $this->getTree() );
				$tmpNode->setValue( $this->getValue() );
				$tmpNode->setLeftChild( $child );

				$tmpChild->setRightChild( $tmpNode );
				$tmpChild = $tmpNode;
			}
			$tmpParent = $tmpChild->getParent();
			$tmpParent->setRightChild( $tmpChild->getLeftChild() );
		}
	}
	
	private function parseConstraintNode( SimpleXMLElement $node )
	{
		$constraintRules = $this->getTree()->getConstraintRules();
		$logicalOperators = $this->getTree()->getLogicalOperators();
		
		$questionnaire = $this->getTree()->getQuestionnaire();
		$attributes = $node->attributes();

		if( isset( $constraintRules[ (string) $attributes[ 'rule' ] ] ) )
			$constraintRule = $constraintRules[ (string) $attributes[ 'rule' ] ];
		else
			$constraintRule = $constraintRules[ 'not-empty' ];

		$tmpTree = new ConstraintTreeNode( $this->getTree() );
		//question
		$questions = array();
		$questionConstraints = array();
		$questionName = (string) $attributes[ 'question' ];
		if ( ( $questionName == 'any' ) || ( $questionName == 'all' ) )
			$questions = $questionnaire->getQuestions();
		else
			$questions[] = $questionnaire->getQuestionByName( $questionName );
		
		//question-group
		foreach( $questions as $question )
		{
			$questionGroups = array();
			$questionGroupConstraints = array();
			$questionGroupName = (string) $attributes[ 'question-group' ];
			if ( ( $questionGroupName == 'any' ) || ( $questionGroupName == 'all' ) )
				$questionGroups = $question->getQuestionGroups();
			else
				$questionGroups[] = $question->getQuestionGroupByName( $questionGroupName );
			
			//answer-group
			foreach( $questionGroups as $questionGroup )
			{
				$answerGroups = array();
				$answerGroupConstraints = array();
				$answerGroupName = (string) $attributes[ 'answer-group' ];
				if ( ( $answerGroupName == 'any' ) || ( $answerGroupName == 'all' ) )
					$answerGroups = $questionGroup->getAnswerGroups();
				else
					$answerGroups[] = $questionGroup->getAnswerGroupByName( $answerGroupName );
				
				//answer
				foreach( $answerGroups as $answerGroup )
				{
					$answers = array();
					$answerName = (string) $attributes[ 'answer' ];
					if ( ( $answerName == 'any' ) || ( $answerName == 'all' ) )
						$answers = $answerGroup->getAnswers();
					else
						$answers[] = $answerGroup->getAnswerByName( $answerName );
					
					//constraints
					$answerConstraints = array();
					foreach( $answers as $answer )
					{
						$tmpConstraint = new Constraint();
						$tmpConstraint->setAnswer( $answer );
						$tmpConstraint->setConstraintRule( $constraintRule );
						
						$tmpNode = new ConstraintTreeNode( null );
						$tmpNode->setValue( $tmpConstraint );
						
						$answerConstraints[] = $tmpNode;
					} // foreach answers
					
					if( count( $answerConstraints ) < 1 )
						throw new EConstraintParsing( 'Answer' );
					
					$logOp = ( $answerName == 'any' ) ? 'or' : 'and';
					$tmpRoot = $this->getTree()->mergeNodes( $answerConstraints, $logOp );
					$answerGroupConstraints[] = $tmpRoot;
				} // foreach answer groups
				
				if( count( $answerGroupConstraints ) < 1 )
					throw new EConstraintParsing( 'AnswerGroup' );
				
				$logOp = ( $answerGroupName == 'any' ) ? 'or' : 'and';
				$tmpRoot = $this->getTree()->mergeNodes( $answerGroupConstraints, $logOp );
				$questionGroupConstraints[] = $tmpRoot;
			} // foreach question groups
			
			if( count( $questionGroupConstraints ) < 1 )
				throw new EConstraintParsing( 'QuestionGroup' );

			$logOp = ( $questionGroupName == 'any' ) ? 'or' : 'and';
			$tmpRoot = $this->getTree()->mergeNodes( $questionGroupConstraints, $logOp );
			$questionConstraints[] = $tmpRoot;
		} // foreach question
		
		if( count( $questionConstraints ) < 1 )
			throw new EConstraintParsing( 'Question' );
		
		$logOp = ( $questionName == 'any' ) ? 'or' : 'and';
		$tmpRoot = $this->getTree()->mergeNodes( $questionConstraints, $logOp );
		
		$this->setValue( $tmpRoot->getValue() );
		$this->setLeftChild( $tmpRoot->getLeftChild() );
		$this->setRightChild( $tmpRoot->getRightChild() );
	}
	
	public function convertToCommitSchedule( Collection $output, Question $question, $schemeType )
	{
		if( $this->holdsConstraint() )
		{
			$this->getDB()->save( $this->_value );
			return $this->getValue();
		}
		else
		{
			$scheduleElement = ( $schemeType == ConstraintTree::VALIDATION ) ? new ValidationOrder() : new FilteringOrder();
			$output->add( $scheduleElement );
			$scheduleElement->setQuestion( $question );
			$scheduleElement->setLogicalOperator( $this->getValue() );
			
			$constraint = $this->getLeftChild()->convertToCommitSchedule( $output, $question, $schemeType );
			$nextConstraint = $this->getRightChild()->convertToCommitSchedule( $output, $question, $schemeType );
			$scheduleElement->setConstraint( $constraint );
			$scheduleElement->setNextConstraint( $nextConstraint );
			
			return $constraint;
		}
	}
	
	public function getTree()
	{
		return $this->_tree;
	}
	
	public function setTree( $tree )
	{
		$this->_tree = $tree;
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
	
	public function setValue( QuestionnaireModel $value )
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
			return "Constraint - " . $this->getValue()->getConstraintRule()->Name . "\n";
	}
}