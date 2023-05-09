<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nfe_id');
            $table->decimal('total_venda', 10,2)->default(0.00);
            $table->decimal('valor_recebido', 10,2)->default(0.00);
            $table->decimal('desconto', 10,2)->default(0.00);
            $table->decimal('troco', 10,2)->default(0.00);
            $table->string('forma_pagamento');
            $table->foreign('nfe_id')->references('id')->on('nves')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('vendas');
    }
}
