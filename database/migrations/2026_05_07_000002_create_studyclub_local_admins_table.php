<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Sistema de login LOCAL e INDEPENDENTE para admin do Study Club
 * Não depende do login do DentalGo - usuário/senha próprios
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studyclub_local_admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // Login único (ex: admin_studyclub)
            $table->string('password'); // Hash bcrypt
            $table->string('name'); // Nome do administrador
            $table->string('email')->nullable(); // Email opcional para notificações
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->integer('login_attempts')->default(0); // Contador de tentativas falhas
            $table->timestamp('locked_until')->nullable(); // Bloqueio temporário
            $table->timestamps();

            $table->index('username');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studyclub_local_admins');
    }
};
