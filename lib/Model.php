<?php

/*
	base class for all Models
*/
class Model extends BaseComponent
{
	
	/*
		serialize properties to array from outlet configuration array
	*/
	public function serializeProperties()
	{
		$class = str_replace( '_OutletProxy', '', get_class( $this ) );
		$result = array();
		$conf = Core::getApp()->getComponent( 'DataBase' )->getConfig();
		foreach( $conf['classes'][$class]['props'] as $key => $value )
			$result[ lcfirst($key) ] = $this->$key;
		
		return $result;
	}
	
}