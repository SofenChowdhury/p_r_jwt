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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created')->nullable();
            $table->string('grade')->nullable();
            $table->string('hr_cr_emp_entry_by_id')->nullable();
            $table->string('hr_cr_emp_update_by_id')->nullable();
            $table->string('modified')->nullable();
            $table->string('quantity')->nullable();
            $table->string('booking_qty')->nullable();
            $table->string('type')->nullable();
            $table->string('product_id')->nullable();
            $table->string('latest_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
