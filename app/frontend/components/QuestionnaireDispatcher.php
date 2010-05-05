<?php

/*
	QuestionnaireDispatcher class
	- prepares Questionnaire model for presentation to the respondent
	- retrieves model
	- validates previous answer(s), if provided
	- saves previous answer(s)
	- finds next question which should be presented to the respondent
*/

class QuestionnaireDispatcher extends BaseComponent
{
	private
		$_tokenModel,
		$_errors,
		$_nextQuestion;
	
	public function __construct( Token $tokenModel )
	{
		$this->setToken( $tokenModel );
	}
	
	public function getToken()
	{
		return $this->_tokenModel;
	}
	
	public function setToken( Token $tokenModel )
	{
		$this->_tokenModel = $tokenModel;
	}
	
	public function getErrors()
	{
		if( is_null( $this->_errors ) )
			$this->_errors = new ParameterHolder();
		return $this->_errors;
	}
	
	public function hasErrors()
	{
		return !$this->getErrors()->isEmpty();
	}
	
	public function getNextQuestion()
	{
		return $this->_nextQuestion;
	}
	
	private function setNextQuestion( Question $question )
	{
		$this->_nextQuestion = $question;
	}
	
	public function prepare()
	{
		$this->validateAndSaveAnswers(); // mega todo
		$nextQuestion = $this->findNextQuestion();
		$this->setNextQuestion( $nextQuestion );
	}
	
	private function generateQuestionGraph()
	{
		$token = $this->getToken();
		$questionnaire = $token->getQuestionnaire();
		
		$questionGraph = new QuestionGraph( $questionnaire );
		
		return $questionGraph;
	}
	
	private function validateAndSaveAnswers()
	{
		// validate questionnaire availability
		// validate answer
		// save this shit
	}
	
	private function findNextQuestion()
	{
		$token = $this->getToken();
		$graph = $this->generateQuestionGraph();
		$nextQuestion = $this->getFilteredNextQuestion( $graph );
		
		return $nextQuestion;
	}
	
	private function getFilteredNextQuestion( QuestionGraph $graph )
	{
		$token = $this->getToken();
		$currentNode = $graph->getCurrentNodeFor( $token );
		$filtering = new FilteringProcessor( $token, $graph );

		// if node contains first Question of Questionnaire, return node
		if( !$currentNode->getQuestion()->hasUserAnswers( $token ) )
			return $currentNode->getQuestion();
		
		// else evaluate constraints of next neighbours
		foreach( $currentNode->getNeighbours() as $node )
			if( $filtering->evaluateNode( $node ) )
				return $node->getQuestion();

		return false;
	}
}