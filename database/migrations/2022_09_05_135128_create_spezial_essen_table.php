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
        Schema::create('spezial_essen', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('essen_id')->unsigned(); 
            $table->foreign('essen_id')->references('id')->on('essen'); 
            $table->BigInteger('teilnehmer_id')->unsigned(); 
            $table->foreign('teilnehmer_id')->references('id')->on('teilnehmer'); 
            $table->BigInteger('wochen_bestellung_id')->unsigned(); 
            $table->foreign('wochen_bestellung_id')->references('id')->on('wochen_bestellung'); 
            $table->BigInteger('wochentag_id')->unsigned(); 
            $table->foreign('wochentag_id')->references('id')->on('wochentag'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spezial_essen');
    }
};
