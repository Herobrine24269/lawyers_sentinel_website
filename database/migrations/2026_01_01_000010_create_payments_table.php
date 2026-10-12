<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->string('payment_method'); // 'easypaisa', 'jazzcash', 'bank_transfer'
            $table->string('transaction_id');
            $table->string('sender_name');
            $table->string('sender_account');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('$');
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->string('receipt_number')->nullable()->unique();
            $table->timestamps();

            $table->index('status');
            $table->index('payment_method');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
