<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table): void {
            $table->uuid('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->boolean('can_preview')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->enum('status', ['processing', 'ready', 'failed'])->default('processing');
            $table->enum('video_driver', ['vimeo', 'file_system'])->default('file_system');
            $table->string('video_source');
            $table->boolean('is_published')->default(false);
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
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
