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
        #imglogo {
    border-radius: 20px;
}
nav {
    font-size: 20px;
}
table {
    background-color: #000; /* Fundo preto */
    border-radius: 8px; /* Bordas arredondadas */
    overflow: hidden; /* Garante que as bordas arredondadas sejam aplicadas corretamente */
    color: #fff; /* Texto branco para contraste */
}

th, td {
    border-color: #444; /* Cor das bordas entre as células */
}

thead {
    background-color: #222; /* Fundo para o cabeçalho */
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

<!-- Indicador de seção -->
<div class="text-center fs-4 bg-warning">
    •
</div>

    <div class="container mt-5">
        <h1 class="text-center text-danger">Resenhas de Filmes</h1>
        <table class="table table-striped table-hover mt-4">
            <thead>
                <tr>
                    
                    <th>Título</th>
                    <th>Data de Lançamento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexão com o banco de dados
                $conn = new mysqli("localhost", "root", "", "cinecritics");

                if ($conn->connect_error) {
                    die("Erro de conexão: " . $conn->connect_error);
                }

                // Consulta para listar apenas filmes
                $sql = "SELECT id, titulo, data_lancamento, diretor FROM resenhas WHERE tipo = 'filme' ORDER BY data_lancamento DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    $contador = 1; // Contador para o índice das linhas
                    while ($resenha = $result->fetch_assoc()):
                ?>
                    <tr>                        
                        <td><?= $resenha['titulo'] ?></td>
                        <td><?= date("d/m/Y", strtotime($resenha['data_lancamento'])) ?></td>
                        <td>
                            <a href="detalhes_resenha.php?id=<?= $resenha['id'] ?>" class="btn btn-sm btn-danger">Ver Detalhes</a>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhuma resenha de filmes encontrada.</td>
                    </tr>
                <?php
                endif;

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <div class="text-center fs-4 bg-warning">
    •
</div>

<!-- Rodapé -->
<footer style="background-color: #000;">
    <img src="IMG/cinecriticslogo3.png" class="rounded mx-auto d-block mb-3" alt="Logo CineCritics">
    <p class="fs-6 text-white">CineCritics, C2M Company - 2024 todos os direitos reservados.</p>
    <div>
        <a href="#">Terms and Privacy Notice</a>
        <a href="admin_dashboard.php"> P.A</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
