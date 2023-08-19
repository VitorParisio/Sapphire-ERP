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
            $table->unsignedBigInteger('venda_cupom_id');
            $table->decimal('total_caixa_conta_fechamento', 10,2)->default(0.00);
           
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
