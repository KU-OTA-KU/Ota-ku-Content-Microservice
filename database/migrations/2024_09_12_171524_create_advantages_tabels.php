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
        Schema::create('advantages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('advantages_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advantages_id')->constrained('advantages')->onDelete('cascade');
            $table->string('locale', 5);
            $table->text('title')->nullable();
            $table->string('icon', 20)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->unique(['advantages_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advantages_translations');
        Schema::dropIfExists('advantages');
    }
};
