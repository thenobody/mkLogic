<?php

/*
	Class FilteringProcessor
	- builds Constraint tree for given question and
	- evaluates filtering predicates on domain
	of answers previously submitted by respondent
	- respondent is identified by Token object
*/

class FilteringProcessor extends Processor
{
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
