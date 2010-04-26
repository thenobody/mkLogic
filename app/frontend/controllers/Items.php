<?php

/*
	Checklist Items controller
*/
class Items extends AppController
{

	/*
		set template with no decorator, since all actions are render for Ajax calls (JSON objects)
	*/
	public function __construct()
	{
		$this->getTemplate()->setTemplateFile( false );
	}

	/*
		add new list Item
	*/
	public function _add()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
			
		$r = Core::getApp()->getComponent( 'Request' );
		// populate required variables
		$value = $r->get( 'item-value', '' );
		$listId = $r->get( 'list-id', -1 );
		$list = $this->getOutlet()->load( 'Checklist', $listId );
		// test for list existence and user must own actual list
		if( $list && ($list->UserId == $this->getUserModel()->Id) )
		{
			$defaultColor = '555555';
			
			// create and populate new Item object
			$item = new Item();
			$item->ListId = $listId;
			$item->Value = $value;
			$item->Color = $defaultColor;
			
			// find last item in Position order
			$lastItem = $this->getOutlet()->from( 'Item' )
				->where('{Item.ListId} = ?', array($item->ListId))
				->orderby('{Item.Position} DESC')
				->limit(1)
				->findOne();
			
			if( $lastItem )
			{
				// when last item exists, new item's position is righ after the last item
				$item->Position = $lastItem->Position + 1;
			}
			else
				$item->Position = 0;

			// save Item and send it to view
			$this->getOutlet()->save( $item );
			$this->item = $item;
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}

	/*
		delete Item from database
	*/
	public function _delete()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
			
		// populate required variables
		$id = Core::getApp()->getComponent( 'Request' )->get( 'item-id', -1 );
		$outlet = $this->getOutlet();
		$item = $outlet->load( 'Item', $id );
		// can delete Item, only if it exists and it is owned by logged in user
		$canDelete = $item && ($item->getChecklist()->UserId == $this->getUserModel()->Id);
		if( $canDelete )
		{
			// decrase position for all items after deleted
			$itemsAfter = $outlet->from( 'Item' )
				->where('{Item.Position} > ? AND {Item.ListId} = ?', array($item->Position, $item->ListId))
				->find();
			if( count( $itemsAfter ) )
			{
				foreach( $itemsAfter as $it )
				{
					$it->Position = $it->Position - 1;
					$outlet->save( $it );
				}
			}
			// finally delete Item from database
			$outlet->delete( 'Item', $item->Id );
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}
	
	/*
		inform database, that Item has moved
		reorder relevant Items to correct Positions
	*/
	public function _moved()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
			
		$r = $this->getRequest();
		$outlet = $this->getOutlet();

		// populate required variables from request
		$id = $r->get( 'id', -1 );
		$newPos = $r->get( 'pos', -1 );
		$item = $outlet->load( 'Item', $id );

		// can move item only if it exists, new position is valid and Item belongs to logged in user
		if( $item && ($newPos > -1) && ($item->getChecklist()->UserId == $this->getUserModel()->Id))
		{
			// if no position change return
			if( $item->Position == $newPos )
				return Controller::SUCCESS;
			
			// select relevant Items
			$items = ($item->Position > $newPos) ? 
				$outlet->from( 'Item' )
					->where('{Item.ListId} = ? AND {Item.Position} < ? AND {Item.Position} >= ?', array(
						$item->ListId, $item->Position, $newPos,
					))->find()
				: $outlet->from( 'Item' )
					->where('{Item.ListId} = ? AND {Item.Position} <= ? AND {Item.Position} > ?', array(
						$item->ListId, $newPos, $item->Position,
					))->find();

			// find delta form moving relevant items
			$inc = ($item->Position > $newPos) ? +1 : -1;
			foreach( $items as $it )
			{
				$it->Position = $it->Position + $inc;
				$outlet->save( $it );
			}
			
			// finally move main item
			$item->Position = $newPos;
			$outlet->save( $item );
			
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}
	
	/*
		Item edit handler
	*/
	public function _edit()
	{
		if( ! $this->getUser()->isAuthenticated() )
			return Controller::ERROR;
			
		// populate required variables
		$r = $this->getRequest();
		$outlet = $this->getOutlet();
		$id = $r->get( 'item-id', -1 );
		$value = $r->get( 'item-value', '' );
		$color = $r->get( 'item-color', '' );
		
		$item = $outlet->load( 'Item', $id );
		
		// can edit item if it exists and is owned by logged in user
		if( $item && ($item->getChecklist()->UserId == $this->getUserModel()->Id) )
		{
			// assign new Item.Value
			$item->Value = $value;
			// check for color validity
			if (!$item->isValidColor( $color ))
				return Controller::ERROR;
			// insert new color, without Hash symbol
			$item->Color = str_replace('#', '', $color);
			$outlet->save( $item );
			$this->item = $item;
			return Controller::SUCCESS;
		}
		return Controller::ERROR;
	}

}