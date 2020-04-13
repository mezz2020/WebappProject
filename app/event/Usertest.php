<?php
declare (strict_types = 1);

namespace app\event;
use app\model\User;

class Usertest
{
    public $user;

    public function __construct(User $user)
    {
        $this->user =   $user;
    }

    public function testCount()
    {
        echo "Run Event: testCount...<br/>"; 

        return "TestCount Data";
    }
}
