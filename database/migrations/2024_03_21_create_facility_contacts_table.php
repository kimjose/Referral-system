<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facility_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->string('contact_type');
            $table->string('contact_value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['facility_id', 'contact_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility_contacts');
    }
}; 