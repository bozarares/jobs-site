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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('code');
            $table->string('phone_number');
            $table->string('email');
            $table->string('country');
            $table->string('state');
            $table->string('town');
            $table->string('address');
            $table->string('logo');
            $table->text('description');
            $table->unsignedBigInteger('owner');
            $table
                ->foreign('owner')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
