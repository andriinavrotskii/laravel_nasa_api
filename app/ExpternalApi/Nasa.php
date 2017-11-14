<?php

namespace App\ExternalApi;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Support\Carbon;
use App\Exceptions\NasaException;


class Nasa
{
    protected $periodDays = 2; //days before, except tooday

    public function getNeo()
    {
        $url = 'https://api.nasa.gov/neo/rest/v1/feed?'
                . 'start_date=' . (new Carbon('tomorrow'))->subDays($this->periodDays)->toDateString()
                . '&end_date=' . (new Carbon('tomorrow'))->toDateString()
                . '&detailed=false'
                . '&api_key=' . env("NASA_API_KEY");

        return $this->checkAndReturnResponse($this->apiRequest($url));

        //return json_decode((string) $response->getBody())->near_earth_objects;
    }


    protected function apiRequest($url)
    {
        try {
            $client = new Guzzle;
            $response = $client->request('GET', $url);

            if ($response->getStatusCode() !== 200) {
                throw new NasaException("NASA API error: Status code " . $response->getStatusCode());    
            }

            return $response;

        } catch (RequestException $e) {
            throw new NasaException("NASA API error: " . $e->getMessage());
        }
    }


    protected function checkAndReturnResponse(GuzzleResponse $response)
    {
        if (!$response->getBody()) {
            throw new NasaException("NASA API error: Response body is empty");  
        }

        $response = json_decode((string) $response->getBody());

        if (!property_exists($response, "near_earth_objects")) {
            throw new NasaException("NASA API error: near_earth_objects not found in response");  
        }

        $response = (array) $response->near_earth_objects;

        if (count($response) !== $this->periodDays+1) {
            throw new NasaException("NASA API error: near_earth_objects not valid");  
        }

        return $response;

    }



}