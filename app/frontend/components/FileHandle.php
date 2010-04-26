<?php

class FileHandle extends BaseComponent
{
	private
		$_fp;
	
	public function __construct( $filename, $mode )
	{
		$this->_fp = fopen( $filename, $mode );
		if( !$this->getFilePointer() )
			throw new EFileNotFound();
	}
	
	public function __destruct()
	{
		$this->close();
	}
	
	public function close()
	{
		if( $this->isOpen() )
			fclose( $this->getFilePointer() );
		$this->_fp = false;
	}
	
	public function isEof()
	{
		return feof( $this->getFilePointer() );
	}
	
	public function isOpen()
	{
		return is_resource( $this->getFilePointer() );
	}
	
	public function getFilePointer()
	{
		return $this->_fp;
	}
	
	public function read( $bytes )
	{
		return ( $this->isOpen() ) ? fread( $this->getFilePointer(), $bytes ) : false;
	}
	
	public function readLine()
	{
		return ( $this->isOpen() ) ? fgets( $this->getFilePointer() ) : false;
	}
	
	public function write( $chars )
	{
		if( $this->isOpen() )
			fwrite( $this->getFilePointer(), $chars );
	}
	
	public function writeLine( $line )
	{
		if( $this->isOpen() )
			fwrite( $this->getFilePointer(), $line."\n" );
	}
}

class EFileNotFound extends Exception {}