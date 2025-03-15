<?php
$precoTicket = 3.00;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pagamento"]) && !empty($_POST["dias"])) {
        $metodoPagamento = $_POST["pagamento"];
        $quantidadeDias = count(explode(",", $_POST["dias"]));
        $valorTotal = $quantidadeDias * $precoTicket;

        $destino = ($metodoPagamento == "pix") ? "pix.php" : "cartao.php";

        echo "<form id='redirectForm' action='{$destino}' method='POST'>
                <input type='hidden' name='valor' value='" . number_format($valorTotal, 2, ".", "") . "'>
                <input type='hidden' name='dias' value='{$_POST["dias"]}'>
              </form>
              <script>document.getElementById('redirectForm').submit();</script>";
        exit();
    } else {
        echo "<script>alert('Selecione pelo menos um dia antes de prosseguir.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <div class="topo white">
        <div class="voltar">
            <a href="../Principal/menu.php" style="width: 18%;">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
        <img id="logo" src="../midia/QrMeal1.png" alt="">
    </div>
    <h3 class="white">Comprar Tickets</h3>

    <div class="info menu">
        <h2 for="calendario">Selecione os dias:</h2>
        <input type="text" id="calendario" name="calendario" placeholder="Escolha os dias">

        <div class="button btwhite">
            <p>Valor total: R$ <span id="valorTotal">0.00</span></p>
            <form method="POST" id="pagamento">
                <input type="hidden" name="dias" id="diasSelecionados">
                <input type="hidden" name="valor" id="valorTotalInput">
                
                <input type="radio" name="pagamento" id="pix" value="pix">
                <label for="pix">Pix</label>
                
                <input type="radio" name="pagamento" id="cartao" value="cartao">
                <label for="cartao">Cartão</label>
                
                <button type="submit" class="btwhite" style="font-size:20px">Confirmar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        let precoTicket = <?php echo $precoTicket; ?>;
        let valorTotal = document.getElementById("valorTotal");
        let inputDias = document.getElementById("diasSelecionados");
        let inputValorTotal = document.getElementById("valorTotalInput");
        let selectedDates = [];

        flatpickr("#calendario", {
            mode: "multiple",
            dateFormat: "d/m/Y",
            minDate: "today",
            disable: [
                function(date) {
                    return date.getDay() === 0;
                }
            ],
            onChange: function(dates, dateStr) {
                selectedDates = dates;
                let total = selectedDates.length * precoTicket;
                valorTotal.innerText = total.toFixed(2);
                inputDias.value = dateStr;
                inputValorTotal.value = total.toFixed(2);
            }
        });

        document.getElementById("pagamento").addEventListener("submit", function(event) {
            if (selectedDates.length === 0) {
                event.preventDefault();
                alert("Por favor, selecione pelo menos um dia antes de confirmar o pagamento.");
            }
        });
    </script>

</body>

</html>
