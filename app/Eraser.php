<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eraser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['phone_id', 'ip_address', 'eraser_type', 'result'];

    public function Phone()
    {
        return $this->belongsTo('App\Phone');
    }

    public function bulks()
    {
        return $this->belongsToMany('App\Bulk');
    }
}
