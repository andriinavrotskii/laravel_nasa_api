<?php

namespace App\ExternalApi;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Carbon;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use App\Model\Neo;

class Nasa
{
    protected const API_KEY = 'N7LkblDsc5aen05FJqBQ8wU4qSdmsftwJagVK7UD';

    protected $log = [
        'count' => 0,
        'new' => 0
    ];


    public function updateNeo()
    {
        $url = 'https://api.nasa.gov/neo/rest/v1/feed?'
                . 'start_date=' . (new Carbon('tomorrow'))->subDays(3)->toDateString()
                . '&end_date=' . (new Carbon('tomorrow'))->toDateString()
                . '&detailed=false'
                . '&api_key=' . self::API_KEY;

        $this->saveToDb($this->apiRequest($url));
    }


    public function getLog()
    {
        return $this->log;
    }


    protected function apiRequest($url)
    {
        try {
            $client = new Guzzle;
            return $client->request('GET', $url);
        } catch (ClientException $e) {
            dd($e->getMessage());
        }
    }


    protected function saveToDb(GuzzleResponse $response)
    {
        $data = json_decode((string) $response->getBody())->near_earth_objects;

        foreach ($data as $date => $dayData) {
            foreach ($dayData as $neoData) {
                $neoData = [
                    'date' => $date,
                    'reference' => $neoData->neo_reference_id,
                    'name' => $neoData->name,
                    'speed' => $neoData->close_approach_data[0]->relative_velocity->kilometers_per_hour,
                    'is_hazardous' => $neoData->is_potentially_hazardous_asteroid,
                ];

                $neo = Neo::firstOrCreate($neoData);

                $this->log['count']++;

                if($neo->wasRecentlyCreated) {
                    $this->log['new']++;
                }
            }
        }
    }
}