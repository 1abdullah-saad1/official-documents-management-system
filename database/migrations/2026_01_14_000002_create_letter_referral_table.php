<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_referral', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seq')->nullable();
            $table->foreignId('referral_id')->nullable()->constrained('referrals')->cascadeOnDelete();
            $table->foreignId('letter_id')->constrained('letters')->cascadeOnDelete();
            $table->foreignId('to_party_id')->nullable()->constrained('parties')->nullOnDelete();
            $table->enum('through', ['central_mail', 'in_hand', 'direct'])->default('central_mail');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_referral');
    }
};
