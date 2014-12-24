<?php namespace Bitw\Pages;

use Bitw\Pages\Models\Page;

/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 23.12.14
 * Time: 14:43
 */

class Pages
{

	public function Menu()
	{
		/*
		exit;
		*/

		$menu = \Menu::make('pagesMenu', function($menu){

			$menu->add(trans('pages::common.home'));

			$pages = Page::whereState('published')->whereShowInMenu(true)->get()->toHierarchy();

			foreach ($pages as $page)
			{
				$menu->add($page->title, route('page.show',$page->slug));
			}

		});

		echo $menu->render();
/*
		foreach($pages as $page)
		{
			var_dump($page->title);
		}
*/
	}

	private function renderNode($node)
	{

	}

}