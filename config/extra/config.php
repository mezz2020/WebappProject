<?php
/**
 * 应用扩展配置
 */
return [
    "token_expire"      =>  1800,       // Session Token过期时间(30分钟)

    // Ajax统一返回Code
    "response_code"     =>  [
        "APP_SYSTEM_ERR"        =>      "10001",        //  系统错误
        "APP_DATA_EMPTY"        =>      "10002",        //  数据为空
        "APP_PARAM_ERR"         =>      "10003",        //  参数错误
        "APP_OPERATE_FAILED"    =>      "10004",        //  操作失败

        "USER_USERNAME_ERR"     =>      "20001",        //  用户名不正确
        "USER_PASSWORD_ERR"     =>      "20002",        //  密码不正确
        "USER_TOKEN_EMPTY"      =>      "20003",        //  获取用户信息失败或不存在
        "USER_USERNAME_EXIST"   =>      "20004",        //  用户名或已存在
    ]
];