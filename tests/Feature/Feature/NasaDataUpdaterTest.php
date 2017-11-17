<?php

namespace Tests\Feature\Feature;

use App\Service\NasaDataUpdater;
use Tests\NasaApiDataProvider;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NasaDataUpdaterTest extends TestCase
{
    use RefreshDatabase;

    public function testWorkflow()
    {
        $fakeData = NasaApiDataProvider::dataProvider();
        $dataUpdater = new NasaDataUpdater();
        $resultOne = $dataUpdater->update($fakeData);
        $resultTwo = $dataUpdater->update($fakeData);

        $this->assertEquals($resultOne, ['all' => 21, 'new' => 21]);
        $this->assertEquals($resultTwo, ['all' => 21, 'new' => 0]);
    }
}
