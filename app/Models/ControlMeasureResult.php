<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlMeasureResult extends Model {

	protected $table = 'control_results';

	/**
	* Relationship between result and measure
	*
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function controlMeasure()
	{
		return $this->belongsTo('App\Models\ControlMeasure', 'control_measure_id');
	}

	/**
	* Relationship between result and test
	*
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function controlTests()
	{
		return $this->belongsTo('App\Models\ControlTest', 'control_test_id');
	}
}
