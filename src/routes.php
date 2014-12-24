<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 09.12.14
 * Time: 1:15
 */

Route::group(['namespace' => 'Bitw\\Pages\\Controllers'], function($route){
	$route->get('{page}.html', ['as'=>'page.show', 'uses'=>'PageController@show']);

	// Manage pages
	$route->get(Config::get('app.route.prefix_control_panel') . '/page', ['before'=>'auth','as'=>'page.manage','uses'=>'PageController@index']);

	$route->group(['prefix'=>'page', 'before'=>'auth'], function($route){
			// Create form
		$route->get('create', ['as'=>'page.create', 'uses'=>'PageController@create']);
		// Stor as default
		//Route::post('create', ['as'=>'page.create', 'uses'=>'PageController@create']);
		// Store as REST
		$route->post('create', ['as'=>'page.store', 'uses'=>'PageController@store']);

		// Edit | Update form
		$route->get('{page}/edit', ['as'=>'page.edit', 'uses'=>'PageController@edit']);
		// Update as default
		//Route::post('{page}/edit', ['as'=>'page.update', 'uses'=>'PageController@edit']);
		// Update as REST
		$route->put('{page}/edit', ['as'=>'page.update', 'uses'=>'PageController@update']);

		// Delete page
		//Route::get('{page}/delete', ['before'=>'csrf', 'as'=>'page.destroy', 'uses'=>'PageController@destroy']);
		$route->delete('{page}/delete', ['before'=>'csrf', 'as'=>'page.destroy', 'uses'=>'PageController@destroy']);

		$route->patch('{page}', ['before'=>'csrf', 'as'=>'page.homepage', 'uses'=>'PageController@homepage']);
	});
});