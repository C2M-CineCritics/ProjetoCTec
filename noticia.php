<?php
// Configuração do banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$db = "cinecritics";

// Conectar ao banco
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Capturar o ID da notícia
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar os detalhes da notícia
if ($id > 0) {
    $stmt = $conn->prepare("SELECT titulo, descricao_breve, descricao_completa, data_publicacao, imagem_url FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $noticia = $result->fetch_assoc();
    } else {
        die("Notícia não encontrada.");
    }
} else {
    die("ID inválido.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Notícia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Imagem no topo sem cortes */
        .top-image {
            width: 100%;
            height: auto; /* Ajusta a altura automaticamente */
            max-height: 70vh; /* Define um limite para a altura, ajustável */
            display: block; /* Garante que seja tratada como um bloco */
        }

        body {
            padding-top: 70px; /* Espaço para a navbar */
            background-color:#000;
        }

        /* Logo da Navbar */
        #imglogo {
            margin-right: 10px;
            border-radius: 20px;
        }

        /* Rodapé */
        footer {
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: #ffc107; /* Links em amarelo para contraste */
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }
        nav {
            font-size: 20px;
        }
        .container p {
            color:#fff;
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
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="resenhas_filme.php">Filmes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="resenhas_serie.php">Séries</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="noticias.php">Notícias</a>
                        </li>
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

    <!-- Imagem da notícia no topo -->
    <img src="<?= htmlspecialchars($noticia['imagem_url']) ?>" class="top-image" alt="Imagem da Notícia">

    <div class="text-center fs-4 bg-warning">
          •
        </div>

    <div class="container mt-5">
        <h1 class="text-danger"><?= htmlspecialchars($noticia['titulo']) ?></h1>
        <p class="text-warning">Publicado em: <?= date("d/m/Y H:i", strtotime($noticia['data_publicacao'])) ?></p>
        <p><?= nl2br(htmlspecialchars($noticia['descricao_completa'])) ?></p>
        <a href="noticias.php" class="btn btn-secondary mt-4">Voltar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <footer>
      <br>
      <img src="IMG\cinecriticslogo3.png" class="rounded mx-auto d-block" alt="...">
        <p class="fs-6 text-white text-center">CineCritics, C2M Company - 2024 todos os direitos reservados. </p>
        <div class="text-center">
        <a class="" href=""> Terms and Privacy Notice</a>
        <a href="admin_dashboard.php"> P.A</a>
        </div> <br>
    </footer>
</body>
</html>
<?php $conn->close(); ?>
