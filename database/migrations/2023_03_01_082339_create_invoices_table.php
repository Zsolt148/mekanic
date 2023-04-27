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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('admins');
            $table->string('name');
            $table->string('zip',6);
            $table->string('city');
            $table->string('address');
            $table->string('tax_number')->nullable();
            $table->string('communal_tax_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('admins');
            $table->foreignId('partner_id')->constrained('partners');
            $table->string('payment_mode', 10);
            $table->date('issue_date');
            $table->date('fulfillment_date');
            $table->date('expiration_date');
            $table->string('order_number');
            $table->float('net',12,2);
            $table->float('tax',12,2);
            $table->float('gross',12,2);
            $table->string('comment', 500)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('admins');
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('name');
            $table->float('unit_price',12,2);
            $table->string('quantity');
            $table->float('tax_percent',5,2);
            $table->float('net',12,2);
            $table->float('tax',12,2);
            $table->float('gross',12,2);
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
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('partners');
    }
};
