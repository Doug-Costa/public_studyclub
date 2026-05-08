<?php

namespace Database\Seeders;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Seeder: StudyClubLegacySeeder
 * Importa as 9 edições do mockup hardcoded para o banco de dados
 * Origem: backup_studyclub/app/Http/Controllers/StudyClubController.php
 */
class StudyClubLegacySeeder extends Seeder
{
    public function run(): void
    {
        Log::info('Iniciando importação do Study Club Legacy');

        $editions = $this->getLegacyData();

        foreach ($editions as $editionData) {
            $this->createEditionWithItems($editionData);
        }

        Log::info('Importação do Study Club Legacy concluída');
        $this->command->info('Importadas ' . count($editions) . ' edições com sucesso!');
    }

    private function createEditionWithItems(array $data): void
    {
        $publishDate = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

        $edition = StudyClubEdition::create([
            'number' => $data['number'],
            'title' => $data['title'],
            'description' => $data['description'],
            'publish_date' => $publishDate,
            'status' => true,
        ]);

        $this->command->info("Criada edição #{$edition->number}: {$edition->title}");

        foreach ($data['items'] as $itemData) {
            $this->createItem($edition->id, $itemData);
        }
    }

    private function createItem(int $editionId, array $data): void
    {
        $imagePath = $this->extractImagePath($data['image'] ?? '');

        StudyClubItem::create([
            'edition_id' => $editionId,
            'category' => $data['category'],
            'type' => $data['type'],
            'type_label' => $data['type_label'],
            'author' => $data['author'],
            'title' => $data['title'],
            'resumo' => $data['resumo'],
            'achados' => $data['achados'],
            'implicacoes' => $data['implicacoes'],
            'image_path' => $imagePath,
            'external_url' => $data['external_url'],
            'likes' => $data['likes'] ?? 0,
            'comments' => $data['comments'] ?? 0,
            'icon' => $data['icon'] ?? 'bi-journal-text',
        ]);
    }

    private function extractImagePath(string $asset): string
    {
        if (str_contains($asset, 'asset(')) {
            $path = str_replace(["asset('", "')", '\''], '', $asset);
            return str_replace('public/', '', $path);
        }
        return $asset;
    }

    private function getLegacyData(): array
    {
        // Dados compactados das 9 edições
        return [
            [
                'number' => 9,
                'title' => 'Study Club #9',
                'date' => '28/05/2026',
                'description' => 'Alô, alô, pessoal! Prontos para os conteúdos dessa semana para o nosso Study Club?!',
                'items' => [
                    [
                        'id' => 'ortodontia-diferenca',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo Original',
                        'author' => 'Matheus Melo Pithon, Orlando Motohiro Tanaka, et al.',
                        'date' => '28/05/2026',
                        'source' => 'DPJO',
                        'title' => 'Quando a Ortodontia faz diferença?',
                        'resumo' => 'Foi realizado um estudo observacional transversal com 288 participantes adultos recrutados por meio de uma pesquisa online.',
                        'achados' => 'Os participantes avaliaram a atratividade em uma Escala Visual Analógica (EVA) de 0 a 100.',
                        'implicacoes' => 'O estudo demonstra o impacto direto da Ortodontia na percepção de atratividade.',
                        'image' => "asset('imagens/fotos_study/artigo9.jpg')",
                        'likes' => 324,
                        'comments' => 45,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1151/journal-2026-v31n2/16193/quando-a-ortodontia-faz-diferenca',
                        'icon' => 'bi-journal-text',
                    ],
                    [
                        'id' => 'calcosferitos',
                        'category' => 'ENDODONTIA',
                        'type' => 'article',
                        'type_label' => 'Biologia da Estética',
                        'author' => 'Alberto Consolaro, Renata B. Consolaro, Dario A. O. Miranda',
                        'date' => '28/05/2026',
                        'source' => 'Revista Estética Dental Press',
                        'title' => 'Importância dos calcosferitos na parede dentinária',
                        'resumo' => 'Uma porcentagem significativa da superfície interna dos canais não entra em contato com os instrumentos endodônticos.',
                        'achados' => 'Os calcosferitos nessa superfície contribuem para preservar a irregularidade das superfícies internas.',
                        'implicacoes' => 'Compreender a anatomia microscópica dos canais radiculares é fundamental.',
                        'image' => "asset('imagens/fotos_study/artigo8.jpg')",
                        'likes' => 276,
                        'comments' => 33,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1152/estetica-jcdr-2026-v23n1/16198/importancia-dos-calcosferitos',
                        'icon' => 'bi-star-fill',
                    ],
                ],
            ],
            [
                'number' => 8,
                'title' => 'Study Club #8',
                'date' => '21/05/2026',
                'description' => 'Semana quente de lançamentos por aqui! Muitos conteúdos novos!',
                'items' => [
                    [
                        'id' => 'sistemas-forcas',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Tópico Especial',
                        'author' => 'Wislei De Oliveira e Wendel Shibazaki',
                        'date' => '21/05/2026',
                        'source' => 'DPJO',
                        'title' => 'Sistemas de forças e função dos attachments na biomecânica dos alinhadores',
                        'resumo' => 'A crescente adesão aos alinhadores contrasta com a falta de fundamentação clara na literatura.',
                        'achados' => 'Estudos focam na eficácia ou em forças estimadas, sem padronização racional.',
                        'implicacoes' => 'Compreender os sistemas de forças é essencial para otimizar os resultados clínicos.',
                        'image' => "asset('imagens/fotos_study/artigo7.jpg')",
                        'likes' => 198,
                        'comments' => 21,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1151/journal-2026-v31n2/16186/sistemas-de-forcas',
                        'icon' => 'bi-journal-text',
                    ],
                    [
                        'id' => 'sono-cigarro',
                        'category' => 'ODONTOLOGIA DO SONO',
                        'type' => 'article',
                        'type_label' => 'Odontologia do Sono',
                        'author' => 'Thays Crosara Abrahão Cunha, Eduardo Januzzi e Thulio Marquez Cunha',
                        'date' => '21/05/2026',
                        'source' => 'Revista Estética Dental Press',
                        'title' => 'Sono e cigarro eletrônico: implicações clínicas',
                        'resumo' => 'A restrição do sono é uma epidemia moderna com repercussões clínicas graves.',
                        'achados' => 'A curta duração do sono está associada ao aumento da mortalidade.',
                        'implicacoes' => 'O sono de qualidade é fator determinante para a saúde sistêmica e bucal.',
                        'image' => "asset('imagens/fotos_study/artigo6.jpg')",
                        'likes' => 276,
                        'comments' => 33,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1152/estetica-jcdr-2026-v23n1/16206/sono-e-cigarro-eletronico',
                        'icon' => 'bi-moon-stars',
                    ],
                ],
            ],
            [
                'number' => 7,
                'title' => 'Study Club #7',
                'date' => '14/05/2026',
                'description' => 'Após um pequeno hiato de páscoa, estamos de volta!',
                'items' => [
                    [
                        'id' => 'table-tops',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Dica Clínica',
                        'author' => 'Eduardo Prado, Raquel Cereser Prado e Karina Maria Salvatore Freitas',
                        'date' => '14/05/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Table Tops: uma alternativa inovadora para manejo da mordida profunda',
                        'resumo' => 'Os Table Tops representam uma alternativa inovadora no contexto ortodôntico.',
                        'achados' => 'Produzidos por tecnologia CAD/CAM, oferecem estabilidade superior.',
                        'implicacoes' => 'Contribuem para a correção indireta da sobremordida.',
                        'image' => "asset('imagens/fotos_study/artigo5.jpg')",
                        'likes' => 324,
                        'comments' => 45,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/Clinical-2026-v25n01/16133/Table-Tops',
                        'icon' => 'bi-lightbulb',
                    ],
                    [
                        'id' => 'ortho-stable',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Recursos em Ortodontia',
                        'author' => 'Cristiane B. André e Maurício de Almeida Cardoso',
                        'date' => '14/05/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Ortho Stable: evolução conceitual da ancoragem esquelética',
                        'resumo' => 'Evidências indicam que esses dispositivos podem estar associados a alterações anatômicas.',
                        'achados' => 'Reforçando a necessidade de sistemas com maior previsibilidade biomecânica.',
                        'implicacoes' => 'A evolução da ancoragem esquelética traz maior segurança.',
                        'image' => "asset('imagens/fotos_study/artigo4.jpg')",
                        'likes' => 198,
                        'comments' => 21,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/Clinical-2026-v25n01/16135/Ortho-Stable',
                        'icon' => 'bi-gear',
                    ],
                ],
            ],
            [
                'number' => 6,
                'title' => 'Study Club #6',
                'date' => '26/06/2026',
                'description' => 'Extraordinariamente, nesta semana, organizamos o nosso Study Club para quinta-feira.',
                'items' => [
                    [
                        'id' => 'go-intelligence',
                        'category' => 'INTELIGÊNCIA ARTIFICIAL',
                        'type' => 'special',
                        'type_label' => 'NOVIDADE',
                        'author' => 'Dental Press',
                        'date' => '26/06/2026',
                        'source' => 'Dental GO',
                        'title' => 'GO Intelligence: A I.A. da Dental Press',
                        'resumo' => 'Desde a noite de ontem, está disponível a mais nova funcionalidade do Dental GO.',
                        'achados' => 'Funciona como as IAs tradicionais que você provavelmente já testou.',
                        'implicacoes' => 'Você pode fazer as perguntas em português, inglês, espanhol ou francês.',
                        'image' => "asset('imagens/fotos_study/artigo3.jpg')",
                        'likes' => 856,
                        'comments' => 124,
                        'external_url' => 'https://dentalgo.com.br/gointelligence',
                        'icon' => 'bi-robot',
                    ],
                    [
                        'id' => 'go-intelligence-features',
                        'category' => 'INTELIGÊNCIA ARTIFICIAL',
                        'type' => 'special',
                        'type_label' => 'Destaques',
                        'author' => 'Dental Press',
                        'date' => '26/06/2026',
                        'source' => 'Dental GO',
                        'title' => 'Três informações que você precisa ter sobre o GO Intelligence',
                        'resumo' => 'Poliglota: Você pode fazer as perguntas em português, inglês, espanhol ou francês.',
                        'achados' => 'Referências em links abaixo da resposta.',
                        'implicacoes' => 'Nesta semana, vamos, juntos, utilizar e estudar com o GO Intelligence?',
                        'image' => "asset('imagens/fotos_study/artigo2.jpg')",
                        'likes' => 543,
                        'comments' => 89,
                        'external_url' => 'https://dentalgo.com.br/gointelligence',
                        'icon' => 'bi-cpu',
                    ],
                ],
            ],
            [
                'number' => 5,
                'title' => 'Study Club #5',
                'date' => '30/04/2026',
                'description' => 'Hoje é quarta-feira e vocês já sabem: é dia das sugestões de artigos!',
                'items' => [
                    [
                        'id' => 'tomografia-ortodontia',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Editorial',
                        'author' => 'Marcio Almeida',
                        'date' => '30/04/2026',
                        'source' => 'Clinical Orthodontics Dental Press',
                        'title' => 'Tomografia na Ortodontia: necessidade clínica ou excesso tecnológico?',
                        'resumo' => 'Discussão importante, apresentando diferentes lados.',
                        'achados' => 'Debater diferentes perspectivas é essencial.',
                        'implicacoes' => 'A discussão sobre o uso racional da tomografia é fundamental.',
                        'image' => "asset('imagens/fotos_study/artigo1.jpg')",
                        'likes' => 324,
                        'comments' => 45,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/clinical-2026-v25n01/16140/tomografia-na-ortodontia',
                        'icon' => 'bi-journal-text',
                    ],
                    [
                        'id' => 'entrevista-basilio',
                        'category' => 'ENTREVISTA',
                        'type' => 'interview',
                        'type_label' => 'Entrevista',
                        'author' => 'Basílio Bernal',
                        'date' => '30/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Entrevista com Basílio Bernal: Ortodontia Digital',
                        'resumo' => 'Um dos nomes mais comentados na Ortodontia Digital na atualidade.',
                        'achados' => 'Entrevista importante com perguntas feitas por Leopoldino Capelozza.',
                        'implicacoes' => 'Formação mínima para o profissional que pretende aplicar alinhadores.',
                        'image' => "asset('imagens/fotos_study/artigo2.jpg')",
                        'likes' => 456,
                        'comments' => 67,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/Clinical-2026-v25n01/16132/Entrevista-com-Basilio',
                        'icon' => 'bi-mic',
                    ],
                ],
            ],
            [
                'number' => 4,
                'title' => 'Study Club #4',
                'date' => '23/04/2026',
                'description' => 'Olá, pessoal! Mais uma quarta-feira, mais uma coletânea de conteúdos!',
                'items' => [
                    [
                        'id' => 'tratamento-hibrido',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'André Wilson Machado',
                        'date' => '23/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Tratamento híbrido da má oclusão de Classe II esquelética',
                        'resumo' => 'O artigo descreve o tratamento de uma criança com má oclusão de Classe II.',
                        'achados' => 'Tratamento apresentado em relato de caso.',
                        'implicacoes' => 'Demonstra a viabilidade da associação entre Ortopedia Funcional e alinhadores.',
                        'image' => "asset('imagens/fotos_study/artigo3.jpg')",
                        'likes' => 287,
                        'comments' => 34,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16078/tratamento-hibrido',
                        'icon' => 'bi-star-fill',
                    ],
                    [
                        'id' => 'classe-ii-alinhadores-4',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'Henrique Xavier Silva Cruz, Silvia Augusta Braga Reis e Ênio Ribeiro Cotrim',
                        'date' => '23/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Correção da Classe II subdivisão com alinhadores ortodônticos',
                        'resumo' => 'O trabalho apresenta um tratamento de Classe II subdivisão com alinhadores.',
                        'achados' => 'Distalização de molares de 4,37 mm, resultado superior aos descritos.',
                        'implicacoes' => 'A associação entre alinhadores e mini-implantes permite resultados superiores.',
                        'image' => "asset('imagens/fotos_study/artigo4.jpg')",
                        'likes' => 342,
                        'comments' => 56,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16080/correcao-da-classe-ii-subdivisao',
                        'icon' => 'bi-journal-text',
                    ],
                ],
            ],
            [
                'number' => 3,
                'title' => 'Study Club #3',
                'date' => '16/04/2026',
                'description' => 'Olá, quarta-feira! Conteúdos selecionados para aprofundar o raciocínio clínico!',
                'items' => [
                    [
                        'id' => 'aumento-altura-facial',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'Kleber Meireles',
                        'date' => '16/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Protocolo de aumento da altura facial anterior inferior',
                        'resumo' => 'Uma abordagem biomecânica conservadora com rotação horária da mandíbula.',
                        'achados' => 'O artigo discute alternativas não cirúrgicas para o manejo de discrepâncias verticais.',
                        'implicacoes' => 'Possibilidade de correção de discrepâncias verticais sem cirurgia.',
                        'image' => "asset('imagens/fotos_study/artigo5.jpg')",
                        'likes' => 276,
                        'comments' => 33,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16081/protocolo-de-aumento-da-altura-facial',
                        'icon' => 'bi-arrow-up',
                    ],
                    [
                        'id' => 'tratamento-implantes',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'Adilson Luiz Ramos e Ricardo Contessotto',
                        'date' => '16/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Tratamento ortodôntico-reabilitador de Classe II em paciente com implantes',
                        'resumo' => 'Relato de caso tratado com alinhadores.',
                        'achados' => 'Discussão clínica relevante sobre os limites do tratamento ortodôntico.',
                        'implicacoes' => 'Ortodontia em pacientes com implantes demanda planejamento criterioso.',
                        'image' => "asset('imagens/fotos_study/artigo6.jpg')",
                        'likes' => 198,
                        'comments' => 21,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16082/tratamento-ortodontico-reabilitador',
                        'icon' => 'bi-implant',
                    ],
                ],
            ],
            [
                'number' => 2,
                'title' => 'Study Club #2',
                'date' => '09/04/2026',
                'description' => 'Nossas sugestões desta quarta-feira no Dental GO!',
                'items' => [
                    [
                        'id' => 'marden-tips',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Dicas do Marden',
                        'author' => 'Marden Bastos',
                        'date' => '09/04/2026',
                        'source' => 'Dental Press / Facelift',
                        'title' => 'Moldagem de transferência de bandas para confecção de aparelhos soldados',
                        'resumo' => 'A moldagem de transferência de bandas é uma etapa crítica.',
                        'achados' => 'Falhas na adaptação podem comprometer o assentamento do aparelho.',
                        'implicacoes' => 'Técnica bem executada é sinônimo de previsibilidade clínica.',
                        'image' => "asset('imagens/fotos_study/artigo7.jpg')",
                        'likes' => 156,
                        'comments' => 12,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/Clinical-2025-v24n06/16079/Moldagem-de-transferencia',
                        'icon' => 'bi-journal-text',
                    ],
                    [
                        'id' => 'classe-ii-alinhadores-2',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'Sílvia Augusta Braga Reis, Enio Ribeiro Cotrim, Henrique Xavier Silva Cruz',
                        'date' => '09/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Correção da Classe II subdivisão com alinhadores ortodônticos',
                        'resumo' => 'Em tratamentos da má oclusão de Classe II sem extrações, a distalização é eficiente.',
                        'achados' => 'O artigo discute o planejamento e a biomecânica envolvidos.',
                        'implicacoes' => 'Uso de mini-implantes como ancoragem permite maior controle.',
                        'image' => "asset('imagens/fotos_study/artigo8.jpg')",
                        'likes' => 210,
                        'comments' => 24,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16080/correcao-da-classe-ii',
                        'icon' => 'bi-star-fill',
                    ],
                ],
            ],
            [
                'number' => 1,
                'title' => 'Study Club #1',
                'date' => '02/04/2026',
                'description' => 'A partir de hoje, toda quarta-feira, apresentamos nossas sugestões para o Dental GO Study Club.',
                'items' => [
                    [
                        'id' => 'paula-oltrami',
                        'category' => 'ENTREVISTA',
                        'type' => 'interview',
                        'type_label' => 'Entrevista',
                        'author' => 'Paula Oltrami',
                        'date' => '02/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Trajetória na Ortodontia, marcada pelo rigor científico',
                        'resumo' => 'A professora Paula Oltrami compartilha sua trajetória na Ortodontia.',
                        'achados' => 'Pioneira nas pesquisas com alinhadores no Brasil.',
                        'implicacoes' => 'Contribuição decisiva para o avanço da especialidade.',
                        'image' => "asset('imagens/fotos_study/artigo1.jpg')",
                        'likes' => 342,
                        'comments' => 56,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16077/entrevista-com-paula-oltramari',
                        'icon' => 'bi-mic',
                    ],
                    [
                        'id' => 'mordida-aberta-invisalign',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'Fabrício Pinelli Valarelli, Eduardo Prado, et al.',
                        'date' => '02/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Compensação dentoesquelética extrema de mordida aberta anterior',
                        'resumo' => 'A mordida aberta anterior é um dos grandes desafios da Ortodontia.',
                        'achados' => 'O artigo mostra como os alinhadores podem ser uma alternativa viável.',
                        'implicacoes' => 'Possibilidade de tratamento de casos severos sem cirurgia.',
                        'image' => "asset('imagens/fotos_study/artigo2.jpg')",
                        'likes' => 450,
                        'comments' => 89,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1138/clinical-2025-v24n05/15979/compensacao-dentoesqueletica',
                        'icon' => 'bi-file-text',
                    ],
                ],
            ],
        ];
    }
}
