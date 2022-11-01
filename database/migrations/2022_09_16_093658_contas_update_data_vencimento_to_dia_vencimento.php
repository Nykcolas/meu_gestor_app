<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContasUpdateDataVencimentoToDiaVencimento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas', function (Blueprint $table) {
            $table->integer('data_vencimento')->change();
            $table->renameColumn("data_vencimento", "dia_vencimento");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas', function (Blueprint $table) {
            $table->renameColumn("dia_vencimento", "data_vencimento");
            $table->date("dia_vencimento")->change();
        });
    }
}
