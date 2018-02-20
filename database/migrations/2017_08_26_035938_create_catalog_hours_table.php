<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('dias');
            $table->text('horario')->nullable();
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
        Schema::table('catalog_hours', function (Blueprint $table) {
            //
        });
    }
}
