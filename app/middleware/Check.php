<?php
declare (strict_types = 1);

namespace app\middleware;

class Check
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

        /*
        // 验证时间戳
        $check_time  =   $this->checkTime($request);
        if ($check_time['error'] > 0) {
            return ajaxReturn(null, $responseCode['APP_PARAM_ERR'], $check_time['msg']);
        }

        // 验证签名
        $check_sign =   $this->checkSign($request);
        if ($check_sign['error'] > 0) {
            return ajaxReturn(null, $responseCode['APP_PARAM_ERR'], $check_sign['msg']);
        }
        */

        return $next($request);
    }

    protected function checkTime($request)
    {
        $result     =   ["error" => 0];
        $timestamp  =   $request->param("timestamp", 0);

        if ($timestamp <= 1 || strlen($timestamp) !== 10) 
            $result =   ["error" => 1, "msg" => "时间戳错误"];
        elseif (abs(time() - intval($timestamp)) > 60)
            $result =   ["error" => 1, "msg" => "请求超时"];

        return $result;
    }

    protected function checkSign($request)
    {
        $result     =   ["error" => 0];
        $sign       =   $request->header("sign", "");
        $valid_sign =   $this->createSign($request);   

        if ($sign != $valid_sign) {
            $result =   ["error" => 1, "msg" => "签名错误"];
        }

        return $result;
    }

    /**
     * 生成签名
     */
    protected function createSign($request)
    {
        $sign_data = $request->except(['ver']);
        ksort($sign_data);
        $sign_data_str = http_build_query($sign_data);
        return md5($sign_data_str);
    }
}
