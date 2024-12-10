<?php
session_start();

// Verificar se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Lógica para fazer logout
if (isset($_GET['logout'])) {
    session_destroy(); // Encerra a sessão
    header("Location: index.php"); // Redireciona para a página inicial
    exit;
}

// Configuração do banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$db = "cinecritics";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Adicionar ou editar notícias e resenhas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adicionar Notícia
    if (isset($_POST['add_news'])) {
        $titulo = $_POST['titulo'];
        $descricao_breve = $_POST['descricao_breve'];
        $descricao_completa = $_POST['descricao_completa'];
        $imagem_url = $_POST['imagem_url'];

        // Usar a data e hora fornecida ou definir como atual se estiver vazia
        $data_publicacao = !empty($_POST['data_publicacao']) 
            ? date('Y-m-d H:i:s', strtotime($_POST['data_publicacao']))
            : date('Y-m-d H:i:s');

        $sql = "INSERT INTO noticias (titulo, descricao_breve, descricao_completa, data_publicacao, imagem_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $titulo, $descricao_breve, $descricao_completa, $data_publicacao, $imagem_url);
        $stmt->execute();
        $stmt->close();
        $message = "Notícia adicionada com sucesso!";
    }

    // Adicionar Resenha
    if (isset($_POST['add_review'])) {
        $titulo = $_POST['titulo'];
        $sinopse = $_POST['sinopse'];
        $imagem_url = $_POST['imagem_url'];
        $video_url = $_POST['video_url'];
        $tipo = $_POST['tipo'];
        $temporadas = $_POST['temporadas'] ?: null;
        $data_lancamento = $_POST['data_lancamento'];
        $diretor = $_POST['diretor'];

        $sql = "INSERT INTO resenhas (titulo, sinopse, imagem_url, video_url, tipo, temporadas, data_lancamento, diretor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $titulo, $sinopse, $imagem_url, $video_url, $tipo, $temporadas, $data_lancamento, $diretor);
        $stmt->execute();
        $stmt->close();
        $message = "Resenha adicionada com sucesso!";
    }
}

// Obter dados existentes para exibição e edição
$noticias = $conn->query("SELECT * FROM noticias ORDER BY data_publicacao DESC")->fetch_all(MYSQLI_ASSOC);
$resenhas = $conn->query("SELECT * FROM resenhas ORDER BY data_lancamento DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="text-center text-warning">Painel Administrativo</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <h2>Gerenciar Notícias</h2>
        <form method="POST" class="mb-5">
            <input type="hidden" name="add_news" value="1">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="mb-3">
                <label for="descricao_breve" class="form-label">Descrição Breve</label>
                <textarea class="form-control" id="descricao_breve" name="descricao_breve" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label for="descricao_completa" class="form-label">Descrição Completa</label>
                <textarea class="form-control" id="descricao_completa" name="descricao_completa" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="imagem_url" class="form-label">URL da Imagem (ex.:https://via.placeholder.com/350x150)</label>
                <input type="text" class="form-control" id="imagem_url" name="imagem_url" required>
            </div>
            <div class="mb-3">
                <label for="data_publicacao" class="form-label">Data e Hora de Publicação</label>
                <input type="datetime-local" class="form-control" id="data_publicacao" name="data_publicacao">
            </div>
            <button type="submit" class="btn btn-warning">Adicionar Notícia</button>
        </form>

        <h2>Gerenciar Resenhas</h2>
        <form method="POST">
            <input type="hidden" name="add_review" value="1">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="mb-3">
                <label for="sinopse" class="form-label">Sinopse</label>
                <textarea class="form-control" id="sinopse" name="sinopse" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="imagem_url" class="form-label">URL da Imagem (ex.:https://via.placeholder.com/350x150)</label>
                <input type="text" class="form-control" id="imagem_url" name="imagem_url" required>
            </div>
            <div class="mb-3">
                <label for="video_url" class="form-label">URL do Vídeo (ex.:https://www.youtube.com/embed/Y6mihRBWw-Y?si=tYCRiqr-6jv-WhF1)</label>
                <input type="text" class="form-control" id="video_url" name="video_url">
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="filme">Filme</option>
                    <option value="serie">Série</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="temporadas" class="form-label">Temporadas (se for Série)</label>
                <input type="number" class="form-control" id="temporadas" name="temporadas">
            </div>
            <div class="mb-3">
                <label for="data_lancamento" class="form-label">Data de Lançamento</label>
                <input type="date" class="form-control" id="data_lancamento" name="data_lancamento">
            </div>
            <div class="mb-3">
                <label for="diretor" class="form-label">Diretor</label>
                <input type="text" class="form-control" id="diretor" name="diretor">
            </div>
            <button type="submit" class="btn btn-warning">Adicionar Resenha</button>
        </form>
        <br>
        <a href="list_resenhas.php" class="btn btn-warning">Editar Resenhas</a>
        <br><br>
        <a href="list_noticias.php" class="btn btn-warning">Editar Notícias</a>
        <br><br>
        <a href="index.php" class="btn btn-light">Voltar para Home</a>
        <a href="?logout=1" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
