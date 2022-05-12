@extends("layout")
@section("content")
	<div>
		<ol class="breadcrumb">
		  <li><a href="{{ route('user.home')}}">{{ trans('messages.home') }}</a></li>
		  <li><a href="{{ route('unhls_patient.index') }}">{{ Lang::choice('messages.patient',2) }}</a></li>
		  <li class="active">{{trans('messages.create-patient')}}</li>
		</ol>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading ">
			<span class="glyphicon glyphicon-user"></span>
			{{trans('messages.create-patient')}}
		</div>
		<div class="panel-body">
		<!-- if there are creation errors, they will show here -->

			@if($errors->all())
				<div class="alert alert-danger">
					{{ HTML::ul($errors->all()) }}
				</div>
			@endif

			{{ Form::open(array('url' => 'unhls_patient', 'id' => 'form-create-patient')) }}
				<div class="form-group">
					{{ Form::label('patient_number', trans('messages.patient-number')) }}
					{{ Form::text('patient_number', old('patient_number'),
						array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('ulin', trans('messages.ulin'), array('class' => 'required')) }}
					@if($ulinFormat == 'Manual')
					{{ Form::text('ulin', old('ulin'),array('class' => 'form-control')) }}
					@else
					{{ Form::text('ulin', '',
						array('class' => 'form-control', 'readonly' =>'true', 'placeholder' => 'Auto generated upon succesfull save!')) }}
					@endif
				</div>
				<div class="form-group">
					{{ Form::label('nin', trans('messages.national-id')) }}
					{{ Form::text('nin', old('nin'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('name', trans('messages.names'), array('class' => 'required')) }}
					{{ Form::text('name', old('name'), array('class' => 'form-control')) }}
					@if ($errors->has('name'))
						<span class="text-danger">
						<strong>{{ $errors->first('name') }}</strong>
					</span>
					@endif
				</div>
				<div class="form-group">
					<label class= 'required' for="dob">Date Of Birth</label>
					<input type="text" name="dob" id="dob" class="form-control input-sm" size="11">
				</div>
				<div class="form-group">
					<label for="age">Age</label>
					<input type="text" name="age" id="age" class="form-control input-sm" maxlength="50%" size="4">
					<select name="age_units" id="id_age_units" class="form-control input-sm">
						<option value="Y">Years</option>
						<option value="M">Months</option>
						<option value="D">Days</option>
					</select>
				</div>
				<div class="form-group">
					{{ Form::label('gender', trans('messages.sex'), array('class' => 'required')) }}
					<div>{{ Form::radio('gender', '0', true) }}
					<span class="input-tag">{{trans('messages.male')}}</span></div>
					<div>{{ Form::radio("gender", '1', false) }}
					<span class="input-tag">{{trans('messages.female')}}</span></div>
				</div>
				<div class="form-group">
					{{ Form::label('nationality', trans('Nationality')) }}
					{{ Form::select('nationality', [' ' => '--- Select Nationality ---',
					'0' => trans('National'),'1' => trans('Refugee'),'2' => trans('Foreigner')], null,
						array('class' => 'form-control')) }}
					@if ($errors->has('nationality'))
						<span class="text-danger">
						<strong>{{ $errors->first('nationality') }}</strong>
					</span>
					@endif
				</div>
				<div class="form-group">
					{{ Form::label('village_residence', trans('messages.residence-village'),array('class' => 'required')) }}
					{{ Form::text('village_residence', old('village_residence'), array('class' => 'form-control',
					'required' => 'required')) }}
				</div>
				<div class="form-group">
					{{ Form::label('village_workplace', trans('messages.workplace-village')) }}
					{{ Form::text('village_workplace', old('village_workplace'), array('class'=>'form-control')) }}
					@if ($errors->has('village_workplace'))
						<span class="text-danger">
                                                <strong>{{ $errors->first('village_workplace') }}</strong>
                                            </span>
					@endif
				</div>
				<div class="form-group">
					{{ Form::label('address', trans('messages.physical-address')) }}
					{{ Form::text('address', old('address'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('occupation', trans('messages.occupation')) }}
					{{ Form::text('occupation', old('occupation'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('phone_number', trans('messages.phone-number'), array('class' => 'required')) }}
					{{ Form::text('phone_number', old('phone_number'), array('class' => 'form-control','required' => 'required')) }}
				</div>
				<div class="form-group">
					{{ Form::label('email', trans('messages.email-address')) }}
					{{ Form::email('email', old('email'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group actions-row">
					{{ Form::button('<span class="glyphicon glyphicon-save"></span> '.trans('messages.save'),
						['class' => 'btn btn-primary', 'onclick' => 'submit()']) }}
				</div>

			{{ Form::close() }}
		</div>
	</div>
@stop
