<?php
require '..\vendor\autoload.php';

use MercadoPago\SDK;

SDK::setAccessToken("SEU_ACCESS_TOKEN_AQUI");

echo "Mercado Pago SDK funcionando!";
?>