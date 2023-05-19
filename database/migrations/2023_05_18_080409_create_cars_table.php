<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners');
            $table->foreignId('created_by')->constrained('admins');
            $table->string('brand');
            $table->string('type');
            $table->string('license_plate');
            $table->string('mileage');
            $table->string('chassis_number');
            $table->string('motor_number');
            $table->string('vintage');
            $table->string('ti_validity');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
