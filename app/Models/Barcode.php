<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Barcode extends Model
{
	/**
	 * Enabling soft deletes for barcode settings.
	 *
	 */
    use SoftDeletes;
	protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'barcode_settings';
}
