@extends('dashboard.layouts.default')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3>{{ trans('pages::common.pages') }} <a href="{{ route('page.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;{{ trans('common.create') }}</a></h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" colspan="3">{{ trans('pages::common.state') }}</th>
                        <th>{{ trans('pages::common.title') }}</th>
                        <th>{{ trans('pages::common.text_in_menu') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $page)
                        <tr id="page-{{ $page->id }}">
                            <td style="width:50px;" class="text-center">{{ $page->id }}</td>
                            <td style="width:14px;" class="text-center">
                                @if($page->state == 'draft')
                                    <i class="fa fa-square text-danger" title="{{ trans('pages::common.states.draft') }}"></i>
                                @elseif($page->state == 'disabled')
                                    <i class="fa fa-square text-warning" title="{{ trans('pages::common.states.disabled') }}"></i>
                                @else
                                    <i class="fa fa-square text-success" title="{{ trans('pages::common.states.published') }}"></i>
                                @endif
                            </td>
                            <td style="width:14px;" class="text-center">@if($page->show_in_menu)<i class="fa fa-bars" title="{{ trans('pages::common.show_in_menu') }}"></i>@endif</td>
                            <td style="width:14px;" class="text-center">
                                {{ Form::radio('homepage', $page->id, $page->is_homepage) }}
                            </td>
                            <td>
                                {{ $page->depth ? '<span style="color:#dedede;">'.str_repeat('&middot;', $page->depth*4)."</span> " : '' }}{{ Str::limit($page->title, 60) }}
                            </td>
                            <td>{{ Str::limit($page->menu_title, 60) }}</td>
                            <td style="text-align: right">
                                <a href="{{ route('page.show', ['page'=>$page->slug]) }}" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('page.edit', ['page'=>$page->id]) }}" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
                                <button onclick="deletePage({{ $page->id }});" class="btn btn-xs btn-default"><i class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- $data->links() --}}
@stop

@section('foot_append') @parent
<script>
    var urlHomepage = "{{ route('page.homepage', ['page'=>":page_id", '_token'=>csrf_token()]) }}";
    var urlDelete = "{{ route('page.destroy', ['page'=>":page_id", '_token'=>csrf_token()]) }}";
    var currentHomepage = $('input[name=homepage]:checked').val();

    $('input[name=homepage]').click(function(){
        if($(this).val()==currentHomepage) return;
        page_id = $(this).val();
        $.post(urlHomepage.replace(/:page_id/, page_id), {_method:'patch'})
            .done(function(r){
                currentHomepage = r.id;
            });
    });

    function deletePage(page_id){
        $.post(urlDelete.replace(/:page_id/, page_id), {_method:'delete'})
            .done(function(r){
                $('tr#page-'+r.id).remove();
            });
    }

</script>
@stop