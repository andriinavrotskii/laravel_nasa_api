<?php

namespace Tests\Feature\Api;

use App\Model\Neo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    private function createNeos()
    {
        factory(Neo::class)->create([
            'reference' => 1111111,
            'date' => '2017-11-16',
            'name' => '1111111',
            'speed' => 111111.1111111111,
            'is_hazardous' => 0,
        ]);
        factory(Neo::class)->create([
            'reference' => 2222222,
            'date' => '2017-11-16',
            'name' => '2222222',
            'speed' => 222222.2222222222,
            'is_hazardous' => 1,
        ]);
        factory(Neo::class)->create([
            'reference' => 3333333,
            'date' => '2017-11-16',
            'name' => '3333333',
            'speed' => 333333.3333333333,
            'is_hazardous' => 0,
        ]);
    }


    public function testApiHazardous()
    {
        $this->createNeos();

        $response = $this->json('GET', '/api/neo/hazardous')
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure([
                '*' => ['reference', 'date', 'name', 'speed', 'is_hazardous']
            ])
            ->assertJson([
                [
                    'reference' => 2222222,
                    'date' => '2017-11-16',
                    'name' => '2222222',
                    'speed' => 222222.2222222222,
                    'is_hazardous' => 1,
                ]
            ]);

    }

    public function testApiFastestNonHazardous()
    {
        $this->createNeos();

        $response = $this->json('GET', '/api/neo/fastest?hazardous=false')
            ->assertStatus(200)
            ->assertJsonStructure(['reference', 'date', 'name', 'speed', 'is_hazardous'])
            ->assertJson([
                'reference' => 3333333,
                'date' => '2017-11-16',
                'name' => '3333333',
                'speed' => 333333.3333333333,
                'is_hazardous' => 0,
            ]);
    }

    public function testApiFastestHazardous()
    {
        $this->createNeos();

        $fourthNeo = [
            'reference' => 4444444,
            'date' => '2017-11-16',
            'name' => '4444444',
            'speed' => 4444444.4444444444,
            'is_hazardous' => 1,
        ];

        factory(Neo::class)->create($fourthNeo);

        $response = $this->json('GET', '/api/neo/fastest?hazardous=true')
            ->assertStatus(200)
            ->assertJsonStructure(['reference', 'date', 'name', 'speed', 'is_hazardous'])
            ->assertJson($fourthNeo);
    }
}
