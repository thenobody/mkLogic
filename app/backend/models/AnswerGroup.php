<?php

class AnswerGroup extends QuestionnaireModel
{
	private
		$_questionGroup,
		$_answers,
		$_answerType;
	
	public function getQuestionGroup()
	{
		return $this->_questionGroup;
	}
	
	public function setQuestionGroup( QuestionGroup $questionGroup )
	{
		$this->_questionGroup = $questionGroup;
	}
	
	public function getAnswers()
	{
		if( is_null( $this->_answers ) )
			$this->setAnswers( new Collection() );
		return $this->_answers ;
	}

	public function setAnswers( Collection $answers )
	{
		$this->_answers = $answers;
	}
	
	public function getAnswerType()
	{
		return $this->_answerType;
	}
	
	public function setAnswerType( AnswerType $answerType )
	{
		$this->_answerType = $answerType;
	}
	
	public function getAnswerByName( $name, $default = false )
	{
		foreach( $this->getAnswers() as $answer )
		{
			if( $answer->Name == $name )
				return $answer;
		}
		return $default;
	}

}