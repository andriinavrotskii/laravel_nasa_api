<?php

namespace Tests\Feature\Api;

use App\Model\Neo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{

    public function testApi()
    {
        $neo = factory(Neo::class)->create([
            'reference' => 1111111,
            'date' => '2017-11-16',
            'name' => '1111111',
            'speed' => 111111.1111111111,
            'is_hazardous' => 0,
        ]);
        $neo = factory(Neo::class)->create([
            'reference' => 2222222,
            'date' => '2017-11-16',
            'name' => '2222222',
            'speed' => 222222.2222222222,
            'is_hazardous' => 1,
        ]);
        $neo = factory(Neo::class)->create([
            'reference' => 3333333,
            'date' => '2017-11-16',
            'name' => '3333333',
            'speed' => 333333.3333333333,
            'is_hazardous' => 0,
        ]);

        $response = $this->json('GET', '/api/neo/hazardous')
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                '*' => ['id', 'reference', 'date', 'name', 'speed', 'is_hazardous']
            ])
            ->assertJson([
                'reference' => 2222222,
                'date' => '2017-11-16',
                'name' => '2222222',
                'speed' => 222222.2222222222,
                'is_hazardous' => 1,
            ]);

        dd($response->getReference());
    }
}
