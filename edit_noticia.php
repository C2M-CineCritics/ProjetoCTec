<?php
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
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

// Variável para armazenar erros
$error = "";

// Carrega os dados da notícia para edição
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM noticias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $noticia = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$noticia) {
        die("Notícia não encontrada.");
    }
}

// Atualiza os dados da notícia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die("ID inválido.");
    }

    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $descricao_breve = $_POST['descricao_breve'];
    $descricao_completa = $_POST['descricao_completa'];
    $data_publicacao = $_POST['data_publicacao'];
    $imagem_url = $_POST['imagem_url'];

    // Formata a data para o padrão DATETIME do MySQL
    $data_publicacao = date('Y-m-d H:i:s', strtotime($data_publicacao));

    $sql = "UPDATE noticias SET titulo = ?, descricao_breve = ?, descricao_completa = ?, data_publicacao = ?, imagem_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("sssssi", $titulo, $descricao_breve, $descricao_completa, $data_publicacao, $imagem_url, $id);

    if ($stmt->execute()) {
        header("Location: list_noticias.php");
        exit;
    } else {
        $error = "Erro ao atualizar notícia: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="text-center text-warning">Editar Notícia</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" class="mt-4">
            <input type="hidden" name="id" value="<?= $noticia['id'] ?>">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao_breve" class="form-label">Descrição Breve</label>
                <textarea class="form-control" id="descricao_breve" name="descricao_breve" rows="2" required><?= htmlspecialchars($noticia['descricao_breve']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="descricao_completa" class="form-label">Descrição Completa</label>
                <textarea class="form-control" id="descricao_completa" name="descricao_completa" rows="5" required><?= htmlspecialchars($noticia['descricao_completa']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="data_publicacao" class="form-label">Data de Publicação</label>
                <input type="datetime-local" class="form-control" id="data_publicacao" name="data_publicacao" 
                    value="<?= date('Y-m-d\TH:i', strtotime($noticia['data_publicacao'])) ?>" required>
            </div>
            <div class="mb-3">
                <label for="imagem_url" class="form-label">URL da Imagem</label>
                <input type="text" class="form-control" id="imagem_url" name="imagem_url" value="<?= htmlspecialchars($noticia['imagem_url']) ?>">
            </div>
            <button type="submit" class="btn btn-warning w-100">Salvar Alterações</button>
        </form>
        <a href="list_noticias.php" class="btn btn-light mt-3">Voltar</a>
    </div>
</body>
</html>
