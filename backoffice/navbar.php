<?php
// Garantir que sessão está ativa e evitar headers já enviados
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Evita qualquer output acidental
ob_start();
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
    }

    .navUl {
        width: 100%;
        list-style-type: none;
        margin: 0;
        padding: 0;
        background-color: #435d7d;
        font-size: 17px;
        font-family: 'Varela Round';
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        position: relative;
        z-index: 10;
    }

    .navGroup {
        display: flex;
        flex-wrap: wrap;
    }

    .navExit {
        display: flex;
    }

    .navLi {
        position: relative;
    }

    .navLi a, .dropbtn {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        cursor: pointer;
    }

    .navLi a:hover, .dropbtn:hover {
        background-color: #324c6c;
        color: white;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #435d7d;
        min-width: 200px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        z-index: 9999;
    }

    .dropdown-content a {
        color: white;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        font-size: 13px;
    }

    .dropdown-content a:hover {
        background-color: #324c6c;
    }

    .navLi:hover .dropdown-content {
        display: block;
    }

    .leftnav {
        color: white;
        background-color: #ff0000;
        padding: 14px 16px;
        text-align: center;
        text-decoration: none;
    }

    .leftnav:hover {
        background-color: #dd0000;
    }

    @media screen and (max-width: 768px) {
        .navUl {
            flex-direction: column;
            align-items: flex-start;
        }

        .navGroup, .navExit {
            flex-direction: column;
            width: 100%;
        }

        .navLi {
            width: 100%;
        }

        .dropdown-content {
            position: static;
            box-shadow: none;
        }
    }
</style>

<ul class="navUl">
    <div class="navGroup">
        <li class="navLi"><a href="../Views">Dashboard das Visitas no Site</a></li>
        <li class="navLi">
            <span class="dropbtn">Sobre o TECHN&amp;ART</span>
            <div class="dropdown-content">
                <a href="../missao">Missão e Objetivos</a>
                <a href="../eixos_de_investigação">Eixos de Investigação</a>
                <a href="../estrutura_organica">Estrutura Orgânica</a>
                <a href="../carrosel">Carrosel</a>
            </div>
        </li>

        <li class="navLi">
            <span class="dropbtn">Pessoas</span>
            <div class="dropdown-content">
                <?php if ($_SESSION["autenticado"] === "administrador"): ?>
                    <a href="../administradores">Administradores</a>
                <?php endif; ?>
                <a href="../investigadores">Investigadores</a>
                <a href="../conselho_consultivo">Conselho Consultivo</a>
            </div>
        </li>

        <li class="navLi"><a href="../projetos">Projetos</a></li>
        <li class="navLi"><a href="../noticias">Notícias</a></li>
        <li class="navLi"><a href="../oportunidades">Oportunidades</a></li>
        <?php if ($_SESSION["autenticado"] === "administrador"): ?>
            <li class="navLi"><a href="../admissoes">Admissões</a></li>
        <?php endif; ?>
        <li class="navLi"><a href="../documentos">Documentos</a></li>
    </div>

    <div class="navExit">
        <li class="navLi"><a class="leftnav" href="../sair.php">Sair</a></li>
    </div>
</ul>

<?php ob_end_flush(); ?>
