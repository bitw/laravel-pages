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

	public $default_menu_name = 'defaultMenu';

	public function compileMenu($name = false)
	{
		if($name === false) $name = $this->default_menu_name;

		$menu = \Menu::make($name, function($menu){

			$menu->add(trans('pages::common.homepage'));

			foreach(Page::roots()->whereState('published')->whereShowInMenu(true)->get() as $page_root)
			{
				// Preparing depth 1
				$pages_depth_1 = $page_root->getImmediateDescendants();

				if($pages_depth_1->count())
				{
					$menu_level_1 = $menu->add($page_root->menu_title, ['route' => ['page.show', $page_root->slug], 'class'=>'dropdown']);

					foreach($pages_depth_1 as $page_depth_1)
					{
						// Exclude temporary, draft and disabled as well as disabled for menu
						if($page_depth_1->state == 'temp' || $page_depth_1->state == 'draft' || $page_depth_1->state == 'disabled' || !$page_depth_1->show_in_menu) continue;

						// Preparing depth 2
						$pages_depth_2 = $page_depth_1->getImmediateDescendants();

						if($pages_depth_2->count())
						{
							$menu_level_2 = $menu_level_1->add($page_depth_1->menu_title, ['route'=> ['page.show', $page_depth_1->slug], 'class'=>'dropdown']);

							foreach($pages_depth_2 as $page_depth_2)
							{
								// Exclude temporary, draft and disabled as well as disabled for menu
								if($page_depth_2->state == 'temp' || $page_depth_2->state == 'draft' || $page_depth_2->state == 'disabled' || !$page_depth_2->show_in_menu) continue;

								$pages_depth_3 = $page_depth_2->getImmediateDescendants();

								if($pages_depth_3->count())
								{
									$menu_level_3 = $menu_level_2->add($page_depth_2->menu_title, ['route'=>['page.show', $page_depth_2->slug], 'class'=>'dropdown']);

									foreach($pages_depth_3 as $page_depth_3)
									{
										// Exclude temporary, draft and disabled as well as disabled for menu
										if($page_depth_3->state == 'temp' || $page_depth_3->state == 'draft' || $page_depth_3->state == 'disabled' || !$page_depth_3->show_in_menu) continue;

										$menu->level_4 = $menu_level_3->add($page_depth_3->menu_title, route('page.show', $page_depth_3->slug));
									}
								}
								else $menu_level_3 = $menu_level_2->add($page_depth_2->menu_title, ['route'=>['page.show', $page_depth_2->slug]]);
							}
						}
						else $menu_level_2 = $menu_level_1->add($page_depth_1->menu_title, ['route'=> ['page.show', $page_depth_1->slug]]);
					}
				}
				else $menu_level_1 = $menu->add($page_root->menu_title, ['route' => ['page.show', $page_root->slug]]);
			}
		});

		\Cache::forever('PageMenu.'.$name, $menu);

		return $menu;
	}

	public function getMenu($name = false, $force = false)
	{
		if($name === false) $name = $this->default_menu_name;

		$menu = \Cache::get('PageMenu.'.$name);

		if(!$menu || $force)
		{
			$menu = $this->compileMenu($name);
		}

		return $menu;
	}
}