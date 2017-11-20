<?php

namespace App\Exceptions;

use Exception;

class ApiErrorException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return response()->json([
            'error' => 'Something wrong with API',
        ], 404);
    }
}
