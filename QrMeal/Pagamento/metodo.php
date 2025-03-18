<?php
session_start();

$precoPrimeiroDia = 3.00;
$precoDiasExtras = 15.00;
$validadeTicket = null;
$dataTicket = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["pagamento"]) && !empty($_POST["dias"])) {
        require '../Banco de Dados/conexao.php';

        $metodoPagamento = $_POST["pagamento"];
        $dias = explode(",", $_POST["dias"]);
        $quantidadeDias = count($dias);

        // Calcula o valor total: o primeiro dia tem preço especial e os demais dias têm preço extra
        $valorTotal = $precoPrimeiroDia + ($quantidadeDias - 1) * $precoDiasExtras;

        // Converte a primeira data selecionada para o formato do banco (Y-m-d)
        $dataTicket = DateTime::createFromFormat('d/m/Y', reset($dias))->format('Y-m-d');
        
        // Define a validade do ticket para o mesmo dia às 19:30
        $validadeTicket = $dataTicket . " 23:59:00";

        // Verifica se o usuário está logado
        if (isset($_SESSION['usuario_id'])) {
            $usuario_id = $_SESSION['usuario_id'];
        } else {
            echo "<script>alert('Por favor, faça login para continuar.');</script>";
            header("Location: ../Inicio/login.php");
            exit();
        }

        try {
            // Gera um ID único para o ticket (garante até 20 caracteres)
            $ticket_id = uniqid();

            // Insere o ticket na tabela "ticket"
            $stmt = $pdo->prepare("INSERT INTO ticket (idPessoa, idTicket, dataTicket, dataValidade, tipoPagamento, dataCompra, valorTicket, utilizado) 
                                   VALUES (?, ?, ?, ?, ?, CURDATE(), ?, 0)");
            $stmt->execute([
                $usuario_id,
                $ticket_id,
                $dataTicket,
                $validadeTicket,
                $metodoPagamento,
                $valorTotal
            ]);

            echo "<script>alert('Ticket criado com sucesso!');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao criar ticket: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Selecione pelo menos um dia e o método de pagamento antes de prosseguir.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Tickets - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <div class="topo white fullW">
        <div class="voltar">
            <a href="../Principal/menu.php">
                <img src="../midia/voltar.png" alt="Voltar">
                <p>Voltar</p>
            </a>
        </div>
        <img id="logo" src="../midia/QrMeal1.png" alt="Logo">
    </div>
    <h3 class="colorWhite">Comprar Tickets</h3>

    <div class="info menu">
        <h2>Selecione os dias:</h2>
        <input type="text" id="calendario" name="calendario" placeholder="Escolha os dias">

        <div class="button btwhite">
            <p>Valor total: R$ <span id="valorTotal" data-preco-primeiro="<?php echo $precoPrimeiroDia; ?>" data-preco-extra="<?php echo $precoDiasExtras; ?>">0.00</span></p>
            <form method="POST" id="pagamento">
                <input type="hidden" name="dias" id="diasSelecionados">
                <input type="hidden" name="valor" id="valorTotalInput">
                
                <input type="radio" name="pagamento" id="pix" value="pix">
                <label for="pix">Pix</label>
                
                <input type="radio" name="pagamento" id="cartao" value="cartao">
                <label for="cartao">Cartão</label>
                
                <!-- A parte de seleção de cartão pode ser mantida para exibição,
                     mas não é gravada na tabela "ticket" conforme a nova estrutura -->
                <div id="cartaoSelecionado" style="display: none;">
                    <label for="cartao_id">Selecione o Cartão:</label>
                    <select name="cartao_id" id="cartao_id">
                        <option value="1">Cartão 1234</option>
                        <option value="2">Cartão 5678</option>
                    </select>
                </div>
                
                <button type="submit" class="btwhite green" style="font-size:20px">Confirmar</button>
            </form>
        </div>
    </div>

    <!-- Scripts para o flatpickr e para manipulação dos elementos do formulário -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let precoPrimeiroDia = parseFloat(document.getElementById("valorTotal").dataset.precoPrimeiro);
            let precoExtra = parseFloat(document.getElementById("valorTotal").dataset.precoExtra);
            let valorTotalElem = document.getElementById("valorTotal");
            let inputDias = document.getElementById("diasSelecionados");
            let inputValorTotal = document.getElementById("valorTotalInput");
            let selectedDates = [];

            flatpickr("#calendario", {
                locale: "pt",
                mode: "single",
                dateFormat: "d/m/Y",
                minDate: "today",
                disable: [
                    function (date) {
                        return date.getDay() === 0; // Desabilita domingos
                    }
                ],
                onChange: function (dates, dateStr) {
                    selectedDates = dates;
                    
                    let total = precoPrimeiroDia + (selectedDates.length - 1) * precoExtra;

                    valorTotalElem.innerText = total.toFixed(2);
                    inputDias.value = dateStr;
                    inputValorTotal.value = total.toFixed(2);
                }
            });

            document.getElementById("cartao").addEventListener("change", function() {
                document.getElementById("cartaoSelecionado").style.display = 'block';
            });

            document.getElementById("pix").addEventListener("change", function() {
                document.getElementById("cartaoSelecionado").style.display = 'none';
            });

            document.getElementById("pagamento").addEventListener("submit", function (event) {
                if (selectedDates.length === 0) {
                    event.preventDefault();
                    alert("Por favor, selecione pelo menos um dia antes de confirmar o pagamento.");
                }
            });
        });
    </script>

</body>
</html>
