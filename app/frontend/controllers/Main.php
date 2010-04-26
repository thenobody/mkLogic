<?php

class Main extends AppController
{

	public function _index()
	{
		$token = $this->getRequest()->get( 'token' );
		$model = $this->getOutlet()->
			from( 'Token' )->
			where( '{Token.Token} = ?', array(
				$token
			) )->
			findOne();
		$this->model = $model;
	}
}