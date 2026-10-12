<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lawyer_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained('users')->onDelete('cascade');
            $table->string('day_of_week'); // Monday, Tuesday, Wednesday, etc.
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->default('17:00:00');
            $table->integer('slot_duration_minutes')->default(30);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lawyer_schedules');
    }
};
