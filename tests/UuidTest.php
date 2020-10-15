<?php

// @codingStandardsIgnoreFile

namespace Tests;

use App\CronUuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UuidTest extends TestCase
{
    public function test_uuid_is_well_formed()
    {
        $uuid = CronUuid::generate();
        $this->assertEquals(1, preg_match('/(\w{8}(-\w{4}){3}-\w{12}?)/', $uuid));
    }

    public function test_each_call_to_uuid_is_unique()
    {
        $uuids = [];
        foreach (range(1, 100) as $count) {
            $uuids[] = CronUuid::generate();
        }
        $uniqueUuids = array_unique($uuids);
        $this->assertEquals(count($uuids), count($uniqueUuids));
    }
}
