<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCellphoneRequest;
use App\Models\Cellphone;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CellphoneController extends Controller
{
    use ResponseTrait;

    public function store(StoreCellphoneRequest $request)
    {
        $cell = Cellphone::create($request->validated());
        $message = "O celular " . $cell->model . " foi cadastrado com sucesso!";

        return $this->setResponseWithResource($cell, $message, 201);
    }
}
