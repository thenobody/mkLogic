<?php

/*
	Internal uploaded file wrapper
*/

class UploadedFile
{
	private
		$_fileName,
		$_type,
		$_tmpName,
		$_error,
		$_size;
	
	public function populateFromArray( $properties )
	{
		$this->setFileName( $properties[ 'name' ] );
		$this->setType( $properties[ 'type' ] );
		$this->setTemporaryFileName( $properties[ 'tmp_name' ] );
		$this->setErrorCode( $properties[ 'error' ] );
		$this->setSize( $properties[ 'size' ] );
	}
	
	public function getFileName()
	{
		return $this->_fileName;
	}
	
	public function setFileName( $fileName )
	{
		$this->_fileName = $fileName;
	}
	
	public function getType()
	{
		return $this->_type;
	}

	public function setType( $type )
	{
		$this->_type = $type;
	}
	
	public function getTemporaryFileName()
	{
		return $this->_tmpName;
	}

	public function setTemporaryFileName( $tmpName )
	{
		$this->_tmpName = $tmpName;
	}
	
	public function getErrorCode()
	{
		return $this->_error;
	}

	public function setErrorCode( $errorCode )
	{
		$this->_error = $errorCode;
	}
	
	public function getSize()
	{
		return $this->_size;
	}

	public function setSize( $size )
	{
		$this->_size = $size;
	}

}

/*
	file-not-found exception
*/
class EFileNotFound extends Exception { }

/*
	moving-file-error exception
*/
class EUnableToMoveFile extends Exception { }