<?php
namespace app\controller;

use app\BaseController;
use think\facade\Cache;
use think\facade\Event;

class Test extends BaseController
{
    public function index()
    {
        $test   =   "Test ssssdfksdf ok!";
        Event::trigger('WebscoketMsg');
    }

    public function mezz(string $name)
    {
        $data = ['name' => $name, 'sex' => COMMON_STATE_FALSE, 'mobile' => '15817324178'];
        
        return ajaxReturn($data);
    }
}