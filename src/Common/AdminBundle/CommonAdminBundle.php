<?php

namespace Common\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommonAdminBundle extends Bundle
{
    public function getParent()
    {
        return 'SonataAdminBundle';
    }
}
