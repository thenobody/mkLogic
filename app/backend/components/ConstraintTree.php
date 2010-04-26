<?php

/*
	Internal constraint tree builder
	uses logical operators AND, OR, IF to connect constraints
*/

class ConstraintTree extends BaseComponent
{
	private
		$_xml,
		$_root,
		$_schemeType,
		$_questionnaire,
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
	
	public function getXml()
	{
		return $this->_xml;
	}

	public function setXml( SimpleXMLElement $xml )
	{
		$this->_xml = $xml;
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
	
	public function getQuestionnaire()
	{
		return $this->_questionnaire;
	}
	
	public function setQuestionnaire( Questionnaire $questionnaire )
	{
		$this->_questionnaire = $questionnaire;
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
	
	public function parse()
	{
		if( is_null( $this->getQuestionnaire() ) || is_null( $this->getXml() ) )
			throw new EParametersNotSet( 'Questionnaire' );
		
		if( ( $this->getXml()->getName() != 'validation' ) && ( $this->getXml()->getName() != 'filtering' ) )
			throw new EInvalidElement( "Root element has to be 'validation' or 'filtering'; '{$this->getXml()->getName()}' given" );
		
		$schemeType = ( $this->getXml()->getName() == 'validation' ) ? ConstraintTree::VALIDATION : ConstraintTree::FILTERING;
		$this->setSchemeType( $schemeType );
		
		$children = $this->getXml()->children();
		if( count( $children ) > 1 )
			throw new EInvalidElement( "Root element contains more than one constraint!" );
		
		$root = new ConstraintTreeNode( $this );
		$this->setRootNode( $root );
		$root->parseNode( $children[0] );
	}
	
	public function mergeNodes( $nodes, $logOp )
	{
		if( count( $nodes ) < 1 )
			throw new EConstraintMerging();
		
		if( count( $nodes ) == 1 )
			return $nodes[0];
		
		$logOps = $this->getLogicalOperators();
		$logOp = ( isset( $logOps[ $logOp ] ) ) ? $logOps[ $logOp ] : $logOps[ 'and' ];
		
		$tmpRoot = new ConstraintTreeNode( null );
		$tmpRoot->setValue( $logOp );
		$tmpNode = $tmpRoot;
		
		foreach( $nodes as $node )
		{
			$tmpNode->setLeftChild( $node );
			$rightChild = new ConstraintTreeNode( null );
			$rightChild->setValue( $logOp );
			$tmpNode->setRightChild( $rightChild );
			$tmpNode = $rightChild;
		}

		$tmpNode = $tmpNode->getParent();
		$tmpNode->setValue( $tmpNode->getLeftChild()->getValue() );
		$tmpNode->setRightChild( $tmpNode->getLeftChild()->getRightChild() );
		$tmpNode->setLeftChild( $tmpNode->getLeftChild()->getLeftChild() );
		
		return $tmpRoot;
	}
	
	public function generateOutletCollection( Question $question )
	{
		$result = new Collection();
		$constraint = $this->getRootNode()->convertToCommitSchedule( $result, $question, $this->getSchemeType() );

		if( count( $result ) < 1 )
		{
			$scheme = null;
			$logOps = $this->getLogicalOperators();
			if( $this->getSchemeType() == ConstraintTree::VALIDATION )
				$scheme = new ValidationOrder();
			else
				$scheme = new FilteringOrder();
			
			$scheme->setQuestion( $question );
			$scheme->setConstraint( $constraint );
			$scheme->setNextConstraint( $constraint );
			$scheme->setLogicalOperator( $logOps[ 'and' ] );
			
			$result->add( $scheme );
		}
		return $result;
	}
}

class EParametersNotSet extends Exception
{
	public function __construct( $model )
	{
		$this->message = "'$model' model or XML data were not set prior to parsing!";
	}
}
class EConstraintParsing extends Exception
{
	public function __construct( $level )
	{
		$this->message = "Error parsing constraints at '$level' level! (incorrect number of constraints)";
	}
}
class EConstraintMerging extends Exception
{
	public function __construct()
	{
		$this->message = "Error merging constraints! No constraints provided.";
	}
}