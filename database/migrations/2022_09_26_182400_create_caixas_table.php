<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaixasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caixas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nro_caixa_id')->nullable();
            $table->unsignedBigInteger('user_abertura_id')->nullable();
            $table->unsignedBigInteger('user_fechamento_id')->nullable();
            $table->date('data_abertura')->nullable();
            $table->date('data_fechamento')->nullable();
            $table->time('horario_abertura')->nullable();
            $table->time('horario_fechamento')->nullable();
            $table->decimal('valor_abertura', 10,2)->default(0.00);
            $table->decimal('valor_fechamento', 10,2)->default(0.00);
            $table->decimal('valor_vendido', 10,2)->default(0.00);
            $table->decimal('sangria', 10,2)->default(0.00);
            $table->decimal('suplemento', 10,2)->default(0.00);
            $table->decimal('total_caixa', 10,2)->default(0.00);
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caixas');
    }
}
