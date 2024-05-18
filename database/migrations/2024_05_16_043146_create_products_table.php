<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->timestamp('created')->nullable();
            $table->string('created_by')->nullable();
            $table->string('description')->nullable();
            $table->string('ele_mot_cell')->nullable();
            $table->string('lft')->nullable();
            $table->string('model')->nullable();
            $table->string('product_id')->nullable();
            $table->string('unit_meas_lookup_code')->nullable();
            $table->string('item_model')->unique();
            $table->string('product_source_id')->nullable();
            $table->string('segment1')->nullable();
            $table->string('segment2')->nullable();
            $table->string('segment3')->nullable();
            $table->string('segment4')->nullable();
            $table->string('item_code')->nullable();
            $table->string('is_sl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
