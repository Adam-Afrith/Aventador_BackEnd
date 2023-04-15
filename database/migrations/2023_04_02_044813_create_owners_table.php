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
        Schema::create('owners', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('owner_name');
            $table->integer('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->restrictOnDelete()->onUpdate('NO ACTION');
            $table->integer('bike_id');
            $table->foreign('bike_id')->references('id')->on('bikes')->restrictOnDelete()->onUpdate('NO ACTION');
            $table->decimal('price',10,2);
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
