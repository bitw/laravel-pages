<?php namespace Bitw\Pages\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Bitw\Pages\Models\Page;

class PageController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
		//dd(View::getFinder()->getHints());
		$data = Page::where('state', '!=', 'temp')->whereAuthorId(Auth::id())->paginate(10);

		return View::make('pages::list', compact('data'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$page = Page::Temporary();

		return Redirect::route('page.edit', $page->id);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	/*
	public function store()
	{
		if($this->validate()->failed()) return Redirect::action('PageController@create')->withErrors($this->validate()->errors())->withInput();

		// Get temporary page
		$page = Page::whereState('draft')->whereAuthorId(Auth::id())->first();

		$page->fill(Input::all())->save();

		return Redirect::action('PageController@index');
	}
	*/

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($slug)
	{
		$page = Page::findBySlug($slug);

		if(!$page || ($page->state == 'disabled' && $page->author_id != Auth::id())) return \App::abort(404);

		return View::make('pages::show', compact('page'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		try
		{
			$page = Page::findOrFail($id);
		}
		catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e)
		{
			return App::abort(404);
		}
		$page->_method = 'put';
		$page->_route = ['page.update', $page->id];

		return View::make('pages::editor', compact('page'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if($this->validate()->fails())
		{
			return Redirect::route('page.edit', $id)->withErrors($this->validate()->errors())->withInput();
		}

		$page = Page::whereAuthorId(Auth::id())->find($id);

		$post = Input::all();

		$post['show_in_menu'] = Input::get('show_in_menu')
			? true
			: false;

		$page->fill($post)->save();

		return Redirect::route('page.manage');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
			$page = Page::whereAuthorId(Auth::id())->findOrFail($id);
		}
		catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e)
		{
			return App::abort(401);
		}

		$page->delete();

		return Response::json([
			'type'=>'success',
			'message'=>trans('pages::common.messages.page_deleted'),
			'id' => $id,
		]);
	}

	/**
	 * Set page as homepage
	 *
	 * @param int $id
	 * @return Response
	 */
	public function homepage($id)
	{
		try
		{
			$page = Page::whereAuthorId(Auth::id())->findOrFail($id);
		}
		catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e)
		{
			return App::abort(401);
		}

		\DB::table('pages')->update(['is_homepage'=>false]);

		$page->is_homepage = true;

		$page->save();

		//return Response::json();
	}

	private function validate()
	{
		return $validate = Validator::make(
			Input::all(),
			[
				'content' => 'required'
			],
			[
				'content.required' => trans('pages::common.messages.content_required'),
			]
		);
	}

}
