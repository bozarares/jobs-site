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
        Schema::create('user_descriptions', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('description');
            $table->string('locale', 2)->index();
            $table->timestamps();

            $table->unique(['user_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_descriptions');
    }
};
