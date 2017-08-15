<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = ['url'];

    protected $table = 'websites';

    public $timestamps = true;

    public function categories() {
    	return $this->belongsToMany('App\Category', 'website_category', 'url', 'api_id');
    }
}
