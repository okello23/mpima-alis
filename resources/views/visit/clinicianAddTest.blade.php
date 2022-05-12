@extends("layout")
@section("content")

	<div>
		<ol class="breadcrumb">
		  <li><a href="{{ route('user.home') }}">{{trans('messages.home')}}</a></li>
		  <li>
		  	<a href="{{ route('visit.index') }}">Visits</a>
		  </li>
		  <li class="active">{{trans('messages.new-test')}}</li>
		</ol>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading ">
			<div class="container-fluid">
				<div class="row less-gutter">
					<span class="glyphicon glyphicon-adjust"></span>
					@if(Auth::user()->can('request_test'))
					{{trans('messages.new-test')}}
					@else
					Receive Specimen
					@endif
				</div>
			</div>
		</div>
		<div class="panel-body">
			<!-- if there are creation errors, they will show here -->
			@if($errors->all())
				<div class="alert alert-danger">
					{{ HTML::ul($errors->all()) }}
				</div>
			@endif
			{{ Form::model($visit, array('route' => ['visit.clinicianpostaddtest', $visit->id], 'id' => 'form-new-test')) }}
			<input type="hidden" name="_token" value="{{ Session::token() }}"><!--to be removed function for csrf_token -->
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-info">
								<div class="panel-heading">
									<h3 class="panel-title">{{trans("messages.patient-details")}}</h3>
								</div>
								<div class="panel-body inline-display-details">
									<span><strong>{{trans("messages.patient-number")}}</strong> {{ $visit->patient->patient_number }}</span>
									<span><strong>{{ Lang::choice('messages.name',1) }}</strong> {{ $visit->patient->name }}</span>
									<span><strong>{{trans("messages.age")}}</strong> {{ $visit->patient->getAge() }}</span>
									<span><strong>{{trans("messages.gender")}}</strong>
										{{ $visit->patient->gender==0?trans("messages.male"):trans("messages.female") }}</span>
									<span><strong>Visit Type</strong> {{ $visit->visit_type }}</span>
									@if($visit->visit_type == 'In-patient')
										<span><strong>Ward</strong>
										@if(!is_null($visit->ward))
											{{ $visit->ward->name }}
										@endif
										</span>
										<span><strong>Bed No</strong> {{ $visit->bed_no }}</span>
									@endif
								</div>
							</div>
							<div class="form-group">
							<div class="panel panel-info">
								<div class="panel-heading">
									<h3 class="panel-title">{{"Clinical Information and Sample Information"}}</h3>
								</div>
									<div class="panel-body inline-display-details">
										<!-- for clinician -->
										<div class="col-md-6">
											<div class="form-group">
												{{ Form::label('clinical_notes','Clinical Notes') }}
												{{ Form::textarea('clinical_notes', old('clinical_notes'), array('class' => 'form-control')) }}
											</div>
										</div>
										<div class="col-md-6">
											<!--
											<div class="form-group">
												{{ Form::label('previous_therapy','Previous Therapy') }}
												{{ Form::text('previous_therapy', old('previous_therapy'), array('class' => 'form-control')) }}
											</div>
											<div class="form-group">
												{{ Form::label('current_therapy','Current Therapy', array('text-align' => 'right')) }}
												{{ Form::text('current_therapy', old('current_therapy'), array('class' => 'form-control')) }}
											</div>
											 -->
											<div class="form-group">
												{{ Form::label('physician', 'Test Requested By') }}
												{{Form::text('physician', Illuminate\Support\Facades\Auth::user()->name, array('class' => 'form-control'))}}
											</div>
											<div class="form-group">
												{{ Form::label('cadre', 'Cadre') }}
												{{Form::text('cadre', Illuminate\Support\Facades\Auth::user()->designation, array('class' => 'form-control'))}}
											</div>
											<div class="form-group">
												{{ Form::label('phone_contact', 'Phone Contact') }}
												{{Form::text('phone_contact', Illuminate\Support\Facades\Auth::user()->phone_contact, array('class' => 'form-control'))}}
											</div>
											<div class="form-group">
												{{ Form::label('email', 'E-mail') }}
												{{Form::email('email', Illuminate\Support\Facades\Auth::user()->email, array('class' => 'form-control', 'placeholder' =>Illuminate\Support\Facades\Auth::user()->email))}}
											</div>
										</div>

										<!-- for clinician -->
										<div class="form-pane panel panel-default">
											<div class="col-md-6">
											<!-- show auto selected lab section and tests -->
											<div class="form-group">
													{{Form::label('test_type_category', 'Lab Section')}}
													{{ Form::select('test_type_category', $testCategory,
													Input::get('testCategory'),
													['class' => 'form-control lab-section test-type-category']) }}
												</div>
											</div>
											<div class="col-md-6 test-type-list">
											</div>
											<div class="col-md-12">
												<a class="btn btn-default add-test-to-list"
													href="javascript:void(0);"
													data-measure-id="0"
													data-new-measure-id="">
												<span class="glyphicon glyphicon-plus-sign"></span>Add Test to List</a>
											</div>
										</div>
										<div class="form-pane panel panel-default test-list-panel">
											<div class=" test-list col-md-12">
											<!-- being removed and added at phlebotomy -->
 													<div class="col-md-4">
														<b>Lab Section</b>
													</div>
													<div class="col-md-4">
														<div class="col-md-11"><b>Test</b></div>
														<div class="col-md-1"></div>
													</div>
											</div>
										</div>
									</div>
								</div>
							</div> <!--div that closes the panel div for clinical and sample information -->

								<div class="form-group actions-row">
								{{ Form::button("<span class='glyphicon glyphicon-save'></span> ".trans('messages.save'),
									['class' => 'btn btn-primary', 'onclick' => 'submit()', 'alt' => 'save_new_test']) }}
								</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>

<div class="hidden test-list-loader">
	<div class="col-md-12 new-test-list-row">
		<div class="col-md-4 test-type-category-name">
		</div>
		<div class="col-md-4">
			<div class="col-md-11 test-type-name">
				<input class="specimen-type-id" type="hidden">
				<input class="test-type-id" type="hidden">
			</div>
			<button class="col-md-1 delete-test-from-list close" aria-hidden="true" type="button"
				title="{{trans('messages.delete')}}">×</button>
		</div>
	</div><!-- Test List Item -->
</div><!-- Test List Item Loader-->
@stop
