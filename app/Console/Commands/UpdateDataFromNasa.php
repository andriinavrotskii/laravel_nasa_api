<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ExternalApi\Nasa;

class UpdateDataFromNasa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:nasa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update data in DB from NASA API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $nasa = new Nasa();
        $nasa->updateNeo();
        print_r($nasa->getLog());
    }
}
