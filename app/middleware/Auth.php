<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Cache;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        \think\facade\Config::load('extra/config', 'extra');
        $responseCode =   config("extra.response_code");

        $user_res   =   $this->checkSessToken($request, $responseCode);
        if ($user_res['error'] > 0) {
            return ajaxReturn(null, $user_res['code'], $user_res['msg']);
        }

        $request->user_info =   $user_res['data'];
        return $next($request);
    }

    protected function checkSessToken($request, $responseCode)
    {
        $sess_token =   $request->header("token", "");
        $user_info  =   Cache::store("redis")->get($sess_token);

        if (empty($sess_token) || empty($user_info)) {
            return ["error" => 1, "code" => $responseCode['USER_TOKEN_EMPTY'], "msg" => "获取用户信息失败"];
        }

        Cache::store("redis")->set($sess_token, $user_info, config("extra.token_expire"));
        return ["error" => 0, "data" => $user_info];
    }
}
