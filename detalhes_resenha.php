<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Resenha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Imagem no topo */
        .top-image {
            width: 100%;
            height: auto;
            max-height: 70vh;
        }

        /* Vídeo responsivo */
        .video-container {
        position: relative;
        padding-bottom: 56.25%; /* Proporção 16:9 */
        height: 0;
        max-width: 400px; /* Limite de largura para desktops */
        margin: 0 auto; /* Centraliza no container */
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    @media (max-width: 768px) { 
        .video-container {
            max-width: 100%; /* Permite expandir em telas menores */
        }
    }

        #imglogo {
            margin-right: 10px;
            border-radius: 20px;
        }

        body {
            padding-top: 70px; /* Espaço para navbar */
            background-color: #000;
            color: #fff;
        }

        footer {
            background-color: #000;
            padding: 20px 0;
            text-align: center;
            color: #fff;
        }

        footer a {
            color: #ffc107;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }

        nav {
            font-size: 20px;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-danger fixed-top">
        <div class="container-fluid">
            <img id="imglogo" src="IMG/CineCriticsLogo.png" alt="CineCritics" width="50" height="50">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="resenhas_filme.php">Filmes</a></li>
                    <li class="nav-item"><a class="nav-link" href="resenhas_serie.php">Séries</a></li>
                    <li class="nav-item"><a class="nav-link" href="noticias.php">Notícias</a></li>
                </ul>
                <form class="d-flex" role="search" method="GET" action="buscar_resenhas.php" onsubmit="return validarPesquisa()">
                        <input id="barraPesquisa" class="form-control me-2" type="search" name="query" placeholder="Pesquisar" aria-label="Search">
                        <button class="btn btn-outline-warning" type="submit">Pesquisar</button>
                    </form>

                    <script>
                        function validarPesquisa() {
                        const barraPesquisa = document.getElementById('barraPesquisa');
                        if (barraPesquisa.value.trim() === "") {
                            alert("Por favor, digite algo na barra de pesquisa antes de enviar.");
                        return false; // Impede o envio do formulário
                    }
                return true; // Permite o envio do formulário
                }
                </script>
            </div>
        </div>
    </nav>
</header>

<?php
// Configuração de conexão com o banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$db = "Cinecritics";

// Criar conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Obter o ID da resenha
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Buscar a resenha no banco de dados
$sql = "SELECT titulo, tipo, data_lancamento, sinopse, diretor, imagem_url, video_url, temporadas FROM resenhas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$resenha = $result->fetch_assoc();

if (!$resenha) {
    echo "<div class='container mt-5'><p class='text-center text-danger'>Resenha não encontrada!</p></div>";
    exit;
}
?>

<!-- Imagem da resenha no topo -->
<img src="<?= htmlspecialchars($resenha['imagem_url']) ?>" class="top-image" alt="Imagem de <?= htmlspecialchars($resenha['titulo']) ?>">

<div class="text-center fs-4 bg-warning">
    •
</div>

<div class="container mt-5">
    <h1 class="text-danger"><?= htmlspecialchars($resenha['titulo']) ?></h1>
    <p>
        Tipo: <?= ucfirst(htmlspecialchars($resenha['tipo'])) ?> | 
        Diretor: <?= htmlspecialchars($resenha['diretor']) ?> | 
        Data de Lançamento: <?= date("d/m/Y", strtotime($resenha['data_lancamento'])) ?>
    </p>

    <?php if ($resenha['tipo'] === 'serie'): ?>
        <p class="text-warning">Temporadas: <?= htmlspecialchars($resenha['temporadas']) ?></p>
    <?php endif; ?>

    <h2 class="text-warning">Sinopse</h2>
    <p><?= nl2br(htmlspecialchars($resenha['sinopse'])) ?></p>

    <h2 class="text-warning">Resenha em Vídeo</h2>
    <div class="video-container">
        <iframe src="<?= htmlspecialchars($resenha['video_url']) ?>" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="text-center mt-5">
        <a href="<?= $resenha['tipo'] === 'filme' ? 'resenhas_filme.php' : 'resenhas_serie.php' ?>" class="btn btn-secondary">Voltar</a>
    </div>
</div>

<div class="text-center fs-4 bg-warning">
    •
</div>

<footer>
    <img src="IMG/cinecriticslogo3.png" class="rounded mx-auto d-block mb-3" alt="Logo CineCritics">
    <p>CineCritics, C2M Company - 2024 todos os direitos reservados.</p>
    <div>
        <a href="#">Terms and Privacy Notice</a>
        <a href="admin_dashboard.php"> P.A</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
