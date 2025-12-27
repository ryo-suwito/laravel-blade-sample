<?php

namespace App\Constants;

use ReflectionClass;

class ModelTypeConstant
{
    const BENEFICIARY = 'BENEFICIARY';
    const PARTNER = 'PARTNER';

    public static function all()
    {
        $class = new ReflectionClass(__CLASS__);

        return $class->getConstants();
    }
}
