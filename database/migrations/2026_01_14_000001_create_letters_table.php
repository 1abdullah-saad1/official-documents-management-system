<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();

            // نوع الكتاب الوارد
            $table->enum('incoming_kind', ['external', 'internal', 'memo', 'personal_request', 'outgoing'])->index();

            // سري/غير سري
            $table->boolean('is_confidential')->default(false)->index();

            // الجهة (نفس القائمة) - قد تكون NULL في الطلبات الشخصية
            $table->foreignId('from_party_id')->nullable()->constrained('parties')->nullOnDelete();

            // صاحب الطلب (للطلبات الشخصية فقط)
            $table->string('requester_name')->nullable();

            // رقم/تاريخ الوثيقة (يسمحان بـ NULL للمذكرات/الطلبات)
            $table->string('book_no')->nullable()->index();
            $table->date('book_date')->nullable()->index();

            // الموضوع (≤ 20 كلمة في التحقق لاحقاً)
            $table->string('subject', 512);

            // كلمات/نصوص دلالية طويلة
            $table->longText('keywords')->nullable();

            // رقم/تاريخ الواردة (اختياريان)
            $table->string('incoming_no')->nullable()->index();
            $table->date('incoming_date')->nullable()->index();

            // حالة الإحالة الصادرة
            $table->enum('out_going_status', ['submited', 'submited_inhand', 'returned'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
