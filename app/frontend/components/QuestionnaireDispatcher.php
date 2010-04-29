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
		$questionnaire = $this->getToken()->getQuestionnaire();
		$this->validateAndSaveAnswers(); // mega todo
		$nextQuestion = $this->findNextQuestion();
		$this->setNextQuestion( $nextQuestion );
	}
	
	private function validateAndSaveAnswers()
	{
	
	}
	
	private function findNextQuestion()
	{
		$token = $this->getToken();
		$questionnaire = $token->getQuestionnaire();
		$questions = $questionnaire->getQuestions();
		$questionTree = new QuestionTree();
		
		foreach( $questions as $question )
			$questionTree->addQuestion( $question );
		
		$answers = $token->getUserAnswers();
		foreach( $answers as $answer )
			$questionTree->addAnswer( $answer );
		
		$nextQuestions = $questionTree->getPossibleNextQuestions();
		
		// filtering process on possible questions
		// TODO
		
		return $nextQuestions[0]->getQuestion();
	}
}