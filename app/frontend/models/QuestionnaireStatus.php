<?php

class QuestionnaireStatus extends Model
{
	public function generateMessage()
	{
		$message = '';
		switch( $this->Value )
		{
			case 'ready':
				$message = 'Questionnaire hasn\'t been opened yet.';
				break;
			case 'active':
				$message = 'Questionnaire is open.';
				break;
			case 'closed':
				$message = 'Questionnaire has been closed.';
				break;
			case 'cancelled':
				$message = 'Questionnaire has been cancelled.';
				break;
			default:
				$message = 'Questionnaire is unavailable.';
				break;
		}
		return $message;
	}
}