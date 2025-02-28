<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor de QR Code</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php' ?>
</head>

<body>
        <script>
            // Obtém acesso à câmera
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                })
                .then(function(stream) {
                    let video = document.getElementById('video');
                    video.srcObject = stream;
                })
                .catch(function(err) {
                    console.error("Erro ao acessar a câmera:", err);
                    alert("Erro ao acessar a câmera. Verifique as permissões do navegador.");
                });

            function scanQRCode() {
                let video = document.getElementById('video');
                let canvas = document.getElementById('canvas');
                let ctx = canvas.getContext('2d');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                let code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    document.getElementById('resultado').innerText = code.data;
                    alert("QR Code lido: " + code.data);
                }

                requestAnimationFrame(scanQRCode);
            }

            document.getElementById('video').addEventListener("play", function() {
                scanQRCode();
            });
        </script>

    <body class="">
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
            <canvas id="canvas" style="display: none;"></canvas>
            <h2>Resultado: <span id="resultado" style="color: white;">Aguardando...</span></h2>
        </div>

        <!-- Biblioteca jsQR -->
        <script src="https://cdn.jsdelivr.net/npm/jsqr"></script>

    </body>

</html>