# Study Club Implementation - Rollback Guide

## Data: 06/05/2026
## Descrição: Implementação completa do sistema Study Club com DDD + TDD

---

## Arquivos Criados

### Domain Layer (DDD)
| Arquivo | Descrição |
|---------|-----------|
| `app/Models/StudyClubEdition.php` | Model de Edições |
| `app/Models/StudyClubItem.php` | Model de Itens/Artigos |
| `app/Repositories/Contracts/StudyClubRepositoryInterface.php` | Interface do Repository |
| `app/Repositories/Eloquent/StudyClubRepository.php` | Implementação Eloquent |
| `database/migrations/2026_05_06_000001_create_studyclub_tables.php` | Migration das tabelas |

### Testes (TDD)
| Arquivo | Descrição |
|---------|-----------|
| `database/factories/StudyClubEditionFactory.php` | Factory de Edições |
| `database/factories/StudyClubItemFactory.php` | Factory de Itens |
| `tests/Unit/StudyClub/StudyClubEditionTest.php` | Testes Unitários do Model Edition |
| `tests/Unit/StudyClub/StudyClubItemTest.php` | Testes Unitários do Model Item |
| `tests/Unit/Repositories/StudyClub/StudyClubRepositoryTest.php` | Testes do Repository |
| `tests/Feature/Controllers/StudyClub/Admin/StudyClubAdminControllerTest.php` | Testes Feature Admin |
| `tests/Feature/Controllers/StudyClub/Public/StudyClubControllerTest.php` | Testes Feature Público |

### Controllers
| Arquivo | Descrição |
|---------|-----------|
| `app/Http/Controllers/StudyClubController.php` | Controller Público |
| `app/Http/Controllers/Admin/StudyClubAdminController.php` | Controller Admin |

### Form Requests (Validação)
| Arquivo | Descrição |
|---------|-----------|
| `app/Http/Requests/StudyClub/Admin/StoreEditionRequest.php` | Validação criação edição |
| `app/Http/Requests/StudyClub/Admin/UpdateEditionRequest.php` | Validação atualização edição |
| `app/Http/Requests/StudyClub/Admin/StoreItemRequest.php` | Validação criação item |

### Views
| Arquivo | Descrição |
|---------|-----------|
| `resources/views/studyclub/index.blade.php` | Lista de edições (público) |
| `resources/views/studyclub/edition.blade.php` | Detalhe da edição (público) |
| `resources/views/studyclub/show.blade.php` | Detalhe do artigo (público) |
| `resources/views/admin/studyclub/index.blade.php` | Dashboard admin |
| `resources/views/admin/studyclub/create.blade.php` | Criar edição |
| `resources/views/admin/studyclub/edit.blade.php` | Editar edição + gerenciar itens |

### Seeders
| Arquivo | Descrição |
|---------|-----------|
| `database/seeders/StudyClub/StudyClubLegacySeeder.php` | Importa 9 edições do backup (namespace completo) |
| `database/seeders/StudyClubLegacySeeder.php` | Importa 9 edições do backup (namespace raiz) |

### Arquivos Modificados
| Arquivo | Modificação |
|---------|-------------|
| `routes/web.php` | Adicionadas rotas públicas e admin |
| `app/Providers/AppServiceProvider.php` | Binding do Repository |

---

## Comandos de Rollback

### 1. Remover Migration (se necessário)
```bash
php artisan migrate:rollback --path=database/migrations/2026_05_06_000001_create_studyclub_tables.php
```

### 2. Remover arquivos (em ordem)
```bash
# Remover arquivos criados
rm app/Models/StudyClubEdition.php
rm app/Models/StudyClubItem.php
rm app/Repositories/Contracts/StudyClubRepositoryInterface.php
rm app/Repositories/Eloquent/StudyClubRepository.php
rm -rf app/Repositories
rm database/migrations/2026_05_06_000001_create_studyclub_tables.php
rm database/factories/StudyClubEditionFactory.php
rm database/factories/StudyClubItemFactory.php
rm database/seeders/StudyClubLegacySeeder.php
rm -rf database/seeders/StudyClub
rm -rf tests/Unit/StudyClub
rm -rf tests/Unit/Repositories/StudyClub
rm -rf tests/Feature/Controllers/StudyClub
rm app/Http/Controllers/StudyClubController.php
rm app/Http/Controllers/Admin/StudyClubAdminController.php
rm -rf app/Http/Controllers/Admin
rm app/Http/Requests/StudyClub/Admin/StoreEditionRequest.php
rm app/Http/Requests/StudyClub/Admin/UpdateEditionRequest.php
rm app/Http/Requests/StudyClub/Admin/StoreItemRequest.php
rm -rf app/Http/Requests/StudyClub
rm -rf resources/views/studyclub
rm -rf resources/views/admin/studyclub
rm -rf resources/views/admin
```

### 3. Restaurar arquivos modificados via Git
```bash
git checkout routes/web.php
git checkout app/Providers/AppServiceProvider.php
```

### 4. Limpar cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

---

## Comandos de Instalação

### 1. Executar migration
```bash
php artisan migrate --path=database/migrations/2026_05_06_000001_create_studyclub_tables.php
```

### 2. Importar dados legados (escolha um)
```bash
# Opção 1: Seeder no namespace raiz (recomendado)
php artisan db:seed --class=StudyClubLegacySeeder

# Opção 2: Seeder no sub-namespace (se autoload estiver configurado)
php artisan db:seed --class=Database\\Seeders\\StudyClub\\StudyClubLegacySeeder
```

### 3. Criar link de storage (para uploads)
```bash
php artisan storage:link
```

### 4. Executar testes (usando vendor/bin/phpunit - compatível com PHPUnit 9.x)
```bash
# Todos os testes do Study Club
vendor/bin/phpunit --filter=StudyClub

# Testes Unitários
vendor/bin/phpunit tests/Unit/StudyClub

# Testes de Repository
vendor/bin/phpunit tests/Unit/Repositories/StudyClub

# Testes Feature
vendor/bin/phpunit tests/Feature/Controllers/StudyClub

# NOTA: php artisan test requer PHPUnit 10.x (Collision 7.x)
```

---

## Rotas Disponíveis

### Públicas
| Rota | URL | Descrição |
|------|-----|-----------|
| `studyclub.index` | GET /studyclub | Lista todas as edições |
| `studyclub.edition` | GET /studyclub/edition/{number} | Detalhe da edição |
| `studyclub.show` | GET /studyclub/{editionNumber}/{itemId} | Detalhe do artigo |

### Admin (Protegidas por auth)
| Rota | URL | Descrição |
|------|-----|-----------|
| `admin.studyclub.index` | GET /admin_studyclub | Dashboard |
| `admin.studyclub.create` | GET /admin_studyclub/create | Form criação |
| `admin.studyclub.store` | POST /admin_studyclub/store | Salvar edição |
| `admin.studyclub.edit` | GET /admin_studyclub/edit/{id} | Form edição |
| `admin.studyclub.update` | PUT /admin_studyclub/update/{id} | Atualizar edição |
| `admin.studyclub.destroy` | DELETE /admin_studyclub/destroy/{id} | Excluir edição |
| `admin.studyclub.items.store` | POST /admin_studyclub/{editionId}/items | Adicionar item |
| `admin.studyclub.items.destroy` | DELETE /admin_studyclub/items/{itemId} | Excluir item |

---

## Verificação Pós-Instalação

1. Acessar `/studyclub` - Deve mostrar lista de edições
2. Acessar `/admin_studyclub` (logado) - Deve mostrar dashboard admin
3. Criar nova edição via admin
4. Adicionar artigo à edição
5. Verificar se aparece no frontend público

---

## Estrutura de Diretórios Criada
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       └── StudyClubAdminController.php
│   ├── Requests/
│   │   └── StudyClub/
│   │       └── Admin/
├── Models/
│   ├── StudyClubEdition.php
│   └── StudyClubItem.php
├── Repositories/
│   ├── Contracts/
│   │   └── StudyClubRepositoryInterface.php
│   └── Eloquent/
│       └── StudyClubRepository.php

database/
├── factories/
│   ├── StudyClubEditionFactory.php
│   └── StudyClubItemFactory.php
├── migrations/
│   └── 2026_05_06_000001_create_studyclub_tables.php
└── seeders/
    └── StudyClub/
        └── StudyClubLegacySeeder.php

resources/
└── views/
    ├── admin/
    │   └── studyclub/
    │       ├── index.blade.php
    │       ├── create.blade.php
    │       └── edit.blade.php
    └── studyclub/
        ├── index.blade.php
        ├── edition.blade.php
        └── show.blade.php

tests/
├── Unit/
│   ├── StudyClub/
│   │   ├── StudyClubEditionTest.php
│   │   └── StudyClubItemTest.php
│   └── Repositories/
│       └── StudyClub/
│           └── StudyClubRepositoryTest.php
└── Feature/
    └── Controllers/
        └── StudyClub/
            ├── Admin/
            │   └── StudyClubAdminControllerTest.php
            └── Public/
                └── StudyClubControllerTest.php
```

---

## Notas

- As imagens são armazenadas em `storage/app/public/studyclub/`
- O seeder importa 9 edições com 18 artigos do backup original
- Sistema usa Repository Pattern para desacoplamento (DDD)
- Testes cobrem models, repository e controllers (TDD)
