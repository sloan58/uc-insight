<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bulk extends Model
{

    protected $fillable = ['file_name'];

    public function erasers()
    {
        return $this->belongsToMany('App\Eraser');
    }
}
