@extends("layout")
@section("content")
	<div>
		<ol class="breadcrumb">
		  <li><a href="{{ route('user.home') }}">{{ trans('messages.home') }}</a></li>
		  <li class="active">{{ Lang::choice('messages.report', 2) }}</li>
		</ol>
	</div>
{{--	{{ Form::open(array('route' => array('reports.patient.index'), 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) }}--}}
	{{ Form::open(array('route' => array('reports.patient.merged'), 'class'=>'form-inline', 'role'=>'form', 'method'=>'POST')) }}
		<div class="form-group">

		    {{ Form::label('search', "search", array('class' => 'sr-only')) }}
            {{ Form::text('search', \Illuminate\Support\Facades\Request::get('search'), array('class' => 'form-control test-search')) }}
		</div>
		<div class="form-group">
			{{ Form::button("<span class='glyphicon glyphicon-search'></span> ".trans('messages.search'),
		        array('class' => 'btn btn-primary', 'id' => 'filter', 'type' => 'submit')) }}
		</div>
	{{ Form::close() }}
	<br>
<div class="panel panel-primary">
	<div class="panel-heading ">
		<span class="glyphicon glyphicon-user"></span>
		{{ trans('messages.patient-report') }}
	</div>
	<div class="panel-body">

	    @if(Session::has('message'))
			<div class="alert alert-info">{{ trans(Session::get('message')) }}</div>
		@endif
	    <table class="table table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th>{{ trans('messages.patient-id') }}</th>
					<th>{{trans('messages.patient-number')}}</th>
					@can('view_names')
						<th>{{trans('messages.full-name')}}</th>
					@endcan
					<th>{{trans('messages.gender')}}</th>
					<th>{{trans('messages.age')}}</th>
					<th>{{trans('messages.actions')}}</th>
				</tr>
			</thead>
			<tbody>
			@forelse($patients as $key => $patient)
				<tr>
					<td>{{ $patient->patient_number }}</td>
					<td>{{ $patient->external_patient_number }}</td>
					@can('view_names')
						<td>{{ $patient->name }}</td>
					@endcan
{{--					<td>{{ $patient->getGender() }}</td>--}}
					<td>{{ ($patient->gender==0?trans('messages.male'):trans('messages.female')) }}</td>
					<td>{{ $patient_helper->newAge($patient->dob) }}</td>
					<td>
					<!-- show the patient report(uses the show method found at GET /patient/{id} -->
						<a class="btn btn-sm btn-info" href="{{ URL::to('patientvisits/' . $patient->id) }}" >
							<span class="glyphicon glyphicon-eye-open"></span>
							{{trans('messages.view-visits')}}
						</a>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="5">{{trans('messages.no-records-found')}}</td>
				</tr>
			@endforelse
			</tbody>
		</table>
		{{$patients->links()}}
	</div>

</div>
@stop
