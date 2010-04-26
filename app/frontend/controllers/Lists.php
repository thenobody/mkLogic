<?php

/*
	Lists controller for Checklist manipulation
*/
class Lists extends AppController
{

	/*
		set no template decorator, as all actoins are called via Ajax
	*/
	public function __construct()
	{
		$this->getTemplate()->setTemplateFile( false );
	}

	/*
		insert new Checklist into DB
	*/
	public function _add()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
			
		$r = $this->getRequest();
		if( ! $r->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );

		
		if( $r->getMethod() !== Request::POST )
			$this->forward( 'HttpStatus', '403' );
		
		// instantiate new Checklist model
		$list = new Checklist();
		$list->Title = $r->get( 'list-title', '' );
		$list->Subtitle = $r->get( 'list-subtitle', '' );
		$list->Public = $r->get( 'list-public', '' ) !== '';
		$list->UserId = $this->getUserModel()->Id;
		
		// if Checlist is valid insert into database
		if( $list->validate() ) 
		{
			$o = $this->getOutlet();
			$o->save( $list );
			$this->list = $list;
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}
	
	/*
		edit Checklist properties
	*/
	public function _edit()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
			
		$r = $this->getRequest();
		if( ! $r->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );
			
		if( $r->getMethod() !== Request::POST )
			$this->forward( 'HttpStatus', '403' );
		
		// populate required variables
		$id = $r->get( 'list-id', -1 );
		$list = $this->getOutlet()->load( 'Checklist', $id );
		$title = $r->get( 'title', '' );
		$subtitle = $r->get( 'subtitle', '' );
		
		// can edit if a property was changed and list is owned by logged in user
		if( $list && ($title !== '') || ($subtitle !== '') && ($list->UserId == $this->getUserModel()->Id) )
		{
			// assign changed props
			if( $title !== '' )
				$list->Title = $title;
			if( $subtitle !== '' )
				$list->Subtitle = $subtitle;
				
			// save changed list
			$this->getOutlet()->save( $list );
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}
	
	/*
		remove Checklist from database
	*/
	public function _delete()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;

		$r = $this->getRequest();
		if( ! $r->isAjax() )
			$this->forward( 'HttpStatus', 'noAjax' );

		if( $r->getMethod() !== Request::GET )
			$this->forward( 'HttpStatus', '403' );
		
		// resolve list to delete
		$listId = $r->get( 'list-id', -1 );
		$list = $this->getOutlet()->load( 'Checklist', $listId );
		
		// Checklist can be deleted if it exists and is owned by logged in user
		$canDelete = $list && ( $list->UserId == $this->getUserModel()->Id );
		if( $canDelete )
		{
			$this->getOutlet()->delete( 'Checklist', $listId );
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}

}