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
		$_token,
		$_questionGraph;
		
	public function __construct( Token $token, QuestionGraph $graph )
	{
		$this->setToken( $token );
		$this->setQuestionGraph( $graph );
	}
	
	public function getToken()
	{
		return $this->_token;
	}
	
	public function setToken( Token $token )
	{
		$this->_token = $token;
	}
	
	public function getQuestionGraph()
	{
		return $this->_questionGraph;
	}
	
	public function setQuestionGraph( QuestionGraph $questionGraph )
	{
		$this->_questionGraph = $questionGraph;
	}
	
	/*
		- returns true if all constraints were matched, otherwise false
		- method receives QuestionGraphNode model as argument,
	*/
	public function evaluateNode( QuestionGraphNode $node )
	{
		$token = $this->getToken();
		$graph = $this->getQuestionGraph();
		$tree = $this->buildTree( $node );
		
		return $tree->evaluate( $token, $graph );
	}
	
	private function buildTree( QuestionGraphNode $node )
	{
		$question = $node->getQuestion();
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
