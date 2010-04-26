<?php

/*
	Tags controller
*/
class Tags extends AppController
{

	/*
		set no decorate template
	*/
	public function __construct()
	{
		$this->getTemplate()->setTemplateFile( false );
	}

	/*
		add new tag
	*/
	public function _add()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
		
		$r = Core::getApp()->getComponent('Request');
		
		// populate variables from request
		$tagText = $r->get('add-tag', '');
		$listId = $r->get('list-id', -1);
		$list = $this->getOutlet()->load( 'Checklist', $listId );
		
		// tag can be assigned to Checklist if tag text is defined, list exists and list is owned by logged in user
		$canAdd = $list && ($tagText !== '') && ($list->UserId == $this->getUserModel()->Id);
		if( $canAdd )
		{
			// try to load Tag
			$tag = $this->getOutlet()->from('Tag')
				->where('{Tag.Tag} = ?', array( $tagText ) )
				->limit(1)
				->findOne();
			// create Tag if it doesn't exist
			if( ! $tag )
			{
				$tag = new Tag();
				$tag->Tag = $tagText;
				$this->getOutlet()->save( $tag );
			}
			
			// create relation between list and tag
			$list->addTag( $tag );
			try 
			{
				$this->getOutlet()->save( $list );
				// pass relationship to view
				$this->tag = $tag;
				$this->list = $list;
				return Controller::SUCCESS;
			} 
			catch (PDOException $e) 
			{
				// already defined relationship
				return 'Duplicate';
			}
		}
		return Controller::ERROR;
	}
	
	/*
		delete relationship between Checklist and Tag
	*/
	public function _delete()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
		
		$r = Core::getApp()->getComponent('Request');
		// populate list, tag ids
		$listId = $r->get('list-id', -1);
		$tagId = $r->get('tag-id', -1);
		
		// instantiate relevant model objects
		$list = $this->getOutlet()->load( 'Checklist', $listId );
		$tag = $this->getOutlet()->load( 'Tag', $tagId );
		
		// can delete relationship only if Tag and List exist
		$exist = $list && $tag;
		if( $exist && ($list->UserId == $this->getUserModel()->Id) )
		{
			try
			{
				$this->getOutlet()->delete( 'ListTag', array( $list->Id, $tag->Id ) );
			}
			catch (PDOException $e)
			{
				// cannot delete, as such ListTag relationship does not exist
				return Controller::ERROR;
			}
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}

}