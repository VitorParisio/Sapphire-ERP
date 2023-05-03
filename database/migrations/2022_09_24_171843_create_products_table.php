<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('nome');
            $table->string('ncm');
            $table->decimal('preco_compra', 10,2);
            $table->decimal('preco_venda', 10,2);
            $table->decimal('preco_minimo', 10,2);
            $table->integer('qtd_compra');
            $table->integer('estoque');
            $table->integer('estoque_minimo');
            $table->string('ucom')->default('UNID');
            $table->string('utrib')->default('UNID');
            $table->string('qtrib');
            $table->decimal('vuntrib', 10,2);
            $table->string('extipi')->nullable();
            $table->tinyInteger('indTot')->default(1);
            $table->tinyInteger('icms_orig')->default(0);
            $table->tinyInteger('icms_csosn')->default(102);
            $table->tinyInteger('pis_cst')->default(99);
            $table->tinyInteger('pis_qbcprod')->default(0);
            $table->tinyInteger('pis_valiqprod')->default(0);
            $table->tinyInteger('cofins_cst')->default(99);
            $table->tinyInteger('cofins_qbcprod')->default(0);
            $table->tinyInteger('cofins_valiqprod')->default(0);
            $table->decimal('vpis', 10,2)->default(0.00);
            $table->decimal('vcofins', 10,2)->default(0.00);
            $table->decimal('pcredsn', 10,2)->default(0.00);
            $table->decimal('vcredicmssn', 10,2)->default(0.00);
            $table->string('cfop')->default('5101');
            $table->date('validade')->nullable();
            $table->string('img')->nullable();
            $table->string('cod_barra')->nullable()->unique()->default('SEM GTIN');
            $table->string('ceantrib')->nullable()->unique()->default('SEM GTIN');
            $table->string('descricao')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('products');
    }
}
