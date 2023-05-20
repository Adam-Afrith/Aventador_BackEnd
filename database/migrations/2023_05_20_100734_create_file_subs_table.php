<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_subs', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('mainid')->unsigned();
            $table->foreign('mainid')->references('id')->on('files')->OnDelete('cascade')->onUpdate('NO ACTION');
            $table->string('originalfilename');
            $table->string('file_type');
            $table->string('file_size');
            $table->string('hasfilename');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_subs');
    }
};
