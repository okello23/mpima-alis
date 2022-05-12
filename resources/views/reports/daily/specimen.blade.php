@extends("layout")
@section("content")
<div>
	<ol class="breadcrumb">
	  <li><a href="{{ route('user.home') }}">{{ trans('messages.home') }}</a></li>
	  <li class="active"><a href="{{ route('reports.patient.index') }}">{{ Lang::choice('messages.report', 2) }}</a></li>
	  <li class="active">{{ trans('messages.daily-log') }}</li>
	</ol>
</div>
<div class='container-fluid'>
{{ Form::open(array('route' => array('reports.daily.log'), 'class' => 'form-inline', 'role' => 'form')) }}
	<div class='row'>
		<div class="col-sm-4">
	    	<div class="row">
				<div class="col-sm-2">
				    {{ Form::label('start', trans('messages.from')) }}
				</div>
				<div class="col-sm-2">
				    {{ Form::text('start', isset($input['start'])?$input['start']:date('Y-m-d'),
			                array('class' => 'form-control standard-datepicker')) }}
		        </div>
			</div>
		</div>
		<div class="col-sm-4">
	    	<div class="row">
				<div class="col-sm-2">
				    {{ Form::label('end', trans('messages.to')) }}
				</div>
				<div class="col-sm-2">
				    {{ Form::text('end', isset($input['end'])?$input['end']:date('Y-m-d'),
			                array('class' => 'form-control standard-datepicker')) }}
		        </div>
			</div>
		</div>
		<div class="col-sm-4">
	    	<div class="row">
				<div class="col-sm-3">
				  	{{ Form::button("<span class='glyphicon glyphicon-filter'></span> ".trans('messages.view'),
		                array('class' => 'btn btn-info', 'id' => 'filter', 'type' => 'submit')) }}
		        </div>
		        <div class="col-sm-1">
					{{Form::submit(trans('messages.export-to-word'),
			    		array('class' => 'btn btn-success', 'id'=>'word', 'name'=>'word'))}}
				</div>
			</div>
		</div>
	</div>
    <div class='row spacer'>
    	<div class="col-sm-12">
	    	<div class="row">
				<div class="col-sm-4">
					<label class="radio-inline">
					    {{ Form::radio('records', 'tests', false, array('data-toggle' => 'radio', 'id' => 'tests')) }}
						   {{trans('messages.test-records')}}
				   	</label>
				</div>
				<div class="col-sm-4">
					<label class="radio-inline">
						{{ Form::radio('records', 'patients', false, array('data-toggle' => 'radio', 'id' => 'patients')) }}
						  	{{trans('messages.patient-records')}}
				  	</label>
				</div>
				<div class="col-sm-4">
					<label class="radio-inline">
						{{ Form::radio('records', 'rejections', true, array('data-toggle' => 'radio', 'id' => 'rejections')) }}
						  	{{trans('messages.rejected-specimen')}}
				  	</label>
				</div>
			</div>
		</div>
	</div>
	<div class='row spacer'>
	  	<div class="col-sm-6">
	    	<div class="row">
				<div class="col-sm-3">
					{{ Form::label('section_id', Lang::choice('messages.test-category', 2)) }}
				</div>
			  	<div class="col-sm-3">
					{{ Form::select('section_id', array(''=>trans('messages.select-lab-section'))+$labSections,
						Request::old('testCategory') ? Request::old('testCategory') : $testCategory,
							array('class' => 'form-control', 'id' => 'section_id')) }}
				</div>
			</div>
		</div>
		<div class="col-sm-6">
	    	<div class="row">
				<div class="col-sm-3">
					{{ Form::label('description', Lang::choice('messages.test-type', 2)) }}
				</div>
				<div class="col-sm-3">
					{{ Form::select('test_type', array('' => trans('messages.select-test-type'))+$testTypes,
						Request::old('testType') ? Request::old('testType') : $testType,
							array('class' => 'form-control', 'id' => 'test_type')) }}
				</div>
			</div>
		</div>
	</div>
{{ Form::close() }}
</div>
<br />
<div class="panel panel-primary">
	<div class="panel-heading ">
		<span class="glyphicon glyphicon-user"></span>
		{{ trans('messages.daily-log') }} - {{ trans('messages.rejected-specimen') }}
	</div>
	<div class="panel-body">
		<!-- if there are search errors, they will show here -->
		@if ($error!='')
			<div class="alert alert-info">{{ $error }}</div>
		@else

		<div id="specimen_records_div">
		  @include("reportHeader")
			<strong>
				<p>
					{{trans('messages.rejected-specimen')}}
					@if($testCategory)
						{{' - '.App\Models\TestCategory::find($testCategory)->name}}
					@endif
					@if($testType)
						{{' ('.App\Models\TestType::find($testType)->name.') '}}
					@endif
					<?php $from = isset($input['start'])?$input['start']:date('Y-m-d'); ?>
					<?php $to = isset($input['end'])?$input['end']:date('Y-m-d'); ?>
					@if($from!=$to)
						{{trans('messages.from').' '.$from.' '.trans('messages.to').' '.$to}}
					@else
						{{trans('messages.for').' '.date('d-m-Y')}}
					@endif
				</p>
			</strong>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th>{{ trans('messages.specimen-number-title') }}</th>
						<th>{{ trans('messages.specimen') }}</th>
						<th>{{ trans('messages.lab-receipt-date') }}</th>
						<th>{{ Lang::choice('messages.test', 2) }}</th>
						<th>{{ Lang::choice('messages.test-category', 1) }}</th>
						<th>{{ trans('messages.rejection-reason-title') }}</th>
						<th>{{ trans('messages.reject-explained-to') }}</th>
						<th>{{ trans('messages.date-rejected') }}</th>
					</tr>
					@forelse($test_specimen as $specimens)
					<tr>
						<td>{{ $specimens->getSpecimenId() }}</td>
						<td>{{ $specimens->specimen->specimentype->name }}</td>
						<td>{{ $specimens->specimen->time_accepted }}</td>
						<td>{{ $specimens->testType->name }}</td>
						<td>{{ $specimens->testType->testCategory->name }}</td>
						<td><ul>
								@foreach(getRejectionReasons($specimens->id) as $reason)
								<li>{{ $reason->reason }}</li>
								@endforeach
								</ul></td>
						<td>{{ $specimens->analyticSpecimenRejections->reject_explained_to }}</td>
						<td>{{ $specimens->analyticSpecimenRejections->time_rejected }}</td>
					</tr>
					@empty
					<tr><td colspan="8">{{ trans('messages.no-records-found') }}</td></tr>
					@endforelse
				</tbody>
			</table>
		</div> <!-- /#specimen_records_div -->
		@endif
	</div> <!-- /.panel-body -->
</div> <!-- /.panel -->
@stop
