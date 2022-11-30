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
        Schema::create('speiseplan_files', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('wochen_id')->unsigned(); 
            $table->foreign('wochen_id')->references('id')->on('wochen'); 
            $table->string('file_path');
            $table->string('file_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speiseplan_files');
    }
};
