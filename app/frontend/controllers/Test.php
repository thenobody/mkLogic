<?php

class Test extends AppController
{

	public function _index()
	{
		// 1. add new user (invalid username) - should fail
		$user = new User();
		$user->Login = "test-invalid";
		$user->Password = "test-invalid";
		if( $user->validate() )
		{
			$user->hashPassword();
			$user->setCreatedAtNow();
			$user->Active = 1;
			$user->generateToken();
			$this->getOutlet()->save( $user );
		}
		$models = $this->getOutlet()->from('User')
			->where('{User.Login} = ?', array(
				$user->Login,
			))->limit(1)->find();
		
		$this->test1 = ( count($models) == 0 );
		
		// 2. add new user (valid username) - should succeed
		$user = new User();
		$user->Login = "test@test.sk";
		$user->Password = "test-valid";
		if( $user->validate() )
		{
			$user->hashPassword();
			$models = $this->getOutlet()->from('User')
				->where('{User.Login} = ?', array(
					$user->Login,
				))->limit(1)->find();
			if( count( $models ) == 0 )
			{
				$user->setCreatedAtNow();
				$user->Active = 0;
				$user->generateToken();
				$this->getOutlet()->save( $user );
			}
		}
		$models = $this->getOutlet()->from('User')
			->where('{User.Login} = ?', array(
				$user->Login,
			))->limit(1)->find();

		$this->test2 = ( count($models) == 1 );
		
		// 3. log in with created username
		$user = new User();
		$user->Login = 'test@test.sk';
		$user->Password = 'test-valid';
		if( $user->validate() )
		{
			
			$user->hashPassword();
			$model = $this->getOutlet()->from('User')
				->where('{User.Login} = ?', array(
					$user->Login,
				))->limit(1)->find();
			if( count( $model ) == 1 )
			{
				$this->getUser()->setAuthenticated( true );
				$this->getUser()->setId( $model[0]->Id );
			}
		}
		
		$this->test3 = $this->getUser()->isAuthenticated();
		
		// 4. create new list (named test1)
		$list = new Checklist();
		$list->Title = 'test1';
		$list->Subtitle = '';
		$list->Public = true;
		$list->UserId = $this->getUser()->getId();

		if( $list->validate() ) 
		{
			$model = $this->getOutlet()->from('Checklist')
				->where('{Checklist.Title} = ? AND {Checklist.Subtitle} = ? AND {Checklist.Public} = ? AND {Checklist.UserId} = ?', array(
					$list->Title,
					$list->Subtitle,
					$list->Public,
					$list->UserId,
				))->limit(1)->find();
			if( count($model) == 0 )
			{
				$o = $this->getOutlet();
				$o->save( $list );
			}
		}
		
		$model = $this->getOutlet()->from('Checklist')
			->where('{Checklist.Title} = ? AND {Checklist.Subtitle} = ? AND {Checklist.Public} = ? AND {Checklist.UserId} = ?', array(
				$list->Title,
				$list->Subtitle,
				$list->Public,
				$list->UserId,
			))->limit(1)->find();
		
		$list = ( count($model) == 1 ) ? $model[0] : null;
		$this->test4 = ( count($model) == 1 );
		
		// 5. create new item in list test1 (value testitem1; color #000000)
		if( $list != null )
		{
			$item = new Item();
			$item->ListId = $list->Id;
			$item->Value = 'testitem1';
			$item->Color = '000000';
			
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
				
			$model = $this->getOutlet()->from('Item')
			->where('{Item.ListId} = ? AND {Item.Value} = ? AND {Item.Color} = ?', array(
				$item->ListId,
				$item->Value,
				$item->Color,
			))->limit(1)->find();
			
			if( count($model) == 0 )
			{
				$this->getOutlet()->save( $item );
			}
			
			$model = $this->getOutlet()->from('Item')
			->where('{Item.ListId} = ? AND {Item.Value} = ? AND {Item.Color} = ?', array(
				$item->ListId,
				$item->Value,
				$item->Color,
			))->limit(1)->find();
			
			$this->test5 = ( count($model) == 1 );
		}
		else
			$this->test5 = false;
			
		// 6. edit item in list test1 (value testitem1 =&gt; testitem2; color #000000 = &gt; #ffffff)
		if( $list != null )
		{
			$model = $this->getOutlet()->from('Item')
				->where('{Item.ListId} = ? AND {Item.Value} = ? AND {Item.Color} = ?', array(
					$list->Id,
					'testitem1',
					'000000',
				))->limit(1)->find();
				
			if( count($model) == 1 )
			{
				$item = $model[0];
				$item->Value = 'testitem2';
				$item->Color = 'ffffff';
				$this->getOutlet()->save( $item );
				
				$model = $this->getOutlet()->from('Item')
				->where('{Item.ListId} = ? AND {Item.Value} = ? AND {Item.Color} = ?', array(
					$list->Id,
					'testitem2',
					'ffffff',
				))->limit(1)->find();
				$this->test6 = ( count($model) == 1 );
			}
			else
				$this->test6 = false;
		}
		else
			$this->test6 = false;
			
		// 7. remove item in list test1 (value testitem2; color #ffffff)
		if( $list != null )
		{
			$model = $this->getOutlet()->from('Item')
				->where('{Item.ListId} = ? AND {Item.Value} = ? AND {Item.Color} = ?', array(
					$list->Id,
					'testitem2',
					'ffffff',
				))->limit(1)->find();

			if( count($model) == 1 )
			{
				$item = $model[0];
				$this->getOutlet()->delete( 'Item', $item->Id );
				
				$model = $this->getOutlet()->from('Item')
				->where('{Item.ListId} = ? AND {Item.Value} = ? AND {Item.Color} = ?', array(
					$list->Id,
					'testitem2',
					'ffffff',
				))->limit(1)->find();
				
				$this->test7 = ( count($model) == 0 );
			}
			else
				$this->test7 = false;
		}
		else
			$this->test7 = false;
	
	
		// 8. add tag 'testtag1' for list 'test1'
		$tag = null;
		$tag = $this->getOutlet()->from('Tag')
			->where('{Tag.Tag} = ?', array('testtag1'))
			->limit(1)
			->findOne();
		if( ! $tag )
		{
			$tag = new Tag();
			$tag->Tag = 'testtag1';
			$this->getOutlet()->save( $tag );
		}
	
		$list = $this->getOutlet()->from('Checklist')
			->where('{Checklist.Title} = ?', array('test1'))
			->limit(1)
			->findOne();
		$this->test8 = $tag && $list;
		if( $this->test8 )
		{
			$list->addTag( $tag );
			$this->getOutlet()->save( $list );
			$listtag = $this->getOutlet()->load('ListTag', array($list->Id, $tag->Id));
			$this->test8 &= $listtag->ListId && $listtag->TagId;
		}
	
		// 9. remove tag 'testtag1' for list 'test1'
		$list = $this->getOutlet()->from('Checklist')
			->where('{Checklist.Title} = ?', array('test1') )
			->limit(1)
			->findOne();
		$this->test9 = is_object( $list );
		$tag = $this->getOutlet()->from('Tag')
			->where('{Tag.Tag} = ?', array('testtag1') )
			->limit(1)
			->findOne();
	
		$this->test9 &= is_object( $tag );
	
		if( $this->test9 )
		{
			$listtag = $this->getOutlet()->load('ListTag', array($list->Id, $tag->Id) );
			$this->test9 &= $listtag->ListId && $listtag->TagId;
			$this->getOutlet()->delete( 'ListTag', array( $list->Id, $tag->Id ) );
			$this->test9 &= ! $this->getOutlet()->load('ListTag', array($list->Id, $tag->Id) );
		} 
	
		// 10. remove list (named test1)
		$listTitle = 'test1';
		$list = $this->getOutlet()->from('Checklist')
			->where('{Checklist.Title} = ?', array($listTitle))
			->limit(1)
			->findOne();
		$this->test10 = $list;
		if( $this->test10 )
		{
			$this->getOutlet()->delete('Checklist', $list->Id);
			$list = $this->getOutlet()->from('Checklist')
				->where('{Checklist.Title} = ?', array($listTitle))
				->limit(1)
				->findOne();
			if( $list )
				$this->test10 = false;
		}
	
		// 11. log out with created username
		$this->getUser()->setAuthenticated( false );
		$this->test11 = ! $this->getUser()->isAuthenticated();
	
		// 12. remove created user (nonexistent username) - should fail
		$user = null;
		$userName = 'nonexistent@does-not-exist.com';
		$user = $this->getOutlet()->from('User')
			->where('{User.Login} = ?', array($userName))
			->findOne();
		$this->test12 = ! $user;
	
		// 13. remove created user (existent username) - should succeed
		$user = null;
		$userName = 'test@test.sk';
		$user = $this->getOutlet()->from('User')
			->where('{User.Login} = ?', array($userName))
			->findOne();
		if( ! $user )
		{
		// doesn't exist so create
			$user = new User();
			$user->Login = $userName;
			$user->Password = 'some pass';
			$user->hashPassword();
			$user->setCreatedAtNow();
			$user->Active = false;
			$user->generateToken();
			$this->getOutlet()->save( $user );
		}
		$this->getOutlet()->delete( 'User', $user->Id );
		$this->test13 = ! $user = $this->getOutlet()->from('User')
			->where('{User.Login} = ?', array($userName))->findOne();
		
		$this->test_all =	$this->test1 && $this->test2 && $this->test3 && $this->test4 && $this->test5 &&
							$this->test6 && $this->test7 && $this->test8 && $this->test9 && $this->test10 &&
							$this->test11 && $this->test12 && $this->test13;
	}
}