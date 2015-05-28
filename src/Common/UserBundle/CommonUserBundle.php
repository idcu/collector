<?php

namespace Common\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommonUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
