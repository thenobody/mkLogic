<?php

/*
	class WidgetBuilder
	- used in Question view
	- generates XHTML code from provided model (Answer, AnswerGroup)
	- XHTML consists of:
		1.	widget (input, textarea, combobox, etc)
		2.	label for this widget (optional)
	
	note: generated widgets have names which are base64'd strings of
	{questionnaire-id}_{question-id}_{question-group-id}_{answer-group-id}--{answer-id}
	* answer-id is omitted if answer-group type is not of aggregate type (i.e. check-box)
*/

class WidgetBuilder
{
	const
		TYPE_RADIO		= 'radio',
		TYPE_CHECKBOX	= 'checkbox',
		TYPE_COMBO		= 'combobox',
		TYPE_TEXT		= 'text';
	
	static public function getWidget( Model $model, $name = null, $label = true )
	{
		$class = get_class( $model );
		$class = preg_replace( '/\_OutletProxy/', '', $class );
		
		$method = 'generate' . $class . 'Widget';
		return self::$method( $model, $name, $label );
	}
	
	static public function getLabel( Model $model, $name = null )
	{
		if( is_null( $name ) )
			$name = self::generateWidgetName( $model );

		$format = '<label for="%s">%s</label>';
		$result = sprintf( $format, $name, $model->Label );
		return $result;
	}
	
	static public function getFormTag( $name, $action = '', $method = 'post' )
	{
		$format = '<form name="%s" action="%s" method="%s">';
		return sprintf( $format, $name, $action, $method );
	}
	
	static public function getSubmitButton( $label )
	{
		$format = '<button name="submit" type="submiy" value="submit">%s</button>';
		return sprintf( $format, $label );
	}
	
	static public function generateWidgetName( Model $model )
	{
		$answerGroup = $model->getAnswerGroup();
		$questionGroup = $answerGroup->getQuestionGroup();
		$question = $questionGroup->getQuestion();
		$questionnaire = $question->getQuestionnaire();

		$type = $answerGroup->getAnswerType()->Name;
		$value = $model->Value;
		$widgetName = '';
		
		$strName = "{$questionnaire->Id}_{$question->Id}_{$questionGroup->Id}_{$answerGroup->Id}";
		$widgetName = base64_encode( $strName );
		
		if( $type != self::TYPE_RADIO )
			$widgetName .= '-' . base64_encode( $model->Id );
		
		$widgetName = preg_replace( '/\=/', '', $widgetName );
		
		return $widgetName;
	}
	
	static public function generateAnswerWidget( $model, $name, $label )
	{
		$result = '';
		$answerGroup = $model->getAnswerGroup();
		$questionGroup = $answerGroup->getQuestionGroup();
		$question = $questionGroup->getQuestion();
		$questionnaire = $question->getQuestionnaire();
		
		$type = $answerGroup->getAnswerType()->Name;
		$value = $model->Value;

		if( is_null( $name ) )
			$widgetName = self::generateWidgetName( $model );
		else
			$widgetName = $name;
		
		if( $label )
			$result = self::getLabel( $model, $name );
		
		switch( $type )
		{
			case self::TYPE_RADIO:
			case self::TYPE_CHECKBOX:
			case self::TYPE_TEXT:
				$format = '<input name="%s" type="%s" value="%s" />';
				$result .= sprintf( $format, $widgetName, $type, $value );
				break;
			
			case self::TYPE_COMBO:
				$format = '<option value="%s">%s</option>';
				$result .= sprintf( $format, $widgetName, $model->Label );
		}
		
		return $result;
	}
	
	static public function generateAnswerGroupWidget( $model, $name, $label )
	{
		$result = '';
		$type = $model->getAnswerType()->Name;
		
		switch( $type )
		{
			case self::TYPE_RADIO:
			case self::TYPE_CHECKBOX:
			case self::TYPE_TEXT:
				foreach( $model->getAnswers() as $answer )
					$result .= self::getWidget( $answer );
				break;

			case self::TYPE_COMBO:
				$result .= '<select>';
				foreach( $model->getAnswers() as $answer )
					$result .= self::getWidget( $answer );
				$result .= '</select>';
		}
		
		return $result;
	}
}
