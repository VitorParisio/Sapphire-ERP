<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emitente_id')->nullable();
            $table->unsignedBigInteger('destinatario_id')->nullable();
            $table->tinyInteger('status_id')->nullable();
            $table->integer('nro_nfe')->nullable();
            $table->string('serie_nfe')->nullable();
            $table->string('dhRecbto')->nullable();
            $table->string('xMotivo')->nullable();
            $table->string('xJust')->nullable();
            $table->string('chave_nfe')->nullable();
            $table->integer('finNFe')->default(1);
            $table->text('path_xml')->nullable();
            $table->text('path_file')->nullable();
            $table->bigInteger('nProt')->nullable();
            $table->string('digVal')->nullable();
            $table->integer('cStat')->nullable();
            $table->string('xEvento')->nullable();
            $table->tinyInteger('nSeqEvento')->nullable();
            $table->string('dataRecibo')->nullable();
            $table->string('horaRecibo')->nullable();
            $table->string('dhRegEvento')->nullable();
            $table->tinyInteger('modFrete')->default(9);
            $table->integer('ambiente')->default(2);
            $table->string('tPag', 2)->nullable();
            $table->decimal('vPag', 10,2)->nullable();
            $table->decimal('vTroco', 10,2)->nullable();
            $table->foreign('emitente_id')->references('id')->on('emitentes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('destinatario_id')->references('id')->on('destinatarios')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('nves');
    }
}
