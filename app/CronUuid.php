<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

class CronUuid
{
    use HasFactory;

    public static function generate()
    {
        return Uuid::uuid4()->toString();
    }
}
