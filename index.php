<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineCritics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        /* Imagem no topo sem cortes */
        .top-image {
            width: 100%;
            height: auto; /* Ajusta a altura automaticamente */
            max-height: 70vh; /* Define um limite para a altura, ajustável */
            display: block; /* Garante que seja tratada como um bloco */
        }

        #imglogo {
            margin-right: 10px;
            border-radius: 20px;
        }

        body {
            padding-top: 70px; /* Espaço para navbar */
            background-color: #000;
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
        .carousel-inner a {
            color: #ffc107;
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
    
    <main style="background-color: #2c2c2c;">
      <!--Merchandising-->
      <div class="text-center fs-4 bg-warning">
      Promoção: Ao assinar um plano no Disney+, usando o cupom: CineCritics20 você ganha 20% de desconto.
    </div>
    <!--Carrosel de Notícias-->
    <div id="carrosel-de-noticias">
    <?php
    // Conexão com o banco de dados
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "cinecritics";
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    // Buscar as 3 notícias mais recentes
    $sql = "SELECT id, titulo, descricao_breve, imagem_url FROM noticias ORDER BY data_publicacao DESC LIMIT 3";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        $active = true; // Para marcar a primeira notícia como "active"
    ?>
    <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
            <?php for ($i = 0; $i < $result->num_rows; $i++): ?>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
            <?php endfor; ?>
        </div>
        <div class="carousel-inner">
            <?php while ($noticia = $result->fetch_assoc()): ?>
            <div class="carousel-item <?= $active ? 'active' : '' ?>">
            <div class="bg-dark opacity-25">
                <img src="<?= htmlspecialchars($noticia['imagem_url']) ?>" class="top-image" alt="Imagem da Notícia" > </div>
                <div class="carousel-caption">
                    <h5><?= htmlspecialchars($noticia['titulo']) ?></h5>
                    <a href="noticia.php?id=<?= $noticia['id'] ?>">Clique e saiba mais</a>
                </div>
            </div>
            <?php 
            $active = false; // Apenas a primeira deve ser ativa
            endwhile; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <?php
    else:
        echo "<p class='text-center text-muted'>Nenhuma notícia encontrada para exibir no carrossel.</p>";
    endif;

    $conn->close();
    ?>
</div>

        </div>
        <div class="text-center fs-4 bg-warning">
          •
        </div>
        
    </main>
    
    <!--Rodapé-->
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
