<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Add FHIR-specific columns
            $table->string('resource_type')->default('ServiceRequest');
            $table->string('intent')->default('order');
            $table->json('category')->nullable();
            $table->json('code')->nullable();
            $table->json('encounter')->nullable();
            $table->json('requester')->nullable();
            $table->json('performer')->nullable();
            $table->json('reason_code')->nullable();
            $table->json('reason_reference')->nullable();
            $table->json('supporting_info')->nullable();
            $table->json('note')->nullable();
            $table->json('patient_instruction')->nullable();
            $table->json('relevant_history')->nullable();
            
            // Rename existing columns to match FHIR
            $table->renameColumn('status', 'fhir_status');
            $table->renameColumn('priorityLevel', 'priority');
        });
    }

    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Remove FHIR-specific columns
            $table->dropColumn([
                'resource_type',
                'intent',
                'category',
                'code',
                'encounter',
                'requester',
                'performer',
                'reason_code',
                'reason_reference',
                'supporting_info',
                'note',
                'patient_instruction',
                'relevant_history'
            ]);
            
            // Rename columns back to original
            $table->renameColumn('fhir_status', 'status');
            $table->renameColumn('priority', 'priorityLevel');
        });
    }
}; 