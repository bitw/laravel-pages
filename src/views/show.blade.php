@extends('site.layouts.default')

@section('head_title', $page->title) @stop
@section('head_meta_description', $page->description) @stop
@section('head_meta_keywords', $page->keywords) @stop

@section('content')
    <ul>{{ Pages::Menu() }}</ul>
    {{ $page->content }}
@stop

