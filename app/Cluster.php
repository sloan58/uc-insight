<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $fillable = ['name', 'ip', 'username', 'password', 'version', 'verify_peer', 'user_type'];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}

