<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use App\ExternalApi\Nasa;
use App\Model\Neo;
use App\Exceptions\NasaException;
use App\Http\Service\NasaDataUpdater;



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
     * @return void
     */
    public function handle()
    {
        try {
            $data = (new Nasa())->getNeo();
            $dataUpdater = new NasaDataUpdater($data);
            $this->info(
                "Imported {$dataUpdater->getCountAll()} NEOs."
                . " {$dataUpdater->getCountNew()} of them is new"
            );
        } catch (NasaException $e) {
            $this->info($e->getMessage());
        }
    }

}
