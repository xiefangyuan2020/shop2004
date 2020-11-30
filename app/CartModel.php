<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
     protected $table = 'ecs_cart';
    public $primaryKey = "rec_id";
    public $timestamps = false;
}
