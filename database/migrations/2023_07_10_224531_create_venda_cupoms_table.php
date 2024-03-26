<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendaCupomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_cupoms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cupom_id');
            $table->unsignedBigInteger('caixa_id');
            $table->decimal('total_venda', 10,2)->default(0.00);
            $table->decimal('valor_recebido', 10,2)->default(0.00);
            $table->decimal('desconto', 10,2)->default(0.00);
            $table->decimal('troco', 10,2)->default(0.00);
            $table->string('forma_pagamento');
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
        Schema::dropIfExists('venda_cupoms');
    }
}
