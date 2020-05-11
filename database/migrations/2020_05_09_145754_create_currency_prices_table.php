<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('currency_id')->unsigned()->index();
            $table->decimal('value', 10, 4);
            $table->date('date')->index();
            $table->integer('nominal')->default(1);
            $table->timestamps();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_prices');
    }
}
