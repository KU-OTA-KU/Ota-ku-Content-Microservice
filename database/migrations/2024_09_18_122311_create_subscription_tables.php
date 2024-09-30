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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('subscriptions_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriptions_id')->constrained('subscriptions')->onDelete('cascade');
            $table->string('locale', 5);
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->float('price')->nullable();
            $table->json('benefits')->nullable();
            $table->timestamps();

            $table->unique(['subscriptions_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions_translations');
        Schema::dropIfExists('subscriptions');
    }
};
