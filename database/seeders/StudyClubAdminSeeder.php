<?php

namespace Database\Seeders;

use App\Models\StudyClubAdmin;
use Illuminate\Database\Seeder;

/**
 * Seeder: StudyClubAdminSeeder
 * Cria os primeiros administradores do Study Club
 * Configure os emails conforme necessário
 */
class StudyClubAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'email' => 'ti2@dentalpress.com.br', // Altere para o email real
                'name' => 'Administrador Study Club',
                'role' => 'admin',
                'is_active' => true,
            ],
            // Adicione mais admins conforme necessário:
            // [
            //     'email' => 'jornalista@dentalgo.com.br',
            //     'name' => 'Jornalista',
            //     'role' => 'editor',
            //     'is_active' => true,
            // ],
        ];

        foreach ($admins as $admin) {
            StudyClubAdmin::firstOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }

        $this->command->info('Admins do Study Club criados com sucesso!');
        $this->command->info('Email cadastrado: ' . $admins[0]['email']);
        $this->command->info('Altere no seeder se necessário.');
    }
}
