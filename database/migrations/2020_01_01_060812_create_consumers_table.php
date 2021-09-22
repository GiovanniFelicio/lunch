<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumers', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 191);
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('nis', 12);
            $table->string('sexo', 2);
            $table->string('nascimento', 15);
            $table->string('email')->nullable();
            $table->integer('categoria_id');
            $table->string('cep');
            $table->string('complement')->nullable();
            $table->string('number');
            $table->string('dependentes');
            $table->string('numdepen')->nullable();
            $table->string('renda');
            $table->string('saldo')->default(0);
            $table->string('status')->default(1);
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
        Schema::dropIfExists('consumers');
    }
}
