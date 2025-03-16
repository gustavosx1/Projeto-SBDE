<?php
require 'vendor/autoload.php'; // Carrega o Composer

use MercadoPago\SDK;
use MercadoPago\Payment;

// Configurar Mercado Pago
SDK::setAccessToken("SEU_ACCESS_TOKEN_AQUI"); // Insira sua chave de acesso aqui

// Recebe o valor do formulário ou define um valor padrão
$valor = isset($_POST['valorTotal']) ? floatval($_POST['valorTotal']) : 3.00;

// Criar pagamento PIX
$payment = new Payment();
$payment->transaction_amount = $valor;
$payment->description = "Pagamento Restaurante Universitário";
$payment->payment_method_id = "pix";
$payment->payer = array(
    "email" => "cliente@email.com"
);
$payment->save(); // Salva a transação no Mercado Pago

// Obter QR Code e código Pix
if (isset($payment->point_of_interaction->transaction_data->qr_code_base64)) {
    $qrCodeBase64 = $payment->point_of_interaction->transaction_data->qr_code_base64;
    $pixCopiaCola = $payment->point_of_interaction->transaction_data->ticket_url;
} else {
    die("Erro ao gerar QR Code PIX");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Pix - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="topo">
        <div class="voltar">
            <a href="../Principal/ticket.php">
                <img src="../midia/voltar.png" alt="Voltar">
                <p>Voltar</p>
            </a>
        </div>
    </div>
    <div class="info">
        <h3 style="color: white;">Escaneie o QR Code ou copie o código Pix</h3>
        
        <div class="button btwhite padtop">
            <!-- Exibe o QR Code gerado -->
            <img src="data:image/png;base64,<?= $qrCodeBase64 ?>" alt="QR Code Pix">
        </div>

        <div class="codigo btwhite">
            <p id="codigoPix"><?= htmlspecialchars($pixCopiaCola) ?></p>
        </div>

        <h3 style="color: white;">Valor total: R$ <?= number_format($valor, 2, ',', '.') ?></h3>
        
        <button type="button" class="button btwhite" onclick="copiarCodigo()">Copiar código Pix</button>
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
