<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

use App\UserxModel;
use App\CollectModel;
use App\Model\PgoodsModel;
use App\CartModel;

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

    //加入收藏
    // public function addFav(Request $request){
    //     $goods_id = $request->get('id');
    //     $token = $request->get('token');
    //     $key = "xcxkey:".$token;
    //     //取出openid
    //     $token = Redis::hgetall($key);
    //     $user_id = UserxModel::where('openid',$token['openid'])->select('id')->first()->toArray();

    //     $data = [
    //         'goods_id' => $goods_id,
    //         'add_time' => time();
    //         'user_id' =>$user_id['id']
    //     ];
    //     $res = CollectModel::insert($data);

    //     if($res){
    //         $response = [
    //             'error' => 0,
    //             'msg' => '收藏成功',
    //         ];
    //     }else{
    //         $response = [
    //             'error' => 50001,
    //             'msg' => '收藏失败',
    //         ];
    //     }
    //     return $response;
    // }

    //加入购车
     public function addCart(Request $request)
    {
        $goods_id = $request->post('goodsid');
        $uid = $_SERVER['uid'];

        //查询商品的价格
        $price = GoodsModel::find($goods_id)->shop_price;

        //将商品存储购物车表 或 Redis
        $info = [
            'goods_id'  => $goods_id,
            'uid'       => $uid,
            'goods_num' => 1,
            'add_time'  => time(),
            'cart_price' => $price
        ];

        $id = CartModel::insertGetId($info);
        if($id)
        {
            $response = [
                'errno' => 0,
                'msg'   => 'ok'
            ];
        }else{
            $response = [
                'errno' => 50002,
                'msg'   => '加入购物车失败'
            ];
        }

        return $response;
    }

}
