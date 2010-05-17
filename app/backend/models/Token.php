<?php

class Token extends Model
{
	private
		$_status;
	
	public function getTokenStatus()
	{
		return $this->_status;
	}

	public function setTokenStatus( $status )
	{
		$this->_status = $status;
	}
}