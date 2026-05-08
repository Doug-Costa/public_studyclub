<?php

namespace Database\Seeders\StudyClub;

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
    }

    /**
     * Cria uma edição com seus itens
     */
    private function createEditionWithItems(array $data): void
    {
        // Parse date from format d/m/Y to Y-m-d
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

    /**
     * Cria um item dentro de uma edição
     */
    private function createItem(int $editionId, array $data): void
    {
        // Extrai o caminho da imagem do asset()
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

    /**
     * Extrai caminho da imagem do asset()
     */
    private function extractImagePath(string $asset): string
    {
        // Remove asset() e aspas, mantém apenas o path
        if (str_contains($asset, 'asset(')) {
            $path = str_replace(["asset('", "')", '\''], '', $asset);
            return str_replace('public/', '', $path);
        }

        return $asset;
    }

    /**
     * Dados legados do backup_studyclub/app/Http/Controllers/StudyClubController.php
     */
    private function getLegacyData(): array
    {
        return [
            [
                'number' => 9,
                'title' => 'Study Club #9',
                'date' => '28/05/2026',
                'description' => 'Alô, alô, pessoal! Prontos para os conteúdos dessa semana para o nosso Study Club?! Os artigos de hoje estão simplesmente imperdíveis!',
                'items' => [
                    [
                        'id' => 'ortodontia-diferenca',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo Original',
                        'author' => 'Matheus Melo Pithon, Orlando Motohiro Tanaka, et al.',
                        'date' => '28/05/2026',
                        'source' => 'DPJO',
                        'title' => 'Quando a Ortodontia faz diferença? Efeitos limiares na percepção de atratividade e nas intenções de contratação',
                        'resumo' => 'Foi realizado um estudo observacional transversal com 288 participantes adultos recrutados por meio de uma pesquisa online. Três casos clínicos foram selecionados, e fotografias padronizadas do sorriso pré e pós-tratamento foram apresentadas em ordem aleatória.',
                        'achados' => 'Os participantes avaliaram a atratividade em uma Escala Visual Analógica (EVA) de 0 a 100 e indicaram a intenção de contratação ("sim", "não" ou "não sei").',
                        'implicacoes' => 'O estudo demonstra o impacto direto da Ortodontia na percepção de atratividade e nas decisões de contratação profissional.',
                        'image' => "asset('imagens/fotos_study/artigo9.jpg')",
                        'likes' => 324,
                        'comments' => 45,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1151/journal-2026-v31n2/16193/quando-a-ortodontia-faz-diferenca-efeitos-limiares-na-percepcao-de-atratividade-e-nas-intencoes-de-contratacao',
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
                        'title' => 'Importância dos calcosferitos na parede dentinária de canais preparados para a obturação',
                        'resumo' => 'Uma porcentagem significativa da superfície interna dos canais não entra em contato com os instrumentos endodônticos.',
                        'achados' => 'Os calcosferitos nessa superfície contribuem para preservar e/ou aumentar a irregularidade das superfícies internas dos canais a serem obturados.',
                        'implicacoes' => 'Compreender a anatomia microscópica dos canais radiculares é fundamental para o sucesso dos tratamentos endodônticos.',
                        'image' => "asset('imagens/fotos_study/artigo8.jpg')",
                        'likes' => 276,
                        'comments' => 33,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1152/estetica-jcdr-2026-v23n1/16198/importancia-dos-calcosferitos-na-parede-dentinaria-de-canais-preparados-para-a-obturacao',
                        'icon' => 'bi-star-fill',
                    ],
                ],
            ],
            [
                'number' => 8,
                'title' => 'Study Club #8',
                'date' => '21/05/2026',
                'description' => 'Semana quente de lançamentos por aqui! Muitos conteúdos novos para vocês aproveitarem no Dental GO! Duas revistas novas e um GO Academy no capricho para vocês!',
                'items' => [
                    [
                        'id' => 'sistemas-forcas',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Tópico Especial',
                        'author' => 'Wislei De Oliveira e Wendel Shibazaki',
                        'date' => '21/05/2026',
                        'source' => 'DPJO',
                        'title' => 'Sistemas de forças e função dos attachments na biomecânica dos alinhadores: uma abordagem analítica',
                        'resumo' => 'A crescente adesão aos alinhadores contrasta com a falta de fundamentação clara na literatura sobre seus métodos de ativação.',
                        'achados' => 'Estudos focam na eficácia ou em forças estimadas, sem padronização racional, e frequentemente apresentam resultados conflitantes com a prática clínica.',
                        'implicacoes' => 'Compreender os sistemas de forças é essencial para otimizar os resultados clínicos com alinhadores ortodônticos.',
                        'image' => "asset('imagens/fotos_study/artigo7.jpg')",
                        'likes' => 198,
                        'comments' => 21,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1151/journal-2026-v31n2/16186/sistemas-de-forcas-e-funcao-dos-attachments-na-biomecanica-dos-alinhadores-uma-abordagem-analitica',
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
                        'title' => 'Sono e cigarro eletrônico: implicações clínicas e desafios para a Odontologia',
                        'resumo' => 'A restrição do sono é uma epidemia moderna com repercussões clínicas graves.',
                        'achados' => 'A curta duração do sono (<7 horas) está associada ao aumento da mortalidade por todas as causas e ao risco elevado de doenças crônicas.',
                        'implicacoes' => 'O sono de qualidade é fator determinante para a saúde sistêmica e bucal dos pacientes.',
                        'image' => "asset('imagens/fotos_study/artigo6.jpg')",
                        'likes' => 276,
                        'comments' => 33,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1152/Est%C3%A9tica-%7C-JCDR-2026-v23n1/16206/Sono-e-cigarro-eletr%C3%B4nico:-implica%C3%A7%C3%B5es-cl%C3%ADnicas-e-desafios-para-a-Odontologia',
                        'icon' => 'bi-moon-stars',
                    ],
                ],
            ],
            [
                'number' => 7,
                'title' => 'Study Club #7',
                'date' => '14/05/2026',
                'description' => 'Após um pequeno hiato de páscoa, estamos de volta com nossas sugestões do que há de melhor para você ler, ouvir e assistir no Dental GO! É sugestão de conteúdo que vocês querem? Então, aqui está!',
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
                        'resumo' => 'Os Table Tops, tradicionalmente utilizados na Odontologia Restauradora para recobrimento oclusal e aumento da dimensão vertical, representam uma alternativa inovadora quando aplicados ao contexto ortodôntico.',
                        'achados' => 'Produzidos por tecnologia CAD/CAM, oferecem estabilidade superior aos levantes convencionais, permitindo desoclusão anterior imediata, extrusão posterior previsível e rotação mandibular horária.',
                        'implicacoes' => 'Contribuem para a correção indireta da sobremordida com menor dependência de mecânicas intrusivas.',
                        'image' => "asset('imagens/fotos_study/artigo5.jpg')",
                        'likes' => 324,
                        'comments' => 45,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/Clinical-2026-v25n01/16133/Table-Tops:-uma-alternativa-inovadora-para-manejo-da-mordida-profunda',
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
                        'title' => 'Ortho Stable: evolução conceitual da ancoragem esquelética em Ortodontia',
                        'resumo' => 'Evidências indicam que esses dispositivos podem estar associados a alterações anatômicas e biológicas que exigem monitoramento criterioso.',
                        'achados' => 'Reforçando a necessidade de sistemas com maior previsibilidade biomecânica e menor impacto tecidual.',
                        'implicacoes' => 'A evolução da ancoragem esquelética traz maior segurança e previsibilidade aos tratamentos ortodônticos complexos.',
                        'image' => "asset('imagens/fotos_study/artigo4.jpg')",
                        'likes' => 198,
                        'comments' => 21,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/Clinical-2026-v25n01/16135/Ortho-Stable:-evolu%C3%A7%C3%A3o-conceitual-da-ancoragem-esqueletica-em-Ortodontia',
                        'icon' => 'bi-gear',
                    ],
                ],
            ],
            [
                'number' => 6,
                'title' => 'Study Club #6',
                'date' => '26/06/2026',
                'description' => '🚨 ATENÇÃO: ALERTA DE NOVIDADE🚨 Extraordinariamente, nesta semana, organizamos o nosso Study Club para quinta-feira. Mas o motivo é mais do que nobre.',
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
                        'resumo' => 'Desde a noite de ontem, quarta-feira (25), está disponível a mais nova funcionalidade do Dental GO: O GO Intelligence a I.A. da Dental Press!',
                        'achados' => 'Funciona como as IAs tradicionais que você provavelmente já testou, como o ChatGPT e o Gemini. A ferramenta faz busca em artigos científicos que foram publicados em revistas que passaram já por uma curadoria de nossos editores.',
                        'implicacoes' => 'Você pode fazer as perguntas em português, inglês, espanhol ou francês e o GO Intelligence vai responder na língua que você perguntou. Referências em links abaixo da resposta.',
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
                        'resumo' => 'Poliglota: Você pode fazer as perguntas em português, inglês, espanhol ou francês. Anatomia do Prompt: Quanto mais contextualização você der para ferramenta, mais completa vai ser sua resposta.',
                        'achados' => 'Referências em links: Necessariamente abaixo da resposta, você terá acesso a links com os artigos que foram utilizados para a resposta dada.',
                        'implicacoes' => 'Nesta semana, vamos, juntos, utilizar e estudar com o GO Intelligence? Acesse já essa nova ferramenta que vai revolucionar sua forma de estudar e preparar seus casos!',
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
                'description' => 'Hoje é quarta-feira e vocês já sabem: é dia das sugestões de artigos nas nossas revistas! Olha só o que nós separamos para vocês👇',
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
                        'resumo' => 'Discussão importante, apresentando diferentes lados, promovida pelo editor-chefe da revista Clinical Orthodontics Dental Press, Prof. Dr. Marcio Almeida.',
                        'achados' => 'Debater diferentes perspectivas é essencial para o avanço clínico.',
                        'implicacoes' => 'A discussão sobre o uso racional da tomografia na Ortodontia é fundamental para a prática baseada em evidências.',
                        'image' => "asset('imagens/fotos_study/artigo1.jpg')",
                        'likes' => 324,
                        'comments' => 45,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/clinical-2026-v25n01/16140/tomografia-na-ortodontia-necessidade-clinica-ou-excesso-tecnologico',
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
                        'resumo' => 'Um dos nomes mais comentados na Ortodontia Digital na atualidade, Basílio Bernal é o entrevistado da nova edição da Revista Clinical Orthodontics da Dental Press!',
                        'achados' => 'Entrevista importante com perguntas feitas por Leopoldino Capelozza, Jurandir Barbosa, Adilson Ramos e Celestino Nóbrega.',
                        'implicacoes' => '"Considerando a sua experiência e a perspectiva definida para o uso dos alinhadores no tratamento ortodôntico, qual considera a formação mínima para o profissional que pretende aplicar essa técnica?", pergunta de Leopoldino Capelozza.',
                        'image' => "asset('imagens/fotos_study/artigo2.jpg')",
                        'likes' => 456,
                        'comments' => 67,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1147/Clinical-2026-v25n01/16132/Entrevista-com-Basilio-Jos%C3%A9-Bernal-Junior',
                        'icon' => 'bi-mic',
                    ],
                ],
            ],
            [
                'number' => 4,
                'title' => 'Study Club #4',
                'date' => '23/04/2026',
                'description' => 'Olá, pessoal! Mais uma quarta-feira, mais uma coletânea de conteúdos para você! Dá só uma olhadinha nos materiais que estão disponíveis no Dental GO:👇',
                'items' => [
                    [
                        'id' => 'tratamento-hibrido',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'André Wilson Machado',
                        'date' => '23/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Tratamento híbrido da má oclusão de Classe II esquelética com Ortopedia Funcional e Invisalign em criança com 11 anos de idade',
                        'resumo' => 'O artigo descreve o tratamento de uma criança, com 11 anos de idade, com má oclusão de Classe II esquelética por deficiência mandibular, conduzido de forma híbrida.',
                        'achados' => 'Tratamento apresentado em relato de caso por André Machado.',
                        'implicacoes' => 'Demonstra a viabilidade da associação entre Ortopedia Funcional e alinhadores em pacientes em fase de crescimento.',
                        'image' => "asset('imagens/fotos_study/artigo3.jpg')",
                        'likes' => 287,
                        'comments' => 34,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16078/tratamento-hibrido-da-ma-oclusao-de-classe-ii-esqueletica-com-ortopedia-funcional-e-invisalign-em-crianca-com-11-anos-de-idade',
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
                        'title' => 'Correção da Classe II subdivisão com alinhadores ortodônticos e ancoragem esquelética: relato de caso',
                        'resumo' => 'O trabalho apresenta, por meio de um relato clínico, um tratamento de Classe II subdivisão com alinhadores ortodônticos estéticos associados a mini-implantes ortodônticos.',
                        'achados' => 'Distalização de molares de 4,37 mm, resultado superior aos normalmente descritos na literatura.',
                        'implicacoes' => 'A associação entre alinhadores e mini-implantes permite resultados clinicamente superiores em casos de Classe II.',
                        'image' => "asset('imagens/fotos_study/artigo4.jpg')",
                        'likes' => 342,
                        'comments' => 56,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16080/correcao-da-classe-ii-subdivisao-com-alinhadores-ortodonticos-e-ancoragem-esqueletica-relato-de-caso',
                        'icon' => 'bi-journal-text',
                    ],
                ],
            ],
            [
                'number' => 3,
                'title' => 'Study Club #3',
                'date' => '16/04/2026',
                'description' => 'Olá, quarta-feira! Conteúdos selecionados para aprofundar o raciocínio clínico e ampliar as possibilidades terapêuticas na Ortodontia 👇',
                'items' => [
                    [
                        'id' => 'aumento-altura-facial',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'Kleber Meireles',
                        'date' => '16/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Protocolo de aumento da altura facial anterior inferior em pacientes adultos não cirúrgicos',
                        'resumo' => 'Uma abordagem biomecânica conservadora, com rotação horária da mandíbula, apresentada em relato de caso por José Kleber Soares de Meireles.',
                        'achados' => 'O artigo discute alternativas não cirúrgicas para o manejo de discrepâncias verticais em pacientes adultos, com foco em biomecânica e previsibilidade.',
                        'implicacoes' => 'Possibilidade de correção de discrepâncias verticais sem cirurgia ortognática em adultos.',
                        'image' => "asset('imagens/fotos_study/artigo5.jpg')",
                        'likes' => 276,
                        'comments' => 33,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16081/protocolo-de-aumento-da-altura-facial-anterior-inferior-em-pacientes-adultos-nao-cirurgicos',
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
                        'title' => 'Tratamento ortodôntico-reabilitador de Classe II e mordida aberta anterior em paciente com implantes',
                        'resumo' => 'Relato de caso tratado com alinhadores, assinado por Adilson Luiz Ramos e Ricardo Contessotto.',
                        'achados' => 'Uma discussão clínica relevante sobre os limites e as possibilidades do tratamento ortodôntico em pacientes com implantes na região dos primeiros molares inferiores.',
                        'implicacoes' => 'Ortodontia em pacientes com implantes demanda planejamento criterioso considerando as particularidades biomecânicas.',
                        'image' => "asset('imagens/fotos_study/artigo6.jpg')",
                        'likes' => 198,
                        'comments' => 21,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16082/tratamento-ortodontico-reabilitador-de-classe-ii-e-mordida-aberta-anterior-em-paciente-com-implantes',
                        'icon' => 'bi-implant',
                    ],
                ],
            ],
            [
                'number' => 2,
                'title' => 'Study Club #2',
                'date' => '09/04/2026',
                'description' => 'Nossas sugestões desta quarta-feira no Dental GO. Conteúdos selecionados para apoiar sua prática clínica com mais precisão técnica e embasamento científico 👇',
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
                        'resumo' => 'A moldagem de transferência de bandas é uma etapa crítica na confecção de aparelhos ortodônticos soldados em modelos de trabalho.',
                        'achados' => 'Falhas na adaptação das bandas ou no procedimento de moldagem podem comprometer o assentamento do aparelho, aumentar o risco de deformações e favorecer a ruptura das bandas durante a ativação.',
                        'implicacoes' => 'Técnica bem executada é sinônimo de previsibilidade clínica. Ideal para evitar retrabalho na soldagem de aparelhos.',
                        'image' => "asset('imagens/fotos_study/artigo7.jpg')",
                        'likes' => 156,
                        'comments' => 12,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/Clinical-2025-v24n06/16079/Moldagem-de-transfer%C3%AAncia-de-bandas-para-confec%C3%A7%C3%A3o-de-aparelhos-soldados-em-modelos-de-trabalho',
                        'icon' => 'bi-journal-text',
                    ],
                    [
                        'id' => 'classe-ii-alinhadores',
                        'category' => 'ORTODONTIA',
                        'type' => 'article',
                        'type_label' => 'Artigo em Destaque',
                        'author' => 'Sílvia Augusta Braga Reis, Enio Ribeiro Cotrim, Henrique Xavier Silva Cruz',
                        'date' => '09/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Correção da Classe II subdivisão com alinhadores ortodônticos e ancoragem esquelética',
                        'resumo' => 'Em tratamentos da má oclusão de Classe II sem extrações dentárias, a distalização de molares é uma alternativa eficiente para correções de 2 a 3 mm.',
                        'achados' => 'O artigo discute o planejamento e a biomecânica envolvidos na associação entre alinhadores e ancoragem esquelética.',
                        'implicacoes' => 'Uso de mini-implantes como ancoragem permite maior controle da distalização, otimizando o tempo de tratamento com alinhadores.',
                        'image' => "asset('imagens/fotos_study/artigo8.jpg')",
                        'likes' => 210,
                        'comments' => 24,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1143/clinical-2025-v24n06/16080/correcao-da-classe-ii-subdivisao-com-alinhadores-ortodonticos-e-ancoragem-esqueletica-relato-de-caso',
                        'icon' => 'bi-star-fill',
                    ],
                ],
            ],
            [
                'number' => 1,
                'title' => 'Study Club #1',
                'date' => '02/04/2026',
                'description' => 'A partir de hoje, toda quarta-feira, apresentamos nossas sugestões para o Dental GO Study Club, com sugestões de artigos, entrevistas e conteúdos selecionados para apoiar sua prática clínica com base em evidência científica.',
                'items' => [
                    [
                        'id' => 'paula-oltrami',
                        'category' => 'ENTREVISTA',
                        'type' => 'interview',
                        'type_label' => 'Entrevista',
                        'author' => 'Paula Oltrami',
                        'date' => '02/04/2026',
                        'source' => 'Clinical Orthodontics',
                        'title' => 'Trajetória na Ortodontia, marcada pelo rigor científico e pela prática clínica responsável',
                        'resumo' => 'A professora Paula Oltrami compartilha sua trajetória na Ortodontia, marcada pelo rigor científico e pela prática clínica responsável.',
                        'achados' => 'Pioneira nas pesquisas com alinhadores no Brasil, Paula contribui de forma decisiva para o avanço da especialidade no cenário internacional.',
                        'implicacoes' => 'Contribuição decisiva para o avanço da especialidade no cenário internacional em entrevista à Clinical Orthodontics.',
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
                        'title' => 'Compensação dentoesquelética extrema de mordida aberta anterior esquelética severa tratada com Invisalign®',
                        'resumo' => 'A mordida aberta anterior é um dos grandes desafios da Ortodontia. Caso complexo com compensação dentoesquelética.',
                        'achados' => 'O artigo mostra como, com planejamento criterioso, os alinhadores transparentes — como o Invisalign — podem ser uma alternativa viável mesmo em casos complexos.',
                        'implicacoes' => 'Possibilidade de tratamento de casos severos sem cirurgia ortognática através do uso estratégico de alinhadores.',
                        'image' => "asset('imagens/fotos_study/artigo2.jpg')",
                        'likes' => 450,
                        'comments' => 89,
                        'external_url' => 'https://dentalgo.com.br/facelift25/artigo/1138/clinical-2025-v24n05/15979/compensacao-dentoesqueletica-extrema-de-mordida-aberta-anterior-esqueletica-severa-tratada-com-invisalign',
                        'icon' => 'bi-file-text',
                    ],
                ],
            ],
        ];
    }
}
