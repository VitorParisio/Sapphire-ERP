<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinatariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinatarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('rg_ie')->unique()->default("0");
            $table->string('cpf_cnpj')->unique()->default("0");
            $table->string('cep');
            $table->string('rua');
            $table->string('numero');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('complemento')->nullable();
            $table->string('uf')->default('PE');
            $table->string('cibge')->default('2610707');
            $table->string('cPais')->default('1058');
            $table->string('xPais')->default('Brasil');
            $table->string('xCpl')->nullable();
            $table->string('fone')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinatarios');
    }
}
