@extends("layout")
@section("content")

@if (Session::has('message'))
	<div class="alert alert-info">{{ trans(Session::get('message')) }}</div>
@endif

	<div>
		<ol class="breadcrumb">
		  <li><a href="{{{route('user.home')}}}">{{ trans('messages.home') }}</a></li>
		  <li><a href="{{ route('testcategory.index') }}">{{ Lang::choice('messages.test-category',1) }}</a></li>
		  <li class="active">{{ trans('messages.test-category-details') }}</li>
		</ol>
	</div>
	<div class="panel panel-primary ">
		<div class="panel-heading ">
			<span class="glyphicon glyphicon-adjust"></span>
			{{ trans('messages.test-category-details') }}
			<div class="panel-btn">
				<a class="btn btn-sm btn-info" href="{{ route('testcategory.edit', array($testcategory->id)) }}">
					<span class="glyphicon glyphicon-edit"></span>
					{{ trans('messages.edit') }}
				</a>
			</div>
		</div>
		<div class="panel-body">
			<div class="display-details">
				<h3 class="view"><strong>{{ Lang::choice('messages.name',1) }}:</strong>{{ $testcategory->name }} </h3>
				<p class="view-striped"><strong>{{ trans('messages.description') }}:</strong>
					{{ $testcategory->description }}</p>

			</div>
		</div>
	</div>
@stop
