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
     * Couter for NEOs data
     *
     * @var int
     */
    protected $countAll;

    /**
     * Counter for NEOs data saved in DB
     * @var int
     */
    protected $countNew;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->countAll = 0;
        $this->countNew = 0;
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
            $this->trougthNasaData($data);
            $this->info("Imported {$this->countAll} NEOs. {$this->countNew} of them is new");
        } catch (NasaException $e) {
            dd($e->getMessage());
        }
    }


    /**
     * @param array $data
     * @throws NasaException
     * @return void
     */
    protected function trougthNasaData(array $data)
    {
        if (!is_array($data)) {
            throw new NasaException("Error: Data is not valid.");
        }

        try {
            foreach ($data as $date => $dayData) {
                foreach ($dayData as $neoData) {
                    $this->saveToDbAndCount([
                        'date' => $date,
                        'reference' => $neoData->neo_reference_id,
                        'name' => $neoData->name,
                        'speed' => $neoData->close_approach_data[0]->relative_velocity->kilometers_per_hour,
                        'is_hazardous' => $neoData->is_potentially_hazardous_asteroid,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            throw new NasaException("Error: NASA Data is not valid. " . $e->getMessage());
        }
    }

    /**
     * @param array $neoData
     * @throws NasaException
     * @return void
     */
    protected function saveToDbAndCount(array $neoData)
    {
        try {
            $neo = Neo::firstOrCreate($neoData);

            $this->countAll++;
            if($neo->wasRecentlyCreated) {
                $this->countNew++;
            }
        } catch (QueryException $e) {
            throw new NasaException("Error: Update DB" . $e->getMessage());
        }
    }
}
