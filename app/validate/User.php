<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'username'      =>  'require|min:6|max:35',
        'mobile'        =>  'require|mobile',
        'password'      =>  'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'username.require'      =>  '用户名不能为空',
        'username.min'          =>  '用户名长度不能低于6位',
        'username.max'          =>  '用户名长度不能高于35位',
        'mobile.require'        =>  '手机号码不能为空',
        'mobile.mobile'         =>  '手机号码格式不正确',
        'password.require'      =>  '密码不能为空',
    ];
}
