<?php

namespace Tests\Feature\Feature;

use App\Model\Neo;
use App\Service\NasaDataUpdater;
use Tests\NasaApiDataProvider;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NasaDataUpdaterTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdater()
    {
        $fakeData = NasaApiDataProvider::dataProvider();
        $dataUpdater = new NasaDataUpdater();
        $resultOne = $dataUpdater->update($fakeData);
        $resultTwo = $dataUpdater->update($fakeData);

        $this->assertEquals($resultOne, ['all' => 21, 'new' => 21]);
        $this->assertEquals($resultTwo, ['all' => 21, 'new' => 0]);
        $this->assertEquals(21, Neo::count());
        $this->assertEquals(3, Neo::distinct()->select('date')->get()->count());
        Neo::destroy([1, 2, 3]);
        $this->assertEquals(18, Neo::count());
    }
}
