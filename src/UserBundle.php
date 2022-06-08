<?php

namespace Glavnivc\UserBundle;

use Mchs\AccountBundle\DependencyInjection\AccountExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle
{

//    public function getContainerExtension() : AccountExtension
//    {
//        if ($this->extension === null) {
//            $this->extension = new AccountExtension();
//        }
//        return $this->extension;
//    }


    public function test()
    {
        echo "test";
        die();
    }


}
