<?php
function gerarPixCopiaECola($chave, $valor, $descricao, $nome, $cidade) {
    $payload = "000201";
    
    $payload .= "26" . str_pad(strlen("0014br.gov.bcb.pix01" . strlen($chave) . $chave), 2, '0', STR_PAD_LEFT) . "0014br.gov.bcb.pix01" . strlen($chave) . $chave;
    
    if ($valor > 0) {
        $payload .= "54" . str_pad(strlen(number_format($valor, 2, '.', '')), 2, '0', STR_PAD_LEFT) . number_format($valor, 2, '.', '');
    }
    
    $payload .= "59" . str_pad(strlen($nome), 2, '0', STR_PAD_LEFT) . $nome;
    
    $payload .= "60" . str_pad(strlen($cidade), 2, '0', STR_PAD_LEFT) . $cidade;
    
    $payload .= "62" . str_pad(strlen("0503BRL"), 2, '0', STR_PAD_LEFT) . "0503BRL";
    
    $crc16 = strtoupper(dechex(crc16($payload . "6304")));
    $payload .= "6304" . str_pad($crc16, 4, '0', STR_PAD_LEFT);
    
    return $payload;
}

function crc16($str) {
    $crc = 0xFFFF;
    for ($i = 0; $i < strlen($str); $i++) {
        $crc ^= ord($str[$i]) << 8;
        for ($j = 0; $j < 8; $j++) {
            if ($crc & 0x8000) {
                $crc = ($crc << 1) ^ 0x1021;
            } else {
                $crc = $crc << 1;
            }
        }
    }
    return $crc & 0xFFFF;
}

$chavePix = "samueldsa03032005@gmail.com";
$valor = 1.00;
$descricao = "Pagamento Teste";
$nome = "Samuel Chaves";
$cidade = "Cururupu";

$pixCopiaECola = gerarPixCopiaECola($chavePix, $valor, $descricao, $nome, $cidade);
echo "Pix Copia e Cola: " . $pixCopiaECola;

$pixCopiaECola = urlencode($pixCopiaECola);
$qrcodeUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . $pixCopiaECola;

echo "<br><img src='$qrcodeUrl' alt='QR Code Pix'>";


?>