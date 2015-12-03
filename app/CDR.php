<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cdr extends Model
{
    protected $fillable = ['dialednumber', 'callerid', 'calltype','message','successful','failurereason'];

}
