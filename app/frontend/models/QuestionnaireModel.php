<?php

/*
	abstract class of Questionnaire model and it's children models
*/

abstract class QuestionnaireModel extends Model
{
	private
		// xml node
		$_xml;
	
	public function setXml( SimpleXMLElement $xml )
	{
		$this->_xml = $xml;
	}

	public function getXml()
	{
		return $this->_xml;
	}
}