<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lawyer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->string('meeting_place')->default('In-Person Office, Virtual Video Call');
            $table->json('specializations')->nullable();
            $table->integer('experience_years')->default(1);
            $table->string('license_number')->nullable();
            $table->decimal('hourly_fee', 10, 2)->default(150.00);
            $table->text('bio')->nullable();
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawyer_profiles');
    }
};
