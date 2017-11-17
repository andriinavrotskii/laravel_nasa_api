<?php

namespace Tests\Feature\Feature;

use App\ExternalApi\Nasa;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NasaApiTest extends TestCase
{
    use RefreshDatabase;

    public function testApiWorkflow()
    {
        $nasa = new Nasa();
        $response = $nasa->getNeo();
        $this->assertTrue(is_array($response));
        $this->assertTrue(!empty($response));
    }
}
