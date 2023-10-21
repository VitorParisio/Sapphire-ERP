<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContaFechamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conta_fechamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caixa_id')->default(0);
            $table->string('forma_pagamento_fechamento')->nullable();
            $table->decimal('total_caixa_conta_fechamento', 10,2)->default(0.00);
            $table->decimal('total_caixa_informado', 10,2)->default(0.00);
            $table->string('diferenca_pagamento_fechamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conta_fechamentos');
    }
}
