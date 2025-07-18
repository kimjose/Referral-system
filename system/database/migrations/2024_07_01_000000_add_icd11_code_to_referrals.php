<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->string('icd11_code')->nullable()->after('diagnosis');
        });
    }
    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn('icd11_code');
        });
    }
}; 