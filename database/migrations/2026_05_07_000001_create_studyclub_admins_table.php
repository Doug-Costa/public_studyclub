<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: studyclub_admins
 * Tabela para controlar permissões de administrador do Study Club
 * independente do sistema de admin externo
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studyclub_admins', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // Email do usuário DentalGo
            $table->string('name'); // Nome do administrador
            $table->boolean('is_active')->default(true);
            $table->string('role')->default('editor'); // admin, editor
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studyclub_admins');
    }
};
