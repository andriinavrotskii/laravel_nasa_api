<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use App\ExternalApi\Nasa;
use App\Model\Neo;
use App\Exceptions\NasaException;



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
        try {
            $data = (new Nasa())->getNeo();
            $this->saveToDb($data);
        } catch (NasaException $e) {
            dd($e->getMessage());
        }

    }


    protected function saveToDb($data)
    {
        $count = 0;
        $new = 0;
        if (!is_array($data)) {
            throw new NasaException("Error: Data is not valid.");
        }

        foreach ($data as $date => $dayData) {
            if (!is_array($data)) {
                throw new NasaException("Error: Data is not valid.");
            }
            foreach ($dayData as $neoData) {
                try {
                    $neoData = [
                        'date' => $date,
                        'reference' => $neoData->neo_reference_id,
                        'name' => $neoData->name,
                        'speed' => $neoData->close_approach_data[0]->relative_velocity->kilometers_per_hour,
                        'is_hazardous' => $neoData->is_potentially_hazardous_asteroid,
                    ];
                } catch (\Throwable $e) {
                    throw new NasaException("Error: Data is not valid. " . $e->getMessage()); 
                }

                try {
                    $neo = Neo::firstOrCreate($neoData);   
                } catch (QueryException $e) {
                    throw new NasaException("Error: Update DB" . $e->getMessage()); 
                }


                $count++;
                if($neo->wasRecentlyCreated) {
                    $new++;
                }
            }
        }

        $this->info("Imported {$count} NEOs. {$new} of them is new");
    }
}
