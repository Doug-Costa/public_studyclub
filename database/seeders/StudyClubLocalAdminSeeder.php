<?php

namespace Database\Seeders;

use App\Models\StudyClubLocalAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder: StudyClubLocalAdminSeeder
 * Cria o primeiro administrador LOCAL do Study Club
 * 
 * Acesso padrão:
 * - Usuário: admin
 * - Senha: studyclub2026
 * 
 * IMPORTANTE: Altere a senha após o primeiro login!
 */
class StudyClubLocalAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin padrão - ALTERE A SENHA APÓS O PRIMEIRO LOGIN!
        $admin = StudyClubLocalAdmin::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('studyclub2026'), // SENHA PADRÃO - MUDE DEPOIS!
                'name' => 'Administrador Study Club',
                'email' => 'admin@dentalgo.com.br',
                'is_active' => true,
            ]
        );

        $this->command->info('============================================');
        $this->command->info('ADMIN STUDY CLUB CRIADO!');
        $this->command->info('============================================');
        $this->command->info('Usuário: admin');
        $this->command->info('Senha: studyclub2026');
        $this->command->info('--------------------------------------------');
        $this->command->info('⚠️  IMPORTANTE: Altere a senha após o login!');
        $this->command->info('============================================');
        $this->command->info('Acesse: http://localhost:8000/admin_studyclub/login');
    }
}
