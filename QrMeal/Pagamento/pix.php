<?php
// Dados do pagamento
$chavePix = "samueldsa03032005@gmail.com"; // E-mail como chave Pix
$cpfRecebedor = "62778208348"; // CPF do recebedor
$nomeRecebedor = "Samuel Chaves de Sá";
$telefoneRecebedor = "+5598984884321"; // Número de telefone
$valor = isset($_POST['valorTotal']) ? $_POST['valorTotal'] : "3.00"; // Define o valor a ser pago (mínimo de R$ 3)

// Gerar o código Pix conforme o padrão simplificado
$pixData = [
    "chave" => $chavePix, // Chave Pix (pode ser CPF, CNPJ, ou e-mail)
    "valor" => $valor, // Valor a ser pago
    "nome" => $nomeRecebedor,
    "telefone" => $telefoneRecebedor,
];

// URL para a API de geração do QR Code do Banco do Brasil
$apiUrl = "https://api.bb.com.br/pix/qr-code"; // Este é um exemplo simplificado (certifique-se de que a API está disponível e configurada no Banco do Brasil)

// Configuração do cURL para gerar o QR Code
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer SEU_TOKEN_AQUI' // Substitua pelo seu token de acesso à API do Banco do Brasil
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pixData));

// Executa a requisição
$response = curl_exec($ch);

// Verifica erros
if ($response === false) {
    die('Erro na requisição cURL: ' . curl_error($ch));
}

curl_close($ch);

// Decodifica a resposta
$responseData = json_decode($response, true);

// Verifica se o QR Code foi gerado corretamente
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
