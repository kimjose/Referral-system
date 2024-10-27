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
        Schema::create('phq9_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->date('assessment_date');
            $table->integer('little_interest');
            $table->integer('feeling_down');
            $table->integer('trouble_sleeping');
            $table->integer('feeling_tired');
            $table->integer('poor_appetite');
            $table->integer('feeling_bad_about_self');
            $table->integer('trouble_concentrating');
            $table->integer('slow_or_fidgety');
            $table->integer('thoughts_of_self_hurt');
            $table->enum('difficult_caused', ['', 'Not difficult at all', 'Somewhat difficult', 'Vary difficult', 'Extremely difficult'])->default('');
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
        Schema::dropIfExists('phq9_assessments');
    }
};
