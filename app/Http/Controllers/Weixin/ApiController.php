<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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

    //加入收藏
    public function addFav(Request $request){
        $goods_id = $request->get('id');
        //加入收藏redis有序集合
        $uid = 2345;
        $redis_key = 'ss:goods:fav:'.$uid; //用户收藏的商品有序集合
        Redis::Zadd($redis_key,time(),$goods_id); //将商品id加入有序集合，并给排序值

        $response = [
            'error' => 0,
            'msg' => 'ok'
        ];
        return $response;
    }

    //加入购车
    public function addCart(){
        print_r($_POST);
        print_r($_GET);
    }

}
