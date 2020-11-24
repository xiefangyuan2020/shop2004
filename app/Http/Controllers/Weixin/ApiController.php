<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function test(){
    	 // print_r($_GET);
    	 // print_r($_POST);
    	 $data = [
    	 	'name' => 'zhangsan',
    	 	'age' => '18'
    	 ];
    	 echo json_encode($data);
    }

    public function addUser(){
    	print_r($_GET);
    	print_r($_POST);
    }

}
