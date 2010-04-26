<?php

class Users extends AppController
{

	public function _index()
	{
		$this->users = $this->getOutlet()->from('Supervisor')->find();
	}

}