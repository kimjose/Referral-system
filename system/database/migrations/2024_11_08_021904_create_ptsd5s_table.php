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
        Schema::create('ptsd5s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->date('assessment_date');
            $table->enum('experienced_trauma', ['Yes', 'No']);
            $table->enum('nightmares', ['', 'Yes', 'No'])->default('');
            $table->enum('hard_not_to_think', ['', 'Yes', 'No'])->default('');
            $table->enum('on_guard', ['', 'Yes', 'No'])->default('');
            $table->enum('felt_numb', ['', 'Yes', 'No'])->default('');
            $table->enum('felt_guilty', ['', 'Yes', 'No'])->default('');
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
        Schema::dropIfExists('ptsd7s');
    }
};
