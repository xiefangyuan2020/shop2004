<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;


class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //验证token
        $token = $request->get('token');
        // dd($token);
        // $redis_login_hash = 'xcx_token:' . $token;
        $redis_key = 'xcx_token:'.$token;
        $login_info = Redis::hgetall($redis_key);
        if($login_info)
        {
            // dd(123);
            $_SERVER['uid'] = $login_info['uid'];
        }else{
            // dd(567);
            $response = [
                'errno' => 400003,
                'msg'   => "未授权"
            ];
            die(json_encode($response));
        }

        return $next($request);
    }
}