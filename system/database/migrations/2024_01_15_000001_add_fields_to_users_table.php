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
        Schema::table('users', function (Blueprint $table) {
            // Add status field for user management
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('facility_id');
            
            // Add contact information
            $table->string('phone')->nullable()->after('status');
            $table->text('address')->nullable()->after('phone');
            
            // Add profile fields
            $table->string('profile_picture')->nullable()->after('address');
            $table->text('bio')->nullable()->after('profile_picture');
            
            // Add security fields
            $table->timestamp('last_login_at')->nullable()->after('bio');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->boolean('force_password_change')->default(false)->after('last_login_ip');
            
            // Add audit fields
            $table->unsignedBigInteger('created_by')->nullable()->after('force_password_change');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            
            // Add indexes
            $table->index('status');
            $table->index('last_login_at');
            
            // Add foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by', 'updated_by']);
            $table->dropIndex(['status', 'last_login_at']);
            $table->dropColumn([
                'status',
                'phone',
                'address',
                'profile_picture',
                'bio',
                'last_login_at',
                'last_login_ip',
                'force_password_change',
                'created_by',
                'updated_by'
            ]);
        });
    }
}; 