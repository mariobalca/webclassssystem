<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['api_id', 'label', 'score', 'confident', 'parent'];

    protected $table = 'categories';

    public $timestamps = true;

    public function websites() {
    	return $this->belongsToMany('App\Website', 'website_category', 'api_id', 'url');
    }
}
