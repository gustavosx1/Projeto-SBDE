<?php
$chavePix = "samueldsa03032005@gmail.com";
$cpfRecebedor = "62778208348";
$nomeRecebedor = "Samuel Chaves de Sá";
$telefoneRecebedor = "+5598984884321";
$valor = isset($_POST['valorTotal']) ? $_POST['valorTotal'] : "3.00";

$pixData = [
    "chave" => $chavePix,
    "valor" => $valor,
    "nome" => $nomeRecebedor,
    "telefone" => $telefoneRecebedor,
];

$apiUrl = "https://api.bb.com.br/pix/qr-code";
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer SEU_TOKEN_AQUI'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pixData));

$response = curl_exec($ch);

if ($response === false) {
    die('Erro na requisição cURL: ' . curl_error($ch));
}

curl_close($ch);

$responseData = json_decode($response, true);

if (isset($responseData['qrcode'])) {
    $qrCodeUrl = $responseData['qrcode'];
} else {
    die('Erro ao obter QR Code: ' . json_encode($responseData));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="topo">
        <div class="voltar">
            <a href="../Principal/ticket.php">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>
    <div class="info">
        <h3 style="color: white;">Escaneie ou copie o código do Qr Code</h3>
        <div class="button btwhite padtop">
            <!-- Exibe o QR Code gerado -->
            <img src="<?= $qrCodeUrl ?>" alt="QR Code Pix">
        </div>
        <div class="codigo btwhite">
            <p id="codigoPix"><?= htmlspecialchars($pixData['chave']) ?></p>
        </div>
        <h3 style="color: white;">Valor total: R$ <?= $valor ?></h3>
        <button type="button" class="button btwhite" onclick="copiarCodigo()">Copiar código</button>
    </div>

    <script>
        function copiarCodigo() {
            let codigo = document.getElementById("codigoPix").innerText;
            navigator.clipboard.writeText(codigo).then(() => {
                alert("Código Pix copiado!");
            }).catch(err => {
                alert("Erro ao copiar: " + err);
            });
        }
    </script>
</body>
</html>
