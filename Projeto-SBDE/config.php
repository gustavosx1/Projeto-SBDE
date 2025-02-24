<?php
$modoContraste = isset($_COOKIE["modoContraste"]) && $_COOKIE["modoContraste"] == "alto" ? "rgb(0, 0, 0)" : "rgb(30, 50, 49)";
?>
<style>
    body {
        background-color: <?php echo $modoContraste; ?>;
        color: white;
    }
</style>