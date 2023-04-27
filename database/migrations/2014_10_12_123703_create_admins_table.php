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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('locale')->default('hu');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('status')->default('registered');
            $table->string('password')->nullable();
            $table->rememberToken();
			$table->unsignedInteger('login_attempts')->default(0);
            $table->text('profile_photo_path')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->dateTime('blocked_at')->nullable();
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
        Schema::dropIfExists('admins');
    }
};
