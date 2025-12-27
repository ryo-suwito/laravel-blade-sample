<?php

namespace App\Constants;

use ReflectionClass;

class RoleTargetTypeConstant
{
    const YUKK_CO = 'YUKK_CO';
    const PARTNER = 'PARTNER';
    const MERCHANT_BRANCH = 'MERCHANT_BRANCH';
    const CUSTOMER = 'CUSTOMER';

    public static function all()
    {
        $class = new ReflectionClass(__CLASS__);

        return $class->getConstants();
    }
}
