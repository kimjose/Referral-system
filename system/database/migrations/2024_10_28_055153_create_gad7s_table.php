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
        Schema::create('gad7s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->date('assessment_date');
            $table->integer('anxious_nervous');
            $table->integer('uncontrollable_worrying');
            $table->integer('worrying_too_much');
            $table->integer('trouble_relaxing');
            $table->integer('restless');
            $table->integer('irritable');
            $table->integer('afraid');
            $table->timestamps();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gad7s');
    }
};
