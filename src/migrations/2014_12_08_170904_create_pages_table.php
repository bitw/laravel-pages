<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function (Blueprint $table) {
			// These columns are needed for Baum's Nested Set implementation to work.
			// Column names may be changed, but they *must* all exist and be modified
			// in the model.
			// Take a look at the model scaffold comments for details.
			// We add indexes on parent_id, lft, rgt columns by default.
			$table->increments('id');
			$table->integer('parent_id')->nullable()->index();
			$table->integer('lft')->nullable()->index();
			$table->integer('rgt')->nullable()->index();
			$table->integer('depth')->nullable();

			$table->string('slug');
			$table->text('content');
			$table->string('image');

			$table->string('lang', 2);

			$table->string('title');
			$table->text('description');
			$table->text('keywords');

			$table->enum('state',['temp', 'draft', 'disabled', 'published'])->default('temp');

			$table->integer('author_id')->unsigned();

			$table->timestamps();
			$table->softDeletes();

			$table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages');
	}

}
