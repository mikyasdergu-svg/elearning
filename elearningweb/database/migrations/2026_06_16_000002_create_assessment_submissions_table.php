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
        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_assessment_id')->constrained('lesson_assessments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('submission_type', 50);
            $table->text('response')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['submitted', 'graded', 'needs_revision'])->default('submitted');
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->unique(['lesson_assessment_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_submissions');
    }
};
