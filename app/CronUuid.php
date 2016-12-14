<?php

namespace App;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class CronUuid
{
    public static function generate()
    {
        return Uuid::uuid4()->toString();
    }
}
