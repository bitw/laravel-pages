<?php namespace Bitw\Pages;

use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 23.12.14
 * Time: 12:50
 */

class PagesFacade extends Facade
{

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'pages';
	}

}