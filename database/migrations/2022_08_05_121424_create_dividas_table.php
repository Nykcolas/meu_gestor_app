<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDividasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dividas', function (Blueprint $table) {
            $table->id();
            $table->string("nome_credor", 50);
            $table->string("descricao_divida", 200);
            $table->boolean("divida_finalizada");
            $table->string("observacao_divida", 50)->nullable();
            $table->decimal('valor_inicial');
            $table->decimal('valor_pago');
            $table->decimal('valor_atual');
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
        Schema::dropIfExists('dividas');
    }
}
