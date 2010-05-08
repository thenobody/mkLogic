<?php

class QuestionGroup extends Model
{
	private
		$_question,
		$_answerGroups;

	public function getQuestion()
	{
		return $this->_question;
	}

	public function setQuestion( Question $question )
	{
		$this->_question = $question;
	}
	
	public function getAnswerGroups()
	{
		return $this->_answerGroups;
	}

	public function setAnswerGroups( Collection $answerGroups )
	{
		$this->_answerGroups = $answerGroups;
	}
	
	public function getAnswerGroupByName( $name, $default = false )
	{
		foreach( $this->getAnswerGroups() as $answerGroup )
			if( $answerGroup->Name == $name )
				return $answerGroup;
		return $default;
	}
}