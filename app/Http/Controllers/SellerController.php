<?php

namespace App\Http\Controllers;

use App\Http\Requests\Seller\CreateSellerRequest;
use App\Http\Requests\Seller\EditSellerRequest;
use App\Http\Resources\SellerCollection;
use App\Http\Resources\SellerResource;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $sellers = Seller::all();
        return $this->setResponseWithResource(new SellerCollection($sellers));
    }

    public function store(CreateSellerRequest $request)
    {
        $seller = Seller::create($request->validated());

        return $this->setResponse(__('messages.seller.created', ['name' => $seller->name]), 201);
    }

    public function remove(Request $request)
    {
        $seller = Seller::withoutTrashed()->find($request->input('id'));

        if ($seller) {
            $seller->delete();

            return $this->setResponse(__('messages.seller.deleted'));
        }

        return $this->setResponse(__('messages.seller.not_found_on_delete'), 400);
    }

    public function restore(Request $request)
    {
        $seller = Seller::withTrashed()->find($request->input('id'));

        if($seller){
            $seller->restore();
            return $this->setResponse(__('messages.seller.restored'));
        }

        return $this->setResponse(__('messages.seller.not_found_on_restore'), 400);
    }

    public function update(EditSellerRequest $request)
    {
        $seller = Seller::withoutTrashed()->where([
            'id' => $request->input('id')
        ]);

        if ($seller) {
            $seller->update($request->validated());

            return $this->setResponse(__('messages.seller.edited'));
        }

        return $this->setResponse(__('messages.seller.not_found_on_delete'), 400);
    }
}
