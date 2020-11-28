<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\OpenidModel;
use App\Model\PgoodsModel;
use App\UserxModel;
use DB;

class XcxController extends Controller
{
    //小程序登录
    public function login(Request $request){
        $userinfo = $request->u;
        //dd($u);
    	//接收code
    	$code = $request->get('code');
    	// echo $code;
    	//使用code
    	$url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.env('WX_XCX_APPID').'&secret='.env('WX_XCX_SECRET').'&js_code='.$code.'&grant_type=authorization_code';
    	$data = json_decode(file_get_contents($url),true);
    	//print_r($data);


    	//自定义登录状态
    	if(isset($data['errcode'])){
    		//有错误
    		$response = [
    			'error' => 50001,
    			'msg' => '登录失败'
    		];
    		//TODO错误处理
    	}else{
    		// $res = OpenidModel::where("openid",$data['openid'])->first();
    		// if(empty($res)){
    		// 	OpenidModel::insert(["openid"=>$data["openid"]]);
    		// }
            $openid=$data['openid'];

            $u = UserxModel::where('openid',$openid)->first();

            if($u){
                //echo "老用户,已入库"
                $uid = $u->id;
            }else{
               // dd($userinfo);
                $u_info=[
                    'openid' => $openid,
                    'nickname' => $userinfo['nickName'],
                    'sex' =>  $userinfo['gender'],
                    'language' => $userinfo['language'],
                    'city'=> $userinfo['city'],
                    'province' =>  $userinfo['province'],
                    'country'  => $userinfo['country'],
                    'headimgurl'=>$userinfo['avatarUrl'],
                    'add_time'=>time(), //小程序
                    'type'=> 3
                ];

                $uid = UserxModel::insertGetId($u_info);
            }
    		//成功
    		$token = sha1($data['openid'].$data['session_key'].mt_rand(0,999999));
            //dd($token);
    		//echo $token;
            $login_info = [
                'uid' => $uid,
                'user_name' => "",
                'login_time' => date('Y-m-d H:i:s'),
                'login_ip' => $token,
                'openid' => $openid
            ];
    		//保存token
    		$redis_key = 'xcx_token:'.$token;
    		Redis::hMset($redis_key,$login_info);
    		//设置过期时间
    		Redis::expire($redis_key,7200);

    		$response = [
    			'error' => 0,
    			'msg' => 'ok',
    			'data' => [
    				'token' => $token
    			]
    		];
    	}
    	return $response;
    }

    //商品列表
    // public function goods(){
    //     $goods = PgoodsModel::limit('20')->get()->toArray();
    //     return $goods;
    // }

    public function goods(Request $request){
        // $goods = PgoodsModel::select('goods_id','goods_name','shop_price',"goods_img")->limit(10)->get()->toArray();
        //下拉刷新
        $page_size = $request->get('ps');
        $goods = PgoodsModel::select('goods_id','goods_name','shop_price',"goods_img")->paginate($page_size);
        // dd($goods);
        $response = [
            'error' => 0,
            'msg' => 'ok',
            'data' => [
                'list' => $goods->items()
            ]
        ];
        return $response;
    }

    //商品详情页
    public function detail(){
        $goods_id = request()->goods_id;
        $res = PgoodsModel::select('goods_name','shop_price','goods_img','goods_number','goods_newest','goods_desc','goods_imgs','keywords')->where('goods_id',$goods_id)->first()->toArray();
        $array = [
            "goods_name" => $res['goods_name'],
            "shop_price" => $res['shop_price'],
            "goods_number" => $res['goods_number'],
            "goods_newest" => $res['goods_newest'],
            "keywords" => $res['keywords'],
            "goods_desc" => explode("|",$res['goods_desc']),
            "goods_imgs" => explode("|",$res['goods_imgs'])
        ];
        return $array;

    }

}


