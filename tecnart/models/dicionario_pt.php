<?php 
include_once "config/configurations.php";
function ret_dic_pt(){



    /**
     * Dicionario para site em portugues
     * As chaves dos arrays servirao como substituto do id ou classe CSS do objeto
     * [INCOMPLETO!!! VERIFICAR E INTRODUZIR ENTRADAS NESTE DICIONARIO!!]
     */

    $dic_pt = array(

        //dates
        "day-name" => array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'),
        "date-of" => " de ",
        "month-name" => array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'),
        //::::::CABEÇALHO PRINCIPAL::::::

        //Imagem com o logotipo da techn&art
        "header-site-logo" => "./assets/images/TechnArt5FundoTrans.png",
        //Drop-down 'Sobre o Techn&Art'
        "about-technart-drop-down" => "Sobre o TECHN&ART",
        "mission-and-goals-option" => "Missão e Objetivos",
        "research-axes-option" => "Eixos de Investigação",
        "org-struct-option" => "Estrutura Orgânica",
        "opportunities-option" => "Oportunidades",
        "regulation-option" => "Regulamentos",
        "electoral-process" => "Processo Eleitoral ",
        "regulation-option-geral" => "Regulamento Geral",
        "electoral-option-geral" => "Regulamento Eleitoral",
        "electoral-calendar-option-geral" => "Calendário Eleitoral (última atualização: 2 de junho de 2025)",
        "electoral-calendar-option-geral-rectification" => "Declaração de Retificação",
        "voters-notebook" => "Caderno de Eleitores/as",
        "application-admitted" => "Candidatura Admitida",
        "funding-option" => "Financiamento",
        //Drop-down 'Projetos'
        "projects-tab" => "Projetos",
        "ongoing-option" => "Em Curso",
        "finished-option" => "Concluídos",
        //Drop-down 'Investigadores/as'
        "researchers-drop-down" => "Investigadores/as",
        "integrated-option" => "Integrados/as",
        "collaborators-option" => "Colaboradores/as",
        "students-option" => "Estudantes Colaboradores/as",
        "admission-option" => "Novas admissões",
        //Separador 'Noticias'
        "news-tab" => "Notícias",
        //Separador 'Publicacoes'
        "publ-tab" => "Publicações",

        //::::::RODAPÉ PRINCIPAL::::::

        //Imagem com o logotipo da techn&art
        "footer-site-logo" => "./assets/images/TechnArt6FundoTrans.png",
        //Etiqueta / texto com parte da morada
        "address-txt-1" => "Quinta do Contador,",
        //Etiqueta / texto com parte da morada
        "address-txt-2" => "Estrada da Serra",
        //Etiqueta / texto 'Siga-nos',
        "follow-us-txt" => "Siga-nos",
        //Divisoria com 'projeto UD ...'
        "project-ud-txt" => "Projeto UID/05488/2020",
        //Etiqueta / texto com 'Instituto Pol...'
        "ipt-copyright-txt" => "©Instituto Politécnico de Tomar",
        //Etiqueta / texto com 'Todos os direitos reservados'
        "all-rights-reserved-txt" => "Todos os direitos reservados",

        //::::::index.php::::::

        //Titulo do 1 slide
        "index-first-slide" => "Sobre o TECHN&ART",
        //Breve descricao do 1 slide
        "index-first-slide-desc" => "Centro de investigação e desenvolvimento nos domínios da Salvaguarda do Património e da sua Valorização, experimental e aplicada.",
        //Titulo do 2 slide
        "index-second-slide" => "Tecnologia e interdisciplinaridade ao serviço do Património",
        //Breve descricao do 2 slide
        "index-second-slide-desc" => "O TECHN&ART une investigadores/as das mais diversas áreas disciplinares presentes no Instituto Politécnico de Tomar, das TIC às artes, das ciências sociais às ciências naturais",
        //Titulo do 3 slide
        "index-third-slide-slider" => "Investigação e desenvolvimento em rede",
        //Breve descricao do 3 slide
        "index-third-slide-slider-desc" => "O TECHN&ART acolhe e coordena projetos de I&D numa ampla rede de parceiros nacionais e internacionais, na linha da frente da salvaguarda e valorização patrimoniais",
        //botao 'Saiba mais' do slider
        "know-more-btn-txt-slider" => "SAIBA MAIS",
        //Etiqueta 'Video Institucional'
        "institutional-video-heading" => "VÍDEO INSTITUCIONAL",
        //Etiqueta 'Projetos I&D'
        "rd-projects-heading" => "PROJETOS I&D",
        //botao 'Ver Todos'
        "see-all-btn-rd-projects" => "VER TODOS",
        //Etiqueta 'Ultimas noticias'
        "latest-news-heading" => "ÚLTIMAS NOTÍCIAS",
        //botao 'Ver todas'
        "see-all-btn-latest-news" => "VER TODAS",

        //::::::sobre.php::::::

        //Titulo 'Sobre o Techn&Art'
        "about-technart-page-heading" => "Sobre o TECHN&ART",
        //Subitulo página 'Sobre o Techn&Art'
        "about-technart-page-subtitle" => "O <b>TECHN&ART - Centro de Tecnologia, Restauro e Valorização das Artes</b> - é uma unidade de investigação e desenvolvimento do Instituto Politécnico de Tomar. O TECHN&ART reúne investigadores/as de múltiplas áreas disciplinares, com a missão de desenvolver estratégias e metodologias de investigação no âmbito da <b>Salvaguarda e Valorização do Património Artístico e Cultural</b>, nas suas diversas formas de expressão. Este trabalho desenvolve-se numa abordagem que se pretende sustentável, holística e transdisciplinar, com o propósito de ligar o presente ao passado. Este trabalho desenvolve-se numa abordagem que se pretende sustentável, holística e transdisciplinar, com o propósito de ligar o presente ao passado.",
        //Legenda 'Missao e Objetivos'
        "mission-and-goals-caption" => "MISSÃO E OBJETIVOS",
        //Legenda 'Eixos de Investigacao'
        "research-axes-caption" => "EIXOS DE INVESTIGAÇÃO",
        //Legenda 'Estrutra organica'
        "organic-struct-caption" => "ESTRUTURA ORGÂNICA",
        //Legenda 'Oportunidades'
        "opportunities-caption" => "OPORTUNIDADES",
        //botao 'SAIBA MAIS'
        "opportunities-know-more-btn" => "SAIBA MAIS",

        //::::::missao.php::::::

        //Titulo 'Missao e Objetivos'
        "mission-and-goals-page-heading" => "Missão e Objetivos",
        //ponto 1
        "mission-and-goals-page-point-one" => "O TECHN&ART desenvolve investigação nos domínios da Salvaguarda do Património e da sua Valorização, quer em desenvolvimento experimental, quer em investigação aplicada.",
        //ponto 2
        "mission-and-goals-page-point-two" => "Esta unidade de I&D tem por missão:",
        //ponto 2, alinea a)
        "mission-and-goals-page-a-txt" => "Contribuir para a consolidação dos programas de formação do IPT enquadrados nos domínios científicos listados;",
        //ponto 2, alinea b)
        "mission-and-goals-page-b-txt" => "Contribuir para a sólida formação das/os alunas/os estreitando a colaboração entre os trabalhos de investigação científica desenvolvidos pelas/os investigadoras/es TECHN&ART;",
        //ponto 2, alinea c)
        "mission-and-goals-page-c-txt" => "Difundir a cultura científica, tecnológica e artística através da organização de conferências, colóquios, seminários, exposições e sessões culturais;",
        //ponto 2, alinea d)
        "mission-and-goals-page-d-txt" => "Promover a formação avançada dos recursos humanos, fomentando a sua constante valorização científica e cultural;",
        //ponto 2, alinea e)
        "mission-and-goals-page-e-txt" => "Estabelecer a cooperação interinstitucional com entidades nacionais e internacionais;",
        //ponto 2, alinea f)
        "mission-and-goals-page-f-txt" => "Utilizar com eficácia os financiamentos de que é beneficiária e outros recursos disponíveis;",
        //ponto 2, alinea g)
        "mission-and-goals-page-g-txt" => "Prestar serviços à comunidade no âmbito das suas atividades.",
        //legenda da imagem
        //LEGENDA DA IMAGEM AQUI

        //::::::eixos.php::::::

        //Título 'Eixos de Investigacao'
        "axes-page-heading" => "Eixos de Investigação",
        //Descricao / texto da pagina 'Eixos de investigacao', logo abaixo do subtitulo
        "axes-page-p1-txt" => "O Centro de Tecnologia, Restauro e Valorização das Artes desenvolve estratégias e metodologias de investigação no âmbito de dois domínios:",
        //alinea a)
        "axes-page-a-txt" => "Salvaguarda",
        //alinea b)
        "axes-page-b-txt" => "Valorização do Património Artístico e Cultural",
        //paragrafo após o a e b
        "axes-page-p2-txt" => "O domínio da <b>Salvaguarda</b> é constituída por duas áreas de ação: <b>a1) Conservação e Restauro</b> e <b>a2) Caraterização e Contextualização do Património</b>:",
        //alinea a1)
        "axes-page-a-one-txt" => "<b>Conservação e Restauro –</b> esta área ação sustenta-se nos estudos de na intervenção de conservação e restauro no património artístico móvel e integrado, e onde as questões relacionadas com as metodologias, materiais, tecnologia e ética estão sistematicamente em discussão. Esta área reúne conservadores restauradores e investigadores que direta ou indiretamente participam nos projetos de investigação, desenvolvimento e intervenção para a salvaguarda do património artístico e cultural;",
        // alinea a2)
        "axes-page-a-two-txt" => "<b>Caraterização e Contextualização do Património –</b> esta área de ação sustenta-se nos estudos culturais, arqueológicos, históricos, artísticos, literários e também de caraterização física, química e biológica dos materiais e da respetiva alteração e alterabilidade da compatibilidade química e estrutural dos suportes a preservar e dos novos materiais a aplicar, considerando o meio de proveniência e também da sua preservação. Esta área reúne investigadores de diferentes formações que estudam, contextualizam e caracterizam o património material, imaterial e natural.",
        //parágrafo após o a1 e a2 antes do b1 e b2
        "axes-page-p3-txt" => "O domínio da <b>Valorização do Património Artístico e Cultural</b> reúne as áreas de ação:<b> b1) Didática, Tecnologia e Comunicação</b> e <b>b2) Design e Inovação:</b>",
        //alinea b1)
        "axes-page-b-one-txt" => "<b>Didática, Tecnologia e Comunicação</b> – esta área de ação sustenta-se nos estudos da educação, sensibilização e difusão do património cultural e artístico e respetiva preservação, a diferentes escalas. No quadro da didática, pretende-se uma simbiose entre o património, a interpretação patrimonial e o turismo numa lógica sustentável. Visa-se, assim, proporcionar aprendizagens ativas e integradas através da interpretação das manifestações do património cultural (material e imaterial), com elevado valor científico, didático, patrimonial e turístico. A gestão patrimonial na ótica da fruição proporcionará conhecimentos sobre as dinâmicas sociais e culturais da contemporaneidade. Assim, esta área de investigação, pode ser integrada na interação entre contextos de promoção da aprendizagem através da exploração de conexões didáticas, tecnológicas e comunicacionais. Estas metodologias e estratégias englobam e-learning, mobile-learning, objetos de aprendizagem, bibliotecas e repositórios de conteúdos digitais e gamification. Incluem-se ainda os ambientes imersivos, realidade aumentada, realidade virtual, sistemas de informação, sistemas multimédia e hipermédia, apps. Esta área de ação reúne investigadores no âmbito do turismo cultural, do cinema e vídeo documental, do design e da informática;",
        //alinea b2)
        "axes-page-b-two-txt" => "<b>Design e Inovação</b> – respeita à componente criativa, responde às funções estéticas, práticas e simbólicas dos produtos ou projetos e tem compromisso com a sociedade e sua envolvente de forma sustentável, inclusiva e inovadora. Esta área de ação considera aspetos tecnológicos, sociais, económicos e culturais, e trabalha com a forma e a função, tanto na comunicação como no produto, agindo em consonância com as necessidades materiais e culturais da sociedade. Reporta também formas de expressão e manifestações artísticas e culturais, tangíveis e intangíveis; no sentido de perpetuar a memória, sintetizada nas diversas manifestações, bem como descodificar ou reinterpretar o património à luz dos entendimentos, conceitos e linguagens atuais.",
        //Texto do fundo da página
        "bottom-text" => "Estas áreas de ação complementam-se e imbricam-se para que o todo que a missão do TECHN&ART seja coerente e tire partido do visando a transferência de conhecimento, de competências e de experiências de todas/os as/os investigadoras/es e colaboradoras/es do nosso centro.",

        //::::::estrutura.php::::::

        //Título 'Estrutura Organica'
        "org-struct-page-heading" => "Estrutura Orgânica",
        //Descricao / texto da pagina 'Estrutura Organica', logo abaixo do subtitulo
        "org-struct-page-description" => "A atividade do TECHN&ART é suportada pelos seguintes órgãos de direção, gestão e administração:",
        //Etiqueta 'Diretor'
        "org-struct-page-director-tag" => "Diretora",
        "director" => "<a href='/tecnart/integrado.php?integrado=29'>Hermínia Maria Pimenta Ferreira Sol</a>",
        //Etiqueta 'Diretor adjunto'
        "org-struct-page-deputy-director-tag" => "Diretora Adjunta",
        "deputy-director" => "<a href='/tecnart/integrado.php?integrado=36'>Ana Cláudia Leal Marques Pires da Silva</a>",
        //Etiqueta 'secretarios administrativos'
        "org-struct-page-executive-secretary-tag" => "Secretário Administrativo",
        "executive-secretary" => "Hirondina Alves São Pedro",
        //Etiqueta 'Conselho diretivo'
        "org-struct-page-board-tag" => "Conselho Diretivo",
        "board-composed" => "Composto pela Diretora, pela Diretora Adjunta e por:",
        "board-member1" => "<a href='/tecnart/integrado.php?integrado=68'>Eduardo Jorge Marques de Oliveira Ferraz</a>",
        "board-member2" => "<a href='/tecnart/integrado.php?integrado=119'>Liliana Cristina Vidais Rosa</a>",
        "board-member3" => "<a href='/tecnart/integrado.php?integrado=104'>João Pedro Tomaz Simões</a>",
        "board-member4" => "<a href='/tecnart/integrado.php?integrado=44'>Anabela Mendes Moreira</a>",
        "board-member5" => "<a href='/tecnart/integrado.php?integrado=90'>Miguel Duarte Antunes da Silva Jorge</a>",
        //Etiqueta 'Conselho cinetifico'
        "org-struct-page-scinetific-conucil-tag" => "Conselho Científico",
        "all-integrated-members" => "Composto por todos os membros integrados e colaboradores.",
        //Etiqueta 'Conselho consultivo'
        "org-struct-page-advisory-council-tag" => "Conselho Consultivo",
        //Elementos integrantes do conselho consultivo
        "advisory-council-one" => "Ana María Calvo Manuel, Faculdade de Belas Artes da Universidade Complutense de Madrid, Espanha.",
        "advisory-council-two" => "Chao Gejin, Instituto de Tradição Oral da Academia Chinesa de Ciências Sociais, Pequim, China",
        "advisory-council-three" => "José Julio García Arranz, Universidade da Extremadura, Espanha.",
        "advisory-council-four" => "Laurent Tissot, Universidade de Neuchâtel, Suíça.",
        "advisory-council-five" => "Maria Filomena Guerra, Centre national de la recherche scientifique, Paris, França.",
        "advisory-council-six" => "Zoltán Somhegyi, Universidade Károli Gáspár, Budapeste, Hungria.",

        //::::::oportunidades.php::::::

        //Titulo 'Oportunidades'
        "opport-page-heading" => "oportunidades",
        //Subtitulo
        "opport-page-subtitle" => "subtítulo",

        //::::::oportunidade.php::::::
        "opport-page-file" => "Ficheiros",

        //::::::projetos_em_curso.php::::::

        //Titulo 'Projetos em curso'
        "projects-ongoing-page-heading" => "Projetos em Curso",
        //Descricao pagina 'Projetos'
        "projects-ongoing-page-description" => "",

        //::::::projetos_concluidos.php::::::

        //Titulo 'Projetos concluídos'
        "projects-finished-page-heading" => "Projetos Concluídos",
        //Descricao pagina 'Projetos'
        "projects-finished-page-description" => "",

        //::::::projeto.php::::::

        //Classe css para todos os botoes 'Sobre o projeto'
        "about-project-btn-class" => "sobre o projeto",
        //Classe css para todos os titulos de separadores 'Sobre'
        "about-project-tab-title-class" => "Sobre o projeto",
        //Subtitulo separador 'sobre o projeto'
        "about-project-tab-subtitle-class" => "Subtitulo ",
        //Etiqueta de referencia do projeto
        "about-project-tab-reference-tag" => "Referência: ",
        //Etiqueta de area preferida do projeto
        "about-project-tab-main-research-tag" => "Eixo principal de investigação: ",
        //Etiqueta de financiamento do projeto
        "about-project-tab-financing-tag" => "Financiamento: ",
        //Etiqueta de escopo do projeto
        "about-project-tab-scope-tag" => "Âmbito: ",
        //Classe css para todos os botoes 'Equipa e Intervenientes'
        "team-steakholders-btn-class" => "equipa e intervenientes",
        //Classe css para todos os botoes 'Equipa e Intervenientes'
        "team-steakholders-tab-title-class" => "Equipa e intervenientes",
        //Subtitulo separador 'equipa e intervenientes'
        "team-steakholders-tab-subtitle-class" => "Subtitulo ",
        //

        //::::::integrados.php/colaboradores.php/alunos.php::::::

        //Titulo 'Investigadores/as Integrados/as'
        "integrated-researchers-page-heading" => "Investigadores/as Integrados/as",
        //Descricao de 'Investigadores/as Integrados/as'
        "integrated-researchers-page-heading-desc" => "",
        //Titulo 'Investigadores/as Colaboradores/as'
        "colaborative-researchers-page-heading" => "Investigadores/as Colaboradores/as",
        //Descricao de 'Investigadores/as Integrados/as'
        "colaborative-researchers-page-heading-desc" => "",
        //Titulo 'Investigadores/as Alunos/as'
        "student-researchers-page-heading" => "Colaboradores/as Estudantes",
        //Descricao de 'Investigadores/as Alunos/as'
        "student-researchers-page-heading-desc" => "",

        //::::::integrado.php/colaborador.php/aluno.php:::::

        //Classe css para todos os botoes 'Sobre'
        "about-btn-class" => "sobre",
        //Classe css para todos os titulos de separadores 'Sobre'
        "about-tab-title-class" => "Sobre",
        //Classe css para todos os botoes 'areas de interesse'
        "areas-btn-class" => "áreas de interesse",
        //Classe css para todos os titulos de separadores 'areas de interesse'
        "areas-tab-title-class" => "Áreas de interesse",
        //Classe css para todos os botoes 'publicacoes'
        "publications-btn-class" => "publicações",
        //Classe css para todos os titulos de separadores 'publicacoes'
        "publications-tab-title-class" => "Publicações",
        //Classe css para todos os botoes 'projetos'
        "projects-btn-class" => "projetos",
        //Classe css para todos os titulos de separadores 'projetos'
        "projects-tab-title-class" => "Projetos",
        //Texto 'Ligacoes Externas'
        "ext-links" => "Ligações externas",

        //::::::noticias.php::::::

        //Titulo pagina 'Noticias'
        "news-page-heading" => "Notícias",
        //Descricao pagina noticias
        "news-page-heading-desc" => "",

        //::::::oportunidades.php::::::

        //Titulo pagina 'Oportunidades'
        "opportunities-page-heading" => "Oportunidades",
        //Descricao pagina Oportunidades
        "opportunities-page-heading-desc" => "",

        //::::::publicacoes.php

        //Etiqueta 'Publicacoes'

        "publications-page-heading" => "Publicações",
        //Texto 'Ano Desconhecido'
        "year-unknown" => "Desconhecido",

        //:::::novasadimssoes.php
        "new-admissions-title" => "Novas admissões",
        "new-admissions-p1" => "A admissão de novos membros à equipa de investigação do TECHN&ART, integrados ou colaboradores, processa-se através de proposta ao conselho científico. O/A candidado/a deve preencher o formulário com as informações e a documentação necessária.",
        "new-admissions-p2" => "A admissão requererá que o/a candidado/a seja proposto/a por um membro integrado do TECHN&ART, servindo para o efeito a carta de recomendação pedida no formulário.",
        "new-admissions-regulations" => "O/A candidato deverá também consultar o",
        "new-admissions-regulations-link" => "regulamento geral do TECHN&ART.",
        "new-admissions-regulations-fill" => "Preencher Formulário",
        "new-admissions-regulations-file" => "Regulamento.pdf",
        "electoral-regulations-file" => "RegulamentoEleitoral.pdf",
        "electoral-calendar-2025-file" => "CalendárioEleicoes2025.pdf",
        "electoral-calendar-2025-file-rectification" => "DeclaracaoRetificacao.pdf",
        "electoral-calendar-2025-voters-notebook" => "CadernoEleitoral2025b.pdf",
        "electoral-calendar-2025-application-admitted" => "CandidaturaAdmitida2025.pdf",

        //:::admissao.php
        //Título
        "admission-title" => "Formulário de integração | TECHN&ART",
        //Mensagem informação após o título
        "admission-msg-1" => "Caro/a investigador/a.",
        "admission-msg-2" => "Muito obrigado pelo seu interesse em integrar a nossa unidade I&D - TECHN&ART. Para que a sua candidatura seja submetida a conselho científico, é necessário que seja preenchido este formulário.",
        "admission-msg-3" => "Caso seja necessário algum esclarecimento, não hesite em contactar o nosso secretariado, através do endereço",
        //Placeholder e Erro dos Inputs
        "admission-placeholder" => "Introduza a sua resposta",
        "admission-error" => "Por favor introduza um valor válido",
        //Etiquetas dos campos de input do formulário
        "admission-name" => "Nome completo",
        "admission-name-prof" => "Nome Profissional",
        "admission-cienciaid" => "Ciência ID",
        "admission-orcid" => "ORCID",
        "admission-email" => "Endereço de email",
        "admission-cellphone" => "Contacto telefónico",
        "admission-academic-qualifications" => "Grau Académico",
        "admission-year-conclusion" => "Ano de conclusão do grau académico",
        "admission-field-expertise" => "Área de especialização do Grau Académico",
        "admission-main-research-areas" => "Principais áreas de Investigação",
        "admission-institucional-affliation" => "Instituição de vínculo (data de início e fim, se aplicável [dd/mm/aaaa])",
        "admission-percentage-dedication-tech" => "Percentagem de dedicação ao TECHN&ART",
        "admission-member-another" => "Pertence a outro centro de investigação e desenvolvimento?",
        "admission-member-yes" => "Sim",
        "admission-member-no" => "Não",
        "admission-another-centre-info" => "Se SIM, qual, em que categoria e qual a percentagem de dedicação?",
        "admission-biography" => "Curta biografia de investigador/a (1-2 parágrafos) em português e inglês",
        //Etiquetas dos campos de ficheiros
        "admission-motivation" => "Carta de Motivação",
        "admission-recommendation" => "Carta de Recomendação do/a investigador/a do TECHN&ART proponente",
        "admission-cv" => "Curriculum Vitae",
        "admission-photo" => "Fotografia do/a investigador/a",
        //Botão Submeter
        "admission-submit" => "Submeter",
        "admission-cancel" => "Cancelar",
        //Mensagens de Submissão
        "admission-file-size-error" => "ERRO: O tamanho do ficheiro excede o limite máximo de " . MAX_FILE_SIZE . "MB",
        "admission-file-type-error" => "ERRO: O ficheiro tem de ser um pdf ou uma imagem (jpg, jpeg, png)",
        "admission-required-error" => "ERRO: Não foi possível obter os dados dos campos",
        "admission-send-error" => "ERRO Base de dados: Por favor, tente novamente mais tarde",
        "admission-successful" => "O formulário foi enviado com sucesso",

        //::::::financiamento.php::::::
        "funding-title" => "Financiamento",
        "funding-p1" => "Financiamento atribuído ao Centro de investigação Centro de Tecnologia, Restauro e Valorização de Artes (TECHN&ART)",
        "funding-table1-project" => "Projeto",
        "funding-table1-project-name" => "Unidade de I&D <br>Centro de Tecnologia, Restauro e Valorização das Artes (TECHN&ART)",
        "funding-table1-investigator" => "Investigador/Coordenador",
        "funding-table1-promoter" => "Promotor",
        "funding-table1-date-celebration" => "Data de celebração/Termo de Aceitação",
        "funding-table1-execution-period" => "Período de execução",
        "funding-table1-start-date" => "Data de início",
        "funding-table1-end-date" => "Data de fim",
        "funding-table1-total-investment" => "Investimento Total",
        "funding-table1-funding" => "Financiamento",
        "funding-p2" => "O financiamento global do centro de investigação corresponde à soma de duas parcelas:",
        "funding-title-2" => "Financiamento Base",
        "funding-title-3" => "Financiamento Programático",
        "funding-tables-universal-code" => "Referência",
        "funding-tables-funding" => "Financiamento",
        "funding-tables-execution-period" => "Período de execução",

        //:::::: copyright.php :::::::
        "copyright-title" => "Copyright",
        "copyright-p1" => "Website desenvolvido no ambito da unidade curricular de Projeto Final por:",
        "copyright-design" => "Design",
        "copyright-advisor" => "Orientador",
        "copyright-students" => "Alunos",

        //:::::: Concelho Consultivo :::::::
        "about-AnaCalvoManuel" => "Ana Calvo Manuel é Professora no Departamento de Pintura e Conservação-Restauro da Faculdade de Belas Artes da Universidade Complutense de Madrid. É doutorada em Belas Artes pela Universidade Politécnica de Valência no programa de Conservação do Património Histórico-Artístico e foi diretora do departamento de restauro no Conselho Provincial de Castellón. Conservadora-restauradora de renome, participou em vários projetos de investigação, colaborou com cursos de Mestrado e Doutoramento em Conservação e Restauro em Portugal e exerceu, igualmente, funções editoriais.",
        "about-ChaoGejin" => "Chao Gejin é Diretor do Instituto de Literatura Oral Tradicional da Academia Chinesa de Ciências Sociais, integra a Comissão Intergovernamental da UNESCO para o Património Cultural Imaterial e membro do comité executivo do Conselho Internacional para a Filosofia e Ciências Humanas, tendo detido anteriormente o cargo de Presidente. É doutorado em Folclore pela Universidade Normal de Pequim e especializa-se em literatura oral e património imaterial, particularmente na região Chinesa da Mongólia Interior.",
        "about-JoseJulioGarciaArranz" => "José Julio García Arranz é Professor Adjunto no Departamento de Arte e Ciências do Território na Universidade da Extremadura (Espanha) e membro do grupo de pesquisa Patrimonio&ARTE. É doutorado em História da Arte pela mesma Universidade e as suas áreas de investigação incluem Iconografia e Emblemática, bem como o Património Artístico da Região Autónoma da Extremadura, particularmente arte rupestre pré-histórica.",
        "about-LaurentTissot" => "Laurent Tissot é historiador. Foi membro do comité executivo do Conselho Internacional para a Filosofia e Ciências Humanas e tesoureiro para o Comité Internacional para as Ciências Históricas. Em estreita colaboração com este, coordena o projeto “História Global da Humanidade” [Global History of Humanity]. É doutorado em Ciências Políticas pela Universidade de Lausanne, tendo-se especializado em história do comércio, turismo e lazer. É Professor Emérito da Faculdade de Letras e Ciências Sociais da Universidade de Neuchântel.",
        "about-MariaFilomenaGuerra" => "Maria Filomena Guerra é diretora de investigação em Química no Centro Nacional de Investigação Científica francês (CNRS), atualmente membro da unidade de investigação MONARIS (UMR 8233 - de la Molécule aux Nano-Objets: Réactivité, Intéractions et Spectroscopies) na Sorbonne Université (Sciences). É doutorada em Física Aplicada pela Universidade Nova de Lisboa e habilitada a dirigir investigação em Ciência e Estrutura da matéria pela Universidade de Orleães. Dedica-se ao desenvolvimento e aplicação de métodos e protocolos físico-químicos ao estudo de objetos em ouro e prata do património cultural.",
        "about-ZoltanSomhegyi" => "Zoltán Somhegyi é Historiador da Arte com doutoramento em Estética e Agregação em Filosofia.  É Professor Associado de História da Arte no Departamento de Filosofia da Universidade de Szeged (Hungria). Como investigador, especializou-se em Arte e Teoria dos séculos XVIII e XIX, com particular incidência na estética das ruínas, da decadência e das representações da paisagem, bem como na estética ambiental, sendo os seus outros domínios de interesse as belas-artes contemporâneas e a crítica de arte. Foi Secretário-Geral (2016-2022) e é o atual Editor do site da Associação Internacional de Estética (IAA), e é o Secretário-Geral Adjunto do Conselho Internacional de Filosofia e Ciências Humanas (CIPSH, desde 2023). <br> <a target=\"_blank\" href=\"http://www.zoltansomhegyi.com\">http://www.zoltansomhegyi.com</a>",

        );

    return $dic_pt;
}
