<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "cinecritics";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM resenhas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resenha = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$resenha) {
        die("Resenha não encontrada.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $sinopse = $_POST['sinopse'];
    $imagem_url = $_POST['imagem_url'];
    $video_url = $_POST['video_url'];
    $tipo = $_POST['tipo'];
    $temporadas = $_POST['temporadas'];
    $data_lancamento = $_POST['data_lancamento'];
    $diretor = $_POST['diretor'];

    $sql = "UPDATE resenhas SET titulo = ?, sinopse = ?, imagem_url = ?, video_url = ?, tipo = ?, temporadas = ?, data_lancamento = ?, diretor = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $titulo, $sinopse, $imagem_url, $video_url, $tipo, $temporadas, $data_lancamento, $diretor, $id);

    if ($stmt->execute()) {
        header("Location: list_resenhas.php");
        exit;
    } else {
        $error = "Erro ao atualizar resenha: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Resenha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="text-center text-warning">Editar Resenha</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" class="mt-4">
            <input type="hidden" name="id" value="<?= $resenha['id'] ?>">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= $resenha['titulo'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="sinopse" class="form-label">Sinopse</label>
                <textarea class="form-control" id="sinopse" name="sinopse" rows="4" required><?= $resenha['sinopse'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="imagem_url" class="form-label">URL da Imagem</label>
                <input type="text" class="form-control" id="imagem_url" name="imagem_url" value="<?= $resenha['imagem_url'] ?>">
            </div>
            <div class="mb-3">
                <label for="video_url" class="form-label">URL do Vídeo</label>
                <input type="text" class="form-control" id="video_url" name="video_url" value="<?= $resenha['video_url'] ?>">
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="filme" <?= $resenha['tipo'] === 'filme' ? 'selected' : '' ?>>Filme</option>
                    <option value="serie" <?= $resenha['tipo'] === 'serie' ? 'selected' : '' ?>>Série</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="temporadas" class="form-label">Temporadas</label>
                <input type="number" class="form-control" id="temporadas" name="temporadas" value="<?= $resenha['temporadas'] ?>">
            </div>
            <div class="mb-3">
                <label for="data_lancamento" class="form-label">Data de Lançamento</label>
                <input type="date" class="form-control" id="data_lancamento" name="data_lancamento" value="<?= $resenha['data_lancamento'] ?>">
            </div>
            <div class="mb-3">
                <label for="diretor" class="form-label">Diretor</label>
                <input type="text" class="form-control" id="diretor" name="diretor" value="<?= $resenha['diretor'] ?>">
            </div>
            <button type="submit" class="btn btn-warning w-100">Salvar Alterações</button>
        </form>
        <a href="list_resenhas.php" class="btn btn-light mt-3">Voltar</a>
    </div>
</body>
</html>
