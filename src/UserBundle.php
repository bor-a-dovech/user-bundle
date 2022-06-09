<?php

namespace Glavnivc\UserBundle;

use Glavnivc\UserBundle\DependencyInjection\UserBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle
{
    public function getContainerExtension() : UserBundleExtension
    {
        if ($this->extension === null) {
            $this->extension = new UserBundleExtension();
        }
        return $this->extension;
    }
}
