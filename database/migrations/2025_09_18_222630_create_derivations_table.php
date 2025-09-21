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
        Schema::create('derivations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tramite_id');
            $table->unsignedBigInteger('from_area_id');
            $table->unsignedBigInteger('to_area_id');
            $table->unsignedBigInteger('user_id');
            $table->text('observations')->nullable();
            $table->enum('status', ['sent', 'received', 'returned'])->default('sent');
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('tramite_id')->references('id')->on('tramites')->onDelete('cascade');
            $table->foreign('from_area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('to_area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('derivations');
    }
};
