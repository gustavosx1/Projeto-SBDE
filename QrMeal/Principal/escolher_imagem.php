<?php
session_start();
require '../Banco de Dados/conexao.php'; // Certifique-se de que esse arquivo conecta corretamente ao banco de dados

$usuario_id = $_SESSION['usuario_id']; // Obtendo o ID do usuário logado

if (!isset($pdo)) {
    die("Erro na conexão com o banco de dados.");
}

// Verifica se o usuário já tem uma foto cadastrada
$sql = "SELECT foto FROM pessoa WHERE idPessoa = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && !empty($usuario['foto'])) {
    // Se já houver imagem, redireciona para o perfil
    header("Location: perfil.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $diretorio = '../uploads/'; // Pasta onde as imagens serão salvas
    
    // Criando diretório se não existir
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    
    if (in_array($extensao, $extensoes_permitidas)) {
        $novo_nome = uniqid('perfil_', true) . '.' . $extensao;
        $caminho_arquivo = $diretorio . $novo_nome;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_arquivo)) {
            try {
                $sql = "UPDATE pessoa SET foto = ? WHERE idPessoa = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$caminho_arquivo, $usuario_id]);
                
                // Redireciona para o perfil após o upload bem-sucedido
                header("Location: perfil.php");
                exit();
            } catch (PDOException $e) {
                echo "Erro ao salvar o link da imagem no banco de dados: " . $e->getMessage();
            }
        } else {
            echo "Erro ao mover o arquivo para a pasta de uploads.";
        }
    } else {
        echo "Formato de arquivo não permitido. Use JPG, JPEG, PNG ou GIF.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Imagem de Perfil</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Escolha sua imagem de perfil</h2>
    <form action="escolher_imagem.php" method="post" enctype="multipart/form-data">
        <input class="btwhite" type="file" name="foto" required>
        <button class="btwhite" type="submit">Enviar Imagem</button>
    </form>
</body>
</html>
