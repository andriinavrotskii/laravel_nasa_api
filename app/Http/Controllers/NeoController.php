<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Model\Neo;

class NeoController extends Controller
{
    public function hazardous()
    {
        $data = Neo::where('is_hazardous', true)->get(); 
        
        return response()->json($data, 200);
    }    


    public function fastest()
    {
        $hazardous = Input::get('hazardous') == 'true' ? true : false;

        $data = Neo::where('is_hazardous', $hazardous)
                    ->orderBy('speed', 'desc')
                    ->first(); 

        return response()->json($data, 200);
    }
}
