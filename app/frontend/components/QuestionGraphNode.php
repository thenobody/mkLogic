<?php

/*
	QuestionGraphNode class
	- element of QuestionGraph
	- contains references to:
		1.	Question model
		2.	next QuestionGraphNodes
*/

class QuestionGraphNode
{
	private
		$_question,
		$_neighbours;
	
	public function __construct( Question $question )
	{
		$this->setQuestion( $question );
	}
	
	public function getQuestion()
	{
		return $this->_question;
	}
	
	public function setQuestion( Question $question )
	{
		$this->_question = $question;
	}
	
	public function getNeighbours()
	{
		if( is_null( $this->_neighbours ) )
			$this->_neighbours = new Collection();
		return $this->_neighbours;
	}
	
	public function hasNeighbours()
	{
		return !$this->getNeighbours()->isEmpty();
	}
	
	public function setNeighbours( Collection $neighbours )
	{
		$this->_neighbours = $neighbours;
	}
	
	public function addNeighbour( QuestionGraphNode $node )
	{
		if( !$this->containsNeighbour( $node ) )
			$this->getNeighbours()->add( $node );
	}
	
	public function containsNeighbour( QuestionGraphNode $node )
	{
		$neighbours = $this->getNeighbours();
		foreach( $neighbours as $neighbour )
		{
			if( $neighbour->getQuestion()->Name == $node->getQuestion()->Name )
				return true;
		}
		return false;
	}
	
	public function getLastAnsweredChild( Token $token )
	{
		if( !$this->hasNeighbours() )
			return false;
		
		foreach( $this->getNeighbours() as $node )
		{
			$question = $node->getQuestion();
			
			$test = $question->hasUserAnswers( $token );
			if( $question->hasUserAnswers( $token ) )
				return $node->getLastAnsweredChild( $token );
		}
		
		return $this;
	}

}