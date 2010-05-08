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
	-- this is subject to possible change
	* answer-id is omitted if answer-group type is not of aggregate type (i.e. check-box)
*/

class WidgetBuilder
{
	const
		TYPE_RADIO		= 'radio',
		TYPE_CHECKBOX	= 'checkbox',
		TYPE_TEXT		= 'text',
		TYPE_COMBO		= 'combobox',
		
		NAME_PREFIX		= 'answers';
	
	static public function getWidget( Model $model, $label = true )
	{
		$class = get_class( $model );
		$class = preg_replace( '/\_OutletProxy/', '', $class );
		
		$method = 'get' . $class . 'Widget';
		return self::$method( $model, $label );
	}
	
	static public function getLabel( Model $model, $name = false )
	{
		if( !$name )
			$name = self::generateWidgetName( $model );

		$format = '<label for="%s">%s</label>';
		$result = sprintf( $format, $name, $model->Label );
		return $result;
	}
	
	static public function getFormTag( Question $question, $action = '', $method = 'post' )
	{
		$format = '<form name="%s" action="%s" method="%s">';
		return sprintf( $format, $question->Name, $action, $method );
	}
	
	static public function getSubmitButton( $label )
	{
		$format = '<button name="submit" type="submiy" value="submit">%s</button>';
		return sprintf( $format, $label );
	}
	
	static public function getAnswerGroupName( AnswerGroup $model )
	{
		$answerGroup = $model;
		$questionGroup = $answerGroup->getQuestionGroup();
		$question = $questionGroup->getQuestion();
		$questionnaire = $question->getQuestionnaire();
		
		$strName = "{$question->Name}_{$questionGroup->Name}_{$answerGroup->Name}";
		$answerGroupName = base64_encode( $strName );
		$answerGroupName = preg_replace( '/\=/', '', $answerGroupName );
		
		$answerGroupName = sprintf( self::NAME_PREFIX . '[%s][ ]', $answerGroupName );
		
		return $answerGroupName;
	}
	
	static public function getAnswerName( Answer $model )
	{
		$answerGroup = $model->getAnswerGroup();
		$type = $answerGroup->getAnswerType()->Name;
		$answerGroupName = self::getAnswerGroupName( $answerGroup );
		
		if( ( $type == self::TYPE_RADIO ) || ( $type == self::TYPE_COMBO ) )
			return $answerGroupName;

		$answerGroupName = preg_replace( '/\[ \]$/', '', $answerGroupName );
		
		$answerName = base64_encode( $model->Name );
		$answerName = preg_replace( '/\=/', '', $answerName );
		$answerName = sprintf( '%s[%s]', $answerGroupName, $answerName );
		
		return $answerName;
	}
	
	static public function getAnswerValue( Answer $model )
	{
		$type = $model->getAnswerGroup()->getAnswerType()->Name;
		$value = '';
		if( ( $type != self::TYPE_RADIO ) && ( $type != self::TYPE_COMBO ) )
			$value = $model->Value;
		else
			$value = $model->Name;
		
		$answerValue = base64_encode( $value );
		$answerValue = preg_replace( '/\=/', '', $answerValue );
		
		return $answerValue;
	}
	
	static public function getAnswerGroupWidget( AnswerGroup $model )
	{
		$type = $model->getAnswerType()->Name;
		
		if( $type != self::TYPE_COMBO )
			return false;
		
		$answerGroupName = self::getAnswerGroupName( $model );
		$result = '<select name="' . $answerGroupName . '">';
		
		foreach( $model->getAnswers() as $answer )
		{
			$answerValue = self::getAnswerValue( $answer );
			$answerLabel = $answer->Label;
			$format = '<option value="%s">%s</option>';
			$result .= sprintf( $format, $answerValue, $answerLabel );
		}

		$result .= '</select>';
		
		return $result;
	}
	
	static public function getAnswerWidget( Answer $model, $label = true )
	{
		$type = $model->getAnswerGroup()->getAnswerType()->Name;
		
		$method = 'get' . ucfirst( $type ) . 'AnswerWidget';
		return self::$method( $model, $label );
	}
	
	static public function getRadioAnswerWidget( Answer $model, $label = true )
	{
		$format = '<input type="radio" name="%s" value="%s" />';
		$answerName = self::getAnswerName( $model );
		$answerValue = self::getAnswerValue( $model );
		
		$widget = '';
		if( $label )
			$widget .= self::getLabel( $model, $answerName );
		
		$widget .= sprintf( $format, $answerName, $answerValue );
		
		return $widget;
	}
	
	static public function getCheckboxAnswerWidget( Answer $model, $label = true )
	{
		$format = '<input type="checkbox" name="%s" value="%s" />';
		$answerName = self::getAnswerName( $model );
		$answerValue = self::getAnswerValue( $model );

		$widget = '';
		if( $label )
			$widget .= self::getLabel( $model, $answerName );

		$widget .= sprintf( $format, $answerName, $answerValue );

		return $widget;
	}
	
	static public function getTextAnswerWidget( Answer $model, $label = true )
	{
		$format = '<input type="text" name="%s" />';
		$answerName = self::getAnswerName( $model );
		$answerValue = self::getAnswerValue( $model );

		$widget = '';
		if( $label )
			$widget .= self::getLabel( $model, $answerName );

		$widget .= sprintf( $format, $answerName );

		return $widget;
	}
	
	static public function getComboboxAnswerWidget( Answer $model, $label = null )
	{
		$format = '<option value="%s">%s</option>';
		$answerValue = self::getAnswerValue( $model );
		$answerLabel = $model->Label;

		$widget = sprintf( $format, $answerValue, $answerLabel );

		return $widget;
	}

}
