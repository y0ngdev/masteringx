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
        Schema::create('lessons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('vimeo_id')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->boolean('is_pre-viewable')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->enum('status', ['processing', 'ready', 'failed'])->default('processing');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
