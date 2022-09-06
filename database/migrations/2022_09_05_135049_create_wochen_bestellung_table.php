<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wochen_bestellung', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('wochen_id')->unsigned(); 
            $table->foreign('wochen_id')->references('id')->on('wochen');
            $table->BigInteger('abteilung_id')->unsigned(); 
            $table->foreign('abteilung_id')->references('id')->on('abteilungen'); 
            $table->BigInteger('massen_bestellung_id')->unsigned(); 
            $table->foreign('massen_bestellung_id')->references('id')->on('massen_bestellung'); 
            $table->integer('anzahl_essen_normal');
            $table->integer('montag_normal'); 
            $table->integer('dienstag_normal'); 
            $table->integer('mittwoch_normal'); 
            $table->integer('donnerstag_normal'); 
            $table->integer('freitag_normal');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wochen_bestellung');
    }
};
