<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $fillable = ['name', 'ip', 'username', 'password', 'active', 'version', 'verify_peer'];
}
