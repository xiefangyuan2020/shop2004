<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectModel extends Model
{
    protected $table = 'xcx_collect';
    public $primaryKey = "id";
    public $timestamps = false;
}
