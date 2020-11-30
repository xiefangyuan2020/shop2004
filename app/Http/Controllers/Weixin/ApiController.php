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
    //     $user_id = UserxModel::where('openid',$token['openid'])->select('u_id')->first()->toArray();

    //     $data = [
    //         'goods_id' => $goods_id,
    //         'add_time' => time();
    //         'user_id' =>$user_id['u_id']
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
    public function addCart(Request $request){
       $goods_id = $request->post('goodsid');
       //dd($goods_id);
       $uid = $_SERVER['uid'];
    dd($uid);

        //查询商品的价格  购买数量  商品名称
        $shop_price = PgoodsModel::find($goods_id)->shop_price;
        //dd($shop_price);
        $goods_sn=PgoodsModel::find($goods_id)->goods_sn;
        $goods_name=PgoodsModel::find($goods_id)->goods_name;
        //将商品存储购物车表 或 Redis
        $info = [
            'goods_id'  => $goods_id,
            'uid'       => $uid,
            'goods_name' =>$goods_name,
            'goods_sn' => $goods_sn,
            'add_time'  => time(),
            'shop_price' => $shop_price
            
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
   
