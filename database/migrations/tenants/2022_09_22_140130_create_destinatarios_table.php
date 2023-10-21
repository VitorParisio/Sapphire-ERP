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
            $table->string('rg_ie')->nullable()->unique()->default("0");
            $table->string('cpf_cnpj')->nullable()->unique()->default("0");
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('complemento')->nullable();
            $table->string('uf')->default('PE');
            $table->string('cibge')->nullable()->default('2610707');
            $table->string('cPais')->nullable()->default('1058');
            $table->string('xPais')->nullable()->default('Brasil');
            $table->string('xCpl')->nullable();
            $table->string('fone')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('destinatarios');
    }
}
