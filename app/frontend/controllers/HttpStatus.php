<?php

/*
	http error controller handler
*/
class HttpStatus extends AppController
{

	/*
		page not found
	*/
	public function _404()
	{
			
	}
	
	/*
		not authorized
	*/
	public function _403()
	{
		// forbidden
	}
	
	/*
		page was requested, but Ajax was required
	*/
	public function _noAjax()
	{
		//
	}

}