<?php

/*
	RequestProcessor class
	- class uses variables from Request
	- builds UserAnswer models with Token and
	- adds UserAnswer models to Questions in QuestionGraph
*/

class RequestProcessor extends Processor
{

	public function addAnswers()
	{
		$request = $this->getRequest();
		$answers = $request->getParameters()->get( WidgetBuilder::NAME_PREFIX );
		
		if( !$answers )
			return;
		
		foreach( $answers as $answerName => $answerValues )
			$this->processAnswer( $answerName, $answerValues );
	}
	
	private function processAnswer( $answerName, $answerValues )
	{
		$token = $this->getToken();
		$answerNameD = base64_decode( $answerName );
		$path = explode( '_', $answerNameD );
		
		$graph = $this->getQuestionGraph();
		$node = $graph->getNodeByQuestionName( $path[0], false );
		if( !$node )
			throw new EInvalidRequestQuestion( $answerNameD );
			
		$question = $node->getQuestion();
		$questionGroup = $question->getQuestionGroupByName( $path[1], false );
		$answerGroup = $questionGroup->getAnswerGroupByName( $path[2], false );
		
		foreach( $answerValues as $name => $value )
		{
			$answerName = ( $tmpName = base64_decode( $name ) ) ? $tmpName : base64_decode( $value );
			if( !$answerName )
				throw new EInvalidRequestAnswer( "$name => $value" );
			$answer = $answerGroup->getAnswerByName( $answerName, false );
			if( !$answer )
				throw new EAnswerNotFound( "$name => $value" );
			$txtValue = ( $answer->Text ) ? $value : null;
			$answer->addUserAnswerByToken( $token, $txtValue );
		}
	}
}

class EInvalidRequestQuestion extends Exception { }
class EInvalidRequestAnswer extends Exception { }
class EAnswerNotFound extends Exception { }