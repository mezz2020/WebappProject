<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class User extends Model
{
    public function getUserInfo(array $cond = null) 
    {
        $condition  =   [];

        if (isset($cond['user_id']))
            $condition[]    =   ["user_id", "=", $cond['user_id']];

        $result     =   $this->where($condition)->find();
        return $result;
    }
}
