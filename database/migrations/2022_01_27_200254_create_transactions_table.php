<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->dateTime('paid_at')->nullable();
            $table->foreignId('content_id')->constrained('contents');
            $table->string('order_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('charges_id')->nullable();
            $table->string('code')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('amount')->nullable();
            $table->tinyInteger('customer_delinquent')->default(0);
            $table->string('status')->nullable();
            $table->tinyInteger('closed')->default(0);
            $table->string('charge_status')->nullable();
            $table->string('document_number')->nullable();
            $table->date('due_date')->nullable();
            $table->string('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
