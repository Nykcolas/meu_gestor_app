<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContasDeleteColunmValorPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas', function (Blueprint $table) {
            $table->dropColumn("valor_pago");
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
            $table->decimal("valor_pago")->after("data_inicio_conta")->nullable();
        });
    }
}
