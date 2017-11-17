<?php

namespace App\Service;

use App\Model\Neo;
use App\Exceptions\NasaException;

class NasaDataUpdater
{
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
     * @var array
     */
    protected $data;

    /**
     * @return void
     */
    public function __construct(array $data)
    {
        $this->countAll = 0;
        $this->countNew = 0;
        $this->goTrougthNasaData($data);
    }

    /**
     * @return int
     */
    public function getCountAll()
    {
        return $this->countAll;
    }

    /**
     * @return int
     */
    public function getCountNew()
    {
        return $this->countNew;
    }

    /**
     * @param array $data
     * @throws NasaException
     * @return void
     */
    protected function goTrougthNasaData(array $data)
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