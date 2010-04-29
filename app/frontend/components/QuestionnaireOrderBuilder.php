<?php

/*
	Component serves as
	-	a Answer tree builder ( order is based on QuestionnaireOrder table )
	-	a tool for filling nodes of Answer tree with answers given under the specified Token
	-	an updater for provided Questionnaire model, which sets next question to be presented to the respondent
*/

class QuestionnaireOrderBuilder extends BaseComponent
{
	private
		$_token,
		$_questionTree;
	
	public function __construct( Token $token )
	{
		$this->setToken( $token );
		
		$this->buildQuestionTree();
		$this->setCurrentQuestion();
	}
	
	public function getQuestionnaire()
	{
		return $this->getToken()->getQuestionnaire();
	}
	
	public function getToken()
	{
		return $this->_token;
	}
	
	public function setToken( Token $token )
	{
		$this->_token = $token;
	}
	
	public function getQuestionTree()
	{
		if( is_null( $this->_questionTree ) )
			$this->setQuestionTree( new QuestionTree() );
		return $this->_questionTree;
	}
	
	public function setQuestionTree( QuestionTree $questionTree )
	{
		$this->_questionTree = $questionTree;
	}
	
	private function buildQuestionTree()
	{
		$questionTree = $this->getQuestionTree();
		foreach( $this->getQuestionnaire()->getQuestions() as $question )
			$questionTree->addQuestion( $question );
	}
	
	public function getCurrentQuestion()
	{
		
	}
	
}