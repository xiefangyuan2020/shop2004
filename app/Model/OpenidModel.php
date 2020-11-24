<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OpenidModel extends Model
{
    protected $table = 'openid';
    protected $primaryKey = 'id';
    public $timestamps = false;
}