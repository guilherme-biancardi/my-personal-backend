<?php

use App\Models\DeviceModel;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('storage_measure', ['GB', 'TB']);
            $table->integer('storage');
            $table->integer('quantity')->default(0);
            $table->foreignIdFor(DeviceModel::class);
            $table->foreignIdFor(Product::class);

            $table->foreign('device_model_id')->references('id')->on('device_models');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
