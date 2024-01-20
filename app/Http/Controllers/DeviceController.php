<?php

namespace App\Http\Controllers;

use App\Http\Requests\Device\CreateDeviceRequest;
use App\Http\Requests\Device\EditDeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceModel;
use App\Models\Product;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $devices = Device::whereHas('model', function ($query) use ($search) {
            return $query->where('model', 'LIKE', "%$search%")
                ->orWhere('brand', 'LIKE', "%$search%");
        })->get();
        
        return $this->setResponseWithResource(DeviceResource::collection($devices));
    }

    public function store(CreateDeviceRequest $request)
    {
        $device_model = DeviceModel::find($request->input('device_model_id'));

        if ($device_model) {
            $validated = $request->validated();

            $device = $device_model->devices()->where('storage', $validated['storage'])->first();

            if ($device) {
                return $this->setResponse(__('messages.device.exists'), 400);
            } else {
                $product = Product::create([
                    'product_type' => 'device'
                ]);

                $validated['product_id'] = $product->id;

                $device_model->devices()->create($validated);
            }

            return $this->setResponse(__('messages.device.created'), 201);
        }

        return $this->setResponse(__('messages.device_model.not_found_on_delete'), 400);
    }

    public function update(EditDeviceRequest $request)
    {
        $validated = $request->validated();
        $device_by_storage = Device::where('storage', $validated['storage'])
            ->where('id', '<>', $request->input('id'))
            ->first();

        if ($device_by_storage) {
            return $this->setResponse(__('messages.device.exists'), 400);
        }

        $device = Device::find($request->input('id'));

        if ($device) {
            $device->update($request->validated());

            return $this->setResponse(__('messages.device.updated'));
        }

        return $this->setResponse(__('messages.device.not_found'), 400);
    }

    public function remove(Request $request)
    {
        $device = Device::find($request->input('id'));

        if ($device) {
            $device->delete();

            return $this->setResponse(__('messages.device.deleted'));
        }

        return $this->setResponse(__('messages.device.not_found'), 400);
    }
}
