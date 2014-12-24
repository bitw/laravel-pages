<?php namespace Bitw\Pages;

use Illuminate\Support\ServiceProvider;
use Bitw\Pages\Models\Page;

class PagesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('bitw/pages');

		include __DIR__ . '/../../routes.php';

		$page = Page::whereState('published')->whereIsHomepage(true)->first();

		\View::composer('site.home', function($view) use($page){
			$view->with([
				'title'         => $page->title,
				'description'   => $page->description,
				'keywords'      => $page->keywords,
				'content'       => $page->content,
			]);

		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		$this->app['pages'] = $this->app->share(function($app)
		{
			return new \Bitw\Pages\Pages;
		});

		// Shortcut so developers don't need to add an Alias in app/config/app.php
		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Pages', 'Bitw\Pages\PagesFacade');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
