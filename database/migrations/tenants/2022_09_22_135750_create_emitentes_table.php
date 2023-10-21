<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmitentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emitentes', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj')->unique();
            $table->string('ie')->nullable()->unique();
            $table->string('im')->nullable();
            $table->string('cnae')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('nome_fantasia');
            $table->string('cep')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->default('PE');
            $table->string('cuf')->default('26');
            $table->string('cibge')->default('2610707');
            $table->string('telefone')->nullable();
            $table->text('certificado_a1')->nullable();
            $table->string('senha_certificado')->nullable();
            $table->string('host_email')->nullable();
            $table->string('email')->nullable();;
            $table->string('senha_email')->nullable();
            $table->string('tokenIBPT')->nullable();
            $table->string('csc')->nullable();
            $table->string('csc_id')->nullable();
            $table->string('crt')->default(1);
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
        Schema::dropIfExists('emitentes');
    }
}
