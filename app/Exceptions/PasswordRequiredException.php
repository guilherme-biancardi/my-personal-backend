<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordRequiredException extends Exception
{

    const PASSWORD_ERROR = 'passwordChangeRequired';

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json(["error" => self::PASSWORD_ERROR, "message" => $this->getMessage()], 401);
    }
}
