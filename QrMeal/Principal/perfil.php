<?php
session_start();
require '../Banco de Dados/conexao.php';  // Certifique-se de que a conexão com o banco de dados esteja sendo feita corretamente

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Inicio/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

try {
    // Buscar os dados do usuário no banco de dados
    $stmt = $pdo->prepare("SELECT nome, idPessoa AS matricula, foto FROM pessoa WHERE idPessoa = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Caso o usuário não seja encontrado
    if (!$usuario) {
        echo "Usuário não encontrado!";
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
    <?php include '../Inicio/config.php' ?>
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

    <!-- Exibir foto, nome e matrícula -->
    <div id="perfil">
        <!-- Verificar se a foto do usuário existe, caso contrário, exibe uma foto padrão -->
        <img src="<?php echo $usuario['foto'] ? '../midia/' . $usuario['foto'] : '../midia/perfil.jpeg'; ?>" alt="Foto de perfil">
    </div>

    <div class="info menu white" style="padding-top: 40%; height:60% !important">
        <h3><?php echo htmlspecialchars($usuario['nome']); ?></h3>
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
