<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Adicionar controle de comentários nos itens
        Schema::table('studyclub_items', function (Blueprint $table) {
            if (!Schema::hasColumn('studyclub_items', 'comments_enabled')) {
                $table->boolean('comments_enabled')->default(true);
            }
        });

        // 2. Criar tabela de likes legítimos
        Schema::create('studyclub_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('studyclub_items')->onDelete('cascade');
            $table->string('user_id'); // Usamos string para compatibilidade com IDs do DentalGo (pode ser int ou hash)
            $table->timestamps();
            
            $table->unique(['item_id', 'user_id']);
        });

        // 3. Criar tabela de comentários
        Schema::create('studyclub_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('studyclub_items')->onDelete('cascade');
            $table->string('user_id');
            $table->string('user_name')->nullable();
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // 4. Limpar e reordenar dados existentes (Regra do Usuário)
        $items = DB::table('studyclub_items')->get();
        foreach ($items as $item) {
            DB::table('studyclub_items')
                ->where('id', $item->id)
                ->update([
                    'likes' => rand(5, 95), // Aleatório abaixo de 100
                    'comments' => 0         // Zerar comentários
                ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('studyclub_comments');
        Schema::dropIfExists('studyclub_likes');
        Schema::table('studyclub_items', function (Blueprint $table) {
            $table->dropColumn('comments_enabled');
        });
    }
};
