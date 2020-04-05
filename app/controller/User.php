<?php
namespace app\controller;

use app\BaseController;
use app\model\User as UserModel;
use app\validate\User as UserValidate;
use think\exception\ValidateException;
use think\facade\Cache;

class User extends BaseController
{
    protected   $mod_user;

    protected function initialize()
    {
        parent::initialize();

        $this->mod_user =   new UserModel();
    }

    /**
     * 会员注册
     */
    public function signup()
    {
        $data       =   input("post.");

        try {
            validate(UserValidate::class)->check($data);
        } catch (ValidateException $e) {
            return ajaxReturn(null, $this->responseCode['APP_DATA_EMPTY'], $e->getError());
        }

        // 检测注册手机号码是否已存在
        $check_user =   $this->mod_user->where("mobile", $data['mobile'])->find();
        if (!empty($check_user)) {
            return ajaxReturn(null, $this->responseCode['USER_USERNAME_EXIST'], "手机号码已注册");
        }

        // 生成Token密码
        $token_passwd       =   genPasswdToken($data['password']);
        $data['strp_token'] =   $token_passwd['strp_token'];
        $data['password']   =   $token_passwd['password'];

        // 保存会员注册数据
        $this->mod_user->save($data);

        return ajaxReturn(null, 0, "注册新会员成功！");
    }

    /**
     * 会员登录
     */
    public function signin()
    {
        $data       =   input("post.");
        
        try {
            validate(UserValidate::class)->check($data);
        } catch (ValidateException $e) {
            return ajaxReturn(null, $this->responseCode['APP_DATA_EMPTY'], $e->getError());
        }
        
        $user   =   $this->mod_user->where("mobile", $data['mobile'])->find();
        if (empty($user)) {
            return ajaxReturn(null, $this->responseCode['USER_PASSWORD_ERR'], "用户名或密码不正确");
        }
        
        $token_passwd   =   genPasswdToken($data['password'], $user->strp_token);
        if ($user->password != $token_passwd['password']){
            return ajaxReturn(null, $this->responseCode['USER_PASSWORD_ERR'], "用户名或密码不正确");
        }

        // 隐藏敏感字段
        $user->hidden(['password','strp_token']);

        // 根据UUID生成Token，保存到Redis中
        $sess_token =   genSessToken($user->user_id);
        Cache::store("redis")->set($sess_token, $user, config("extra.token_expire"));

        return ajaxReturn($user, 0, "Success", ["token" => $sess_token]);
    }

    public function index()
    {
        $user_info  =   $this->request->user_info;

        return ajaxReturn($user_info);
    }
}