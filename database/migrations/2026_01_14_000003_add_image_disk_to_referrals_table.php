<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->string('image_disk')->default('s3')->after('image_path');
        });

        DB::table('referrals')
            ->whereNotNull('image_path')
            ->update(['image_disk' => 's3']);
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn('image_disk');
        });
    }
};
