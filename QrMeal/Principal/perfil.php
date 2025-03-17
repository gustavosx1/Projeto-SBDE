<?php
session_start();
require '../Banco de Dados/conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

// Verifica se o ID do usuário está na sessão
if (!$usuario_id) {
    header("Location: login.php"); // Redireciona para o login caso o usuário não esteja autenticado
    exit();
}

try {
    // Buscar os dados do usuário no banco de dados
    $stmt = $pdo->prepare("SELECT nome, idPessoa AS matricula, foto FROM pessoa WHERE idPessoa = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se o usuário não for encontrado, exibe erro
    if (!$usuario) {
        echo "Usuário não encontrado!";
        exit();
    }

    // Se a foto não estiver definida ou estiver vazia, redireciona para escolher imagem
    if (empty($usuario['foto'])) {
        header("Location: escolher_imagem.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados do usuário: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php'; ?>
</head>
<body>
    <div class="topo padbot fullW">
        <div class="voltar">
            <a href="menu.php">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>

    <div id="perfil">
        <img src="<?php echo (!empty($usuario['foto']) && file_exists('../uploads/' . $usuario['foto'])) 
                        ? '../uploads/' . $usuario['foto'] 
                        : '../midia/perfil.jpeg'; ?>" 
             alt="Foto de perfil">
    </div>

    <div class="info menu white" style="padding-top: 40%; height:60% !important">
        <h3 style="width: 100% !important; font-size:1.5em !important;"><?php echo htmlspecialchars($usuario['nome']); ?></h3>
        <table>
            <tr>
                <td>Nome</td>
                <td class="right"><?php echo htmlspecialchars($usuario['nome']); ?></td>
            </tr>
            <tr>
                <td>Matrícula</td>
                <td class="right"><?php echo htmlspecialchars($usuario['matricula']); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
