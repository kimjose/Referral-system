<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('community_health_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chu_id')->constrained('community_health_units')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('role')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['chu_id', 'phone']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('community_health_workers');
    }
}; 