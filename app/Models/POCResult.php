<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;

class POCResult extends Model {

	protected $table = 'poc_results';

	protected $fillable = [
		'patient_id',
		'results',
		'test_date',
		'tested_by',
		'dispatched_by',
		'dispatched_date',
		'test_time',
		'equipment_used',
		'equipment_serial_number',
		'catridge_serial_number',
		'machine_sample_detection',
		'device_status',
		'hiv1_positive_control',
		'hiv2_positive_control',
		'negative_control',
		'device_analysis',
		'device_software_version',
		'test_summary',
		'test_type' ,
		'error_code'
	];

	public function poc(){
		return $this->belongsTo('App\Models\POC', 'patient_id');
	}

}
