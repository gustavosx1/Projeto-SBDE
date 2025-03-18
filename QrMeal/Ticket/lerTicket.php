<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor de QR Code - Marcar Ticket</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php'; ?>
</head>

<body>
    <div class="topo fullW">
        <div class="voltar">
            <a href="../Principal/menuFunc.php">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>
    
    <div class="info">
        <video id="video" autoplay></video>
        <h2>Escaneie o QR Code ou Insira o ID do Ticket</h2>
        
        <!-- Formulário para inserir manualmente o ticket_id -->
        <form id="ticketForm">
            <label for="ticket_id">ID do Ticket:</label>
            <input type="text" id="ticket_id" name="ticket_id" required>

            <button class="btwhite" type="submit">Marcar como Utilizado</button>
        </form>

        <h3>Informações do Ticket</h3>
        <div id="ticket_info" style="display: none;">
            <table border="1">
                <tr>
                    <th>Ticket ID</th>
                    <th>Data de Validade</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
                <tr id="ticket_row">
                    <td id="ticket_id_cell"></td>
                    <td id="validade"></td>
                    <td id="valor"></td>
                    <td id="status"></td>
                </tr>
            </table>
        </div>

        <!-- Área do vídeo para escaneamento -->
        <h2>Resultado: <span id="resultado" style="color: white;">Aguardando...</span></h2>
    </div>

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>
        let video = document.getElementById("video");
        let resultado = document.getElementById("resultado");

        let scanner = new Instascan.Scanner({ video: video });

        scanner.addListener('scan', function(content) {
            resultado.innerText = content;
            alert("QR Code lido: " + content);
        });

        // Iniciar a câmera
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                let cameraTraseira = cameras.find(cam => cam.name.toLowerCase().includes("back")) || cameras[0];
                scanner.start(cameraTraseira);
            } else {
                alert("Nenhuma câmera encontrada.");
            }
        }).catch(function(e) {
            console.error(e);
            alert("Erro ao acessar a câmera. Verifique as permissões.");
        });

        // Processar o formulário do ticket_id
        document.getElementById('ticketForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            let ticket_id = document.getElementById('ticket_id').value;
            
            // Enviar o ticket_id para o backend via AJAX
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "marcarTicket.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Atualiza a tabela com os dados do ticket
                        document.getElementById('ticket_info').style.display = 'block';
                        document.getElementById('ticket_id_cell').innerText = response.ticket.ticket_id;
                        document.getElementById('validade').innerText = response.ticket.validade;
                        document.getElementById('valor').innerText = response.ticket.valor;
                        document.getElementById('status').innerText = response.ticket.status;
                    } else {
                        alert('Erro ao marcar o ticket como utilizado ou ticket não encontrado.');
                    }
                }
            };
            xhr.send("ticket_id=" + ticket_id);
        });
    </script>
</body>
</html>
