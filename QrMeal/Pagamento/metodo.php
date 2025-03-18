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

        // Calcula o valor total corretamente
        $valorTotal = $precoPrimeiroDia + ($quantidadeDias - 1) * $precoDiasExtras;

        $dataTicket = DateTime::createFromFormat('d/m/Y', reset($dias))->format('Y-m-d');
        
        // Definir a validade do ticket para o mesmo dia às 19:00
        $validadeTicket = $dataTicket . " 19:30:00";

        if (isset($_SESSION['usuario_id'])) {
            $usuario_id = $_SESSION['usuario_id'];
        } else {
            echo "<script>alert('Por favor, faça login para continuar.');</script>";
            header("Location: ../Inicio/login.php");
            exit();
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO ticket (idTicket, dataTicket, dataValidade, valorTicket, utilizado, pessoa_idPessoa) 
                                   VALUES (UUID(), ?, ?, ?, 0, ?)");
            $stmt->execute([$dataTicket, $validadeTicket, $valorTotal, $usuario_id]);

            $ticket_id = $pdo->lastInsertId();

            $cartao_id = null;
            if ($metodoPagamento == 'cartao') {
                if (isset($_POST['cartao_id'])) {
                    $cartao_id = $_POST['cartao_id'];
                } else {
                    echo "<script>alert('Selecione um cartão válido para o pagamento.');</script>";
                    exit();
                }
            }

            $stmtPagamento = $pdo->prepare("INSERT INTO pagamento (pessoa_idPessoa, ticket_idTicket, metodoPagamento, dataCompra, cartao_idCartao) 
                                           VALUES (?, ?, ?, NOW(), ?)");
            $stmtPagamento->execute([$usuario_id, $ticket_id, $metodoPagamento, $cartao_id]);

            $stmtCarteira = $pdo->prepare("SELECT * FROM carteira WHERE pessoa_idPessoa = ? AND cartao_idCartao = ?");
            $stmtCarteira->execute([$usuario_id, $cartao_id]);

            if ($stmtCarteira->rowCount() == 0 && $metodoPagamento == 'cartao') {
                $stmtCarteiraInsert = $pdo->prepare("INSERT INTO carteira (pessoa_idPessoa, cartao_idCartao) VALUES (?, ?)");
                $stmtCarteiraInsert->execute([$usuario_id, $cartao_id]);
            }

            echo "<script>alert('Pagamento realizado com sucesso!');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao processar pagamento: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Selecione pelo menos um dia e método de pagamento antes de prosseguir.');</script>";
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
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
        <img id="logo" src="../midia/QrMeal1.png" alt="">
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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let precoPrimeiroDia = parseFloat(document.getElementById("valorTotal").dataset.precoPrimeiro);
            let precoExtra = parseFloat(document.getElementById("valorTotal").dataset.precoExtra);
            let valorTotal = document.getElementById("valorTotal");
            let inputDias = document.getElementById("diasSelecionados");
            let inputValorTotal = document.getElementById("valorTotalInput");
            let selectedDates = [];

            flatpickr("#calendario", {
                locale: "pt",
                mode: "multiple",
                dateFormat: "d/m/Y",
                minDate: "today",
                disable: [
                    function (date) {
                        return date.getDay() === 0;
                    }
                ],
                onChange: function (dates, dateStr) {
                    selectedDates = dates;
                    
                    let total = precoPrimeiroDia + (selectedDates.length - 1) * precoExtra;

                    valorTotal.innerText = total.toFixed(2);
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
