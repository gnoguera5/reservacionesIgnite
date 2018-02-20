<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->string('key_css')->nullable();
            $table->string('class')->nullable();
            $table->string('circle_number')->nullable();
            $table->string('numero_equipo',2)->nullable();
            $table->string('nombre_equipo')->nullable();
            $table->string('tipo_equipo',1)->nullable()->default('1'); //1= si es computadora 2=consola
            $table->string('status',1)->nullable()->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipments');
    }
}
