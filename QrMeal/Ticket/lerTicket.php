<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor de QR Code</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php'; ?>
</head>

<body>
    <div class="topo">
        <div class="voltar">
            <a href="ticket.php">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>

    <div class="info">
        <h2>Escaneie o QR Code</h2>
        <video id="video" autoplay></video>
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
    </script>
</body>
</html>
