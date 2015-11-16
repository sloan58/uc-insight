<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sql extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sql'];

    public function users()
    {
        return $this->belongsToMany('App\Users');
    }
}