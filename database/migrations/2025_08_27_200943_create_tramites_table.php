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
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('subject');
            $table->enum('origen', ['Internal', 'External']);
            $table->boolean('representation')->default(false);
            $table->string('full_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('dni')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('ruc')->nullable();
            $table->string('empresa')->nullable();
            $table->unsignedBigInteger('document_type_id');
            $table->unsignedBigInteger('area_oreigen_id');
            //  $table->unsignedBigInteger('gestion_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('folio');
            // $table->date('receip_date');
            $table->date('reception_date')->nullable();
            $table->string('file_path');
            $table->string('condition');
            $table->enum('status', [
                'draft',      // creado pero no enviado
                'received',   // recibido en mesa de partes
                'in_process', // en proceso en un área
                'derived',    // derivado a otra área
                'returned',   // devuelto al área anterior
                'archived',    // finalizado/archivado
            ])->default('draft');
            $table->timestamps();
            $table->foreign('document_type_id')
                ->references('id')
                ->on('type_documents')
                ->onDelete('cascade');
            $table->foreign('area_oreigen_id')
                ->references('id')
                ->on('areas')
                ->onDelete('cascade');
            // $table->foreign('gestion_id')->references('id')->on('gestions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); // Permitir nulo si el usuario es eliminado

            // Índices para consultas frecuentes
            $table->index('status');
            $table->index('origen');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
