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
        // Tabela de Edições
        Schema::create('studyclub_editions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('publish_date');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index('number');
            $table->index('publish_date');
            $table->index('status');
        });

        // Tabela de Itens
        Schema::create('studyclub_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edition_id')
                ->constrained('studyclub_editions')
                ->onDelete('cascade');
            $table->string('category');
            $table->string('type'); // article, interview, special
            $table->string('type_label');
            $table->string('author');
            $table->string('title');
            $table->text('resumo');
            $table->text('achados');
            $table->text('implicacoes');
            $table->string('image_path')->nullable();
            $table->string('external_url');
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('comments')->default(0);
            $table->string('icon')->default('bi-journal-text');
            $table->timestamps();

            $table->index('edition_id');
            $table->index('category');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studyclub_items');
        Schema::dropIfExists('studyclub_editions');
    }
};
