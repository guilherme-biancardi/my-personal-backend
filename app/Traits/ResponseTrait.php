<?php

namespace App\Traits;

trait ResponseTrait
{
    public function setResponse($message, $status = 200)
    {
        return response()->json(['message' => $message], $status);
    }

    public function setResponseWithResource($resource, $message, $status = 200)
    {
        return response()->json([$resource, 'message' => $message], $status);
    }
}
