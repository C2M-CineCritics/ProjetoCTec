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

$sql = "SELECT * FROM resenhas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Resenhas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="text-center text-warning">Resenhas Cadastradas</h1>
        <table class="table table-dark table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($resenha = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $resenha['id'] ?></td>
                        <td><?= $resenha['titulo'] ?></td>
                        <td><?= ucfirst($resenha['tipo']) ?></td>
                        <td>
                            <a href="edit_resenha.php?id=<?= $resenha['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-light mt-3">Voltar ao Dashboard</a>
    </div>
</body>
</html>
