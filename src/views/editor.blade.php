@extends('dashboard.layouts.default')

@section('content')
    {{ Form::open(['route'=>$page->_route, 'files'=>true, 'method'=>$page->_method]) }}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>@if(Route::current()->getName()=='page.create') {{ trans('pages::common.creating_page') }} @else {{ trans('pages::common.edit_page') }} @endif</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    {{ Form::textarea('content', Input::get('content', $page->content)) }}
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    {{ Form::submit(trans('common.save'), ['class'=>'btn btn-primary']) }}
                    {{ link_to_route('page.manage', trans('common.cancel'), null, ['class'=>'btn btn-default']) }}
                </div>
                <div class="form-group">
                    <label for="state">{{ trans('pages::common.state') }}</label>
                    {{ Form::select(
                        'state',
                        [
                            'draft'=>trans('pages::common.states.draft'),
                            'disabled'=>trans('pages::common.states.disabled'),
                            'published'=>trans('pages::common.states.published'),
                        ],
                        Input::get('state', $page->state),
                        ['id'=>'state','class'=>'form-control']
                    ) }}
                </div>
                <div class="form-group">
                    <label for="title">{{ trans('pages::common.title') }}</label>
                    {{ Form::text('title', Input::get('title', $page->title), ['id'=>'title','class'=>'form-control']) }}
                </div>
                <div class="form-group">
                    <label for="description">{{ trans('pages::common.description') }}</label>
                    {{ Form::textarea('description', Input::get('description', $page->description), ['id'=>'description','class'=>'form-control', 'rows'=>4]) }}
                </div>
                <div class="form-group">
                    <label for="keywords">{{ trans('pages::common.keywords') }}</label>
                    {{ Form::textarea('keywords', Input::get('keywords', $page->keywords), ['id'=>'keywords','class'=>'form-control', 'rows'=>2]) }}
                </div>
                <div class="checkbox">
                    <label for="show_in_menu">
                        {{ Form::checkbox('show_in_menu', 1, $page->show_in_menu, ['id'=>'show_in_menu']) }} {{ trans('pages::common.show_in_menu') }}
                    </label>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@endsection

@section('foot_append') @parent
<script src="/bower_components/ckeditor/ckeditor.js"></script>
<script src="/bower_components/ckeditor/adapters/jquery.js"></script>
<script>
    $(function(){
        $('textarea[name=content]').ckeditor({
            height: 350
        });
    });
</script>
@stop