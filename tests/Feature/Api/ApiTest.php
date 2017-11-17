<?php

namespace Tests\Feature\Api;

use App\Model\Neo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{

    public function testApi()
    {
        $neo = factory(Neo::class)->create();
        dd($neo);
        $this->assertTrue(true);
    }
}
