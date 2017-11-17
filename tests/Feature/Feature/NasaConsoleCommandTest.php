<?php

namespace Tests\Feature\Feature;

use App\Model\Neo;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NasaConsoleCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommand()
    {
        Artisan::call('update:nasa');
        $output = Artisan::output();
        $this->assertRegExp('/Imported [1-9]+ NEOs. [1-9]+ of them is new\n/i', $output);
        $this->assertTrue((Neo::count()) > 0);
    }
}
