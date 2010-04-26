<?php

class Answer extends QuestionnaireModel
{
	private
		$_answerGroup,
		$_constraints;
	
	public function getAnswerGroup()
	{
		return $this->_answerGroup;
	}
	
	public function setAnswerGroup( AnswerGroup $answerGroup )
	{
		$this->_answerGroup = $answerGroup;
	}
	
	public function getConstraints()
	{
		if ( is_null( $this->_constraints ) )
			$this->_constraints = new Collection();
		return $this->_constraints;
	}
	
	public function setConstraints( Collection $constraints )
	{
		$this->_constraints = $constraints;
	}
}