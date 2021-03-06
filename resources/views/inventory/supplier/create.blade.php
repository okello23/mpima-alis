@extends("layout")
@section("content")
<div>
	<ol class="breadcrumb">
	  <li><a href="{{{route('user.home')}}}">{{trans('messages.home')}}</a></li>
       <li><a href="{{{route('supplier.index')}}}">{{ Lang::choice('messages.supplier', 2) }}</a></li>
	 	  <li class="active">{{ trans('messages.new').' '.Lang::choice('messages.supplier', 1) }}</li>
	</ol>
</div>
@if (Session::has('message'))
	<div class="alert alert-info">{{ trans(Session::get('message')) }}</div>
@endif
@if($errors->all())
                <div class="alert alert-danger">
                    {{ HTML::ul($errors->all()) }}
                </div>
@endif
<div class="panel panel-primary">
	<div class="panel-heading ">
		<span class="glyphicon glyphicon-user"></span>
		{{ Lang::choice('messages.supplier', 2) }}
	</div>
	<div class="panel-body">
		   {{ Form::open(array('route' => 'supplier.store', 'id' => 'form-store_suppliers')) }}

            <div class="form-group">
                {{ Form::label('name', Lang::choice('messages.name', 1)) }}
                {{ Form::text('name', old('name'), array('class' => 'form-control', 'rows' => '2')) }}
            </div>
            <div class="form-group">
                {{ Form::label('phone', trans('messages.phone')) }}
                {{ Form::text('phone', old('phone'),array('class' => 'form-control', 'rows' => '2')) }}
            </div>
            <div class="form-group">
                {{ Form::label('email', trans('messages.email')) }}
                {{ Form::text('email', old('email'),array('class' => 'form-control', 'rows' => '2')) }}
            </div>
             <div class="form-group">
                {{ Form::label('address', trans('messages.address')) }}
                {{ Form::textarea('address', old('address'), array('class' => 'form-control', 'rows' => '2')) }}
            </div>

            <div class="form-group actions-row">
                    {{ Form::button("<span class='glyphicon glyphicon-save'></span> ".trans('messages.save'),
                        array('class' => 'btn btn-primary', 'onclick' => 'submit()')) }}
            </div>
        {{ Form::close() }}
	</div>

</div>
@stop
