<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
	/**
	 * Enabling soft deletes for drugs.
	 *
	 */
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'inv_items';
	/*
    *   Relationship with stocks
    */
    public function stocks()
    {
        return $this->hasMany('App\Models\Stock');
    }

    /*
    *   Relationship with requests
    */
    public function requests()
    {
        return $this->hasMany('App\Models\Topup');
    }
    /*
    *   Get quantity for the specific item
    */
    public function quantity()
    {
        $used = 0;
        $available = $this->stocks->sum('quantity_supplied');
        if(count($this->stocks)>0)
            $used = Usage::whereIn('stock_id', $this->stocks->pluck('id')->toArray())->sum('quantity_used');
        return $available-$used;
    }
}
