<?php

class QuestionTree extends BaseComponent
{
	private
		$_root;
	
	public function getRoot()
	{
		return $this->_root;
	}
	
	public function setRoot( QuestionTreeNode $root )
	{
		$this->_root = $root;
	}
	
	private function updateTree( QuestionTreeNode $merger )
	{
		$root = $this->getRoot();
		if( is_null( $root ) )
			$this->setRoot( $merger );
		else
		{
			if( !$merger->getChildrenByQuestion( $root->getQuestion() )->isEmpty() )
				$this->setRoot( $merger );
			else if( $root->getChildrenByQuestion( $merger->getQuestion() )->isEmpty() )
				throw new EDisjointingNodes();
		}
	}
	
	public function addQuestion( Question $question )
	{
		$root = $this->getRoot();
		if( is_null( $root ) )
			$this->setRoot( new QuestionTreeNode( $question ) );
		else
		{
			$node = new QuestionTreeNode( $question );
			$this->updateTree( $node );
		}
	}
	
	public function getNodesByQuestion( Question $question, $default = false )
	{
		$root = $this->getRoot();
		return $root->getChildrenByQuestion( $question );
	}
	
	public function addAnswer( UserAnswer $userAnswer )
	{
		$question = $userAnswer->getAnswer()->getAnswerGroup()->getQuestionGroup()->getQuestion();
		$nodes = $this->getNodesByQuestion( $question );
		if( $nodes->isEmpty() )
			throw new EInvalidAnswer();
		
		foreach( $nodes as $node )
			$node->setUserAnswer( $userAnswer );
	}
	
	public function getPossibleNextQuestions()
	{
		$root = $this->getRoot();
		$result = new Collection();
		if( !$root->hasUserAnswer() )
		{
			$result->add( $root->getQuestion() );
			return $result;
		}

		$node = $root->getLastAnsweredChild();
		
		foreach( $node->getChildren() as $child )
			$result->add( $child->getQuestion() );
		
		return $result;
	}

}

class EDisjointingNodes extends Exception { }
class EInvalidAnswer extends Exception { }