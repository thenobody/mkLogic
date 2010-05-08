<?php

/*
	abstract Processor class
	- implemented in FilteringProcessor, ValidationProcessor and RequestProcessor
*/

abstract class Processor extends BaseComponent
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
}