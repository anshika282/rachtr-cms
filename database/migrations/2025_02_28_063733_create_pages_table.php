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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('pages')->onDelete('cascade');
            $table->unsignedBigInteger('header_id');
            $table->foreign('header_id')->references('id')->on('common_components')->onDelete('cascade');
            $table->unsignedBigInteger('footer_id');
            $table->foreign('footer_id')->references('id')->on('common_components')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
