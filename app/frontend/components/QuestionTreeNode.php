<?php

class QuestionTreeNode
{
	private
		$_question,
		$_userAnswer,
		$_children;
	
	public function __construct( Question $question )
	{
		$this->setQuestion( $question );
		$this->populateChildren();
	}
	
	private function populateChildren()
	{
		foreach( $this->getQuestion()->getQuestions()->toArray() as $nextQuestion )
			$this->addChild( new QuestionTreeNode( $nextQuestion ) );
	}
	
	public function getQuestion()
	{
		return $this->_question;
	}
	
	public function setQuestion( Question $question )
	{
		$this->_question = $question;
	}
	
	public function getUserAnswer()
	{
		return $this->_userAnswer;
	}
	
	public function setUserAnswer( UserAnswer $answer )
	{
		$this->_userAnswer = $answer;
	}
	
	public function hasUserAnswer()
	{
		return !is_null( $this->getUserAnswer() );
	}
	
	public function getChildren()
	{
		if( is_null( $this->_children ) )
			$this->_children = new Collection();
		return $this->_children;
	}
	
	public function setChildren( Collection $nodes )
	{
		$this->_children = $nodes;
	}
	
	public function hasChildren()
	{
		return !$this->getChildren()->isEmpty();
	}
	
	public function addChild( QuestionTreeNode $node )
	{
		$this->getChildren()->add( $node );
	}
	
	public function getChildrenByQuestion( Question $question, Collection $children = null )
	{
		if( is_null( $children ) )
			$children = new Collection();
		
		if( $this->getQuestion() == $question )
			$children->add( $this );
		
		foreach( $this->getChildren() as $child )
			$child->getChildrenByQuestion( $question, $children );
		
		return $children;
	}
	
	public function getChildByQuestionName( $question, $default = false )
	{
		if( $this->getQuestion()->Name == $question )
			return $this;

		foreach( $this->getChildren() as $child )
		{
			if( $node = $child->getChildByQuestionName( $question ) )
				return $node;
		}
		return $default;
	}
	
	public function getLastAnsweredChild()
	{
		foreach( $this->getChildren() as $child )
		{
			if( $child->hasUserAnswer() )
				return $child->getLastAnsweredChild();
		}
		return $this;
	}
	
}