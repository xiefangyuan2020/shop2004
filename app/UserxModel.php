<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserxModel extends Model
{
    protected $table = 'userx';
    protected $primaryKey = 'u_id';
    public $timestamps = false;
}
