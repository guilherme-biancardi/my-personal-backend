<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceModel\CreateDeviceModelRequest;
use App\Http\Resources\DeviceModelCollection;
use App\Http\Resources\DeviceModelResource;
use App\Http\Resources\DeviceModelWithDevicesResource;
use App\Models\Device;
use App\Models\DeviceModel;
use Illuminate\Http\Request;

class DeviceModelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $device_models = DeviceModel::where('model', 'LIKE', "%$search%")
            ->orWhere('brand', 'LIKE', "%$search%")
            ->get();
        return $this->setResponseWithResource(DeviceModelResource::collection($device_models));
    }

    public function store(CreateDeviceModelRequest $request)
    {
        $device_model = DeviceModel::create($request->validated());

        return $this->setResponse(__('messages.device_model.created', ['model' => $device_model->model]), 201);
    }

    public function remove(Request $request)
    {
        $device_model = DeviceModel::find($request->input('id'));

        if ($device_model) {

            if ($device_model->containsDevices()) {
                return $this->setResponse(__('messages.device_model.contains_devices'), 400);
            }

            $device_model->delete();

            return $this->setResponse(__('messages.device_model.deleted'));
        }

        return $this->setResponse(__('messages.device_model.not_found_on_delete'), 400);
    }

    public function removeDevices(Request $request)
    {
        $device_model = DeviceModel::find($request->input('id'));

        if ($device_model) {
            $device_model->devices()->delete();

            return $this->setResponse(__('messages.device_model.devices_deleted'));
        }

        return $this->setResponse(__('messages.device_model.not_found_on_delete'), 400);
    }
}
