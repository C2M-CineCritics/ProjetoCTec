<?php
// Configuração de conexão com o banco de dados
$host = "localhost";        // Host do servidor MySQL
$user = "root";             // Usuário do banco
$pass = "";                 // Senha do banco
$db = "cinecritics";        // Nome do banco de dados

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Capturar o termo de pesquisa
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Validar se há termo de busca
if (empty($query)) {
    echo "<p class='text-center text-danger'>Por favor, insira um termo para pesquisa.</p>";
    exit;
}

// Consulta SQL para buscar resenhas de filmes e séries
$sql = "SELECT id, titulo, sinopse, tipo, imagem_url, data_lancamento 
        FROM resenhas 
        WHERE (tipo = 'filme' OR tipo = 'serie') 
          AND (titulo LIKE ? OR sinopse LIKE ?)";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param("ss", $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto - CineCritics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            padding-top: 70px; /* Espaço para a navbar */
            background-color: #000;
        }

        /* Navbar */
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
        <div class="text-center fs-4 bg-warning">
          •
        </div>
<div class="container mt-5">
    <h1 class="text-center text-danger">Resultados para: "<?= htmlspecialchars($query) ?>"</h1>
    <div class="row mt-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($resenha = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?= htmlspecialchars($resenha['imagem_url']) ?>" class="card-img-top" alt="Imagem da resenha">
                        <div class="card-body">
                            <h5 class="card-title text-danger"><?= htmlspecialchars($resenha['titulo']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(mb_strimwidth($resenha['sinopse'], 0, 100, '...')) ?></p>
                            <p class="text-muted small">Tipo: <?= htmlspecialchars($resenha['tipo']) ?></p>
                            <p class="text-muted small">Lançamento: <?= date("d/m/Y", strtotime($resenha['data_lancamento'])) ?></p>
                            <a href="detalhes_resenha.php?id=<?= $resenha['id'] ?>" class="btn btn-warning">Leia mais</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">Nenhum resultado encontrado para "<?= htmlspecialchars($query) ?>".</p>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

            <div class="text-center fs-4 bg-warning">
              •
            </div>
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

<?php
$conn->close();
?>
