<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cellphone\StoreCellphoneRequest;
use App\Http\Resources\CellphoneResource;
use App\Models\Cellphone;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CellphoneController extends Controller
{
    use ResponseTrait;

    public function index(){
        $cellphones = Cellphone::all();
        return new CellphoneResource($cellphones);
    }

    public function store(StoreCellphoneRequest $request)
    {
        $cellphone = Cellphone::create($request->validated());
        $message = $cellphone->model .  " foi cadastrado com sucesso!";

        return $this->setResponseWithResource($cellphone, $message, 201);
    }
}
