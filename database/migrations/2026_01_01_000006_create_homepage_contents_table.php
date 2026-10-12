<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->default('Find & Book Trusted Legal Representation');
            $table->text('hero_subtitle')->default('Search top-rated lawyers by specialization and location. Schedule in-person or virtual consultation slots instantly.');
            $table->string('announcement_banner')->default('Welcome to Juris Sentinel - Emergency legal guidance available 24/7.');
            $table->integer('total_lawyers_count')->default(250);
            $table->integer('total_cases_count')->default(4800);
            $table->string('satisfaction_rate')->default('98.5%');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_contents');
    }
};
