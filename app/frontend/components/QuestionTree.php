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
	
	private function updateRoot( QuestionTreeNode $merger )
	{
		$root = $this->getRoot();
		if( is_null( $root ) )
			$this->setRoot( $merger );
		else
		{
			if( $merger->getChildByQuestion( $root->getQuestion(), false ) )
				$this->setRoot( $merger );
			else if( !$root->getChildByQuestion( $merger->getQuestion(), false ) )
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
			$this->updateRoot( $node );
		}
	}
	
	public function getNodeByQuestion( Question $question, $default = false )
	{
		$root = $this->getRoot();
		return $root->getChildByQuestion( $question, $default );
	}
	
	public function addAnswer( UserAnswer $userAnswer )
	{
		$question = $userAnswer->getAnswer()->getAnswerGroup()->getQuestionGroup()->getQuestion();
		$node = $this->getNodeByQuestion( $question, false );
		if( !$node )
			throw new EInvalidAnswer();
		$node->setUserAnswer( $userAnswer );
	}
	
	public function getPossibleNextQuestions()
	{
		$root = $this->getRoot();
		if( !$root->hasUserAnswer() )
		{
			$result = new Collection();
			$result->add( $root );
			return $result;
		}

		$node = $root->getLastAnsweredChild();
		return $node->getChildren();
	}

}

class EDisjointingNodes extends Exception { }
class EInvalidAnswer extends Exception { }