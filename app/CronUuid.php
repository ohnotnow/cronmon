<?php

namespace App;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CronUuid
{
    use HasFactory;

    public static function generate()
    {
        return Uuid::uuid4()->toString();
    }
}
