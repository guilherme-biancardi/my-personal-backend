<?php

namespace App\Traits;

use Illuminate\Http\Resources\Json\JsonResource;

trait ResponseTrait
{
    public function setResponse($message = '', $status = 200)
    {
        return response()->json(['message' => $message], $status);
    }

    public function setResponseWithResource(JsonResource $resource, $message = '', $status = 200)
    {
        $data = ['data' => $resource, 'message' => $message];

        $dataFiltered = array_filter($data, function ($value) {
            return !empty($value);
        });

        return response()->json($dataFiltered, $status);
    }
}
