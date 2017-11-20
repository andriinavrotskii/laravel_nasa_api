<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Database\QueryException;
use App\Model\Neo;
use App\Exceptions\ApiErrorException;

class NeoController extends Controller
{
    public function hazardous()
    {
        try {
            $data = Neo::where('is_hazardous', true)->get();
            return $this->returnResponse($data);

        } catch (QueryException $e) {
            throw new ApiErrorException();
        }
    }


    public function fastest()
    {
        $hazardous = Input::get('hazardous') == 'true' ? true : false;
        try {
            $data = Neo::where('is_hazardous', $hazardous)
                        ->orderBy('speed', 'desc')
                        ->first();
            return $this->returnResponse($data);

        } catch (QueryException $e) {
            throw new ApiErrorException();
        }
    }


    protected function returnResponse($data, $statusCode = 200)
    {
        if (!isset($data[0])) {
            $data = [$data];
        }

        return response()->json([
            'data' => $data,
            'count' => count($data),
        ], 200);
    }
}
