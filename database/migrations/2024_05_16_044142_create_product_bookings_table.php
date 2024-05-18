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
        Schema::create('product_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created')->nullable();
            $table->string('file_url')->nullable();
            $table->string('grade')->nullable();
            $table->string('hr_cr_emp_id')->nullable();
            $table->string('hr_cr_emp_entry_by_id')->nullable();
            $table->string('hr_cr_emp_update_by_id')->nullable();
            $table->string('is_fwd')->nullable();
            $table->string('modified')->nullable();
            $table->string('quantity')->nullable();
            $table->string('product_id')->nullable();
            $table->string('type')->nullable();
            $table->string('price')->nullable();
            $table->string('is_approved')->nullable();
            $table->timestamp('approval_time')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('remarks')->nullable();
            $table->string('upload_id')->nullable();
            $table->string('delivery_location')->nullable();
            $table->string('mob_code')->nullable();
            $table->string('location')->nullable();
            $table->string('p_type')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('is_eligible')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bookings');
    }
};
