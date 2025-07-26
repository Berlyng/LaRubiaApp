<?php
$instalado = file_exists('config.php');
$destino = $instalado ? 'login.php' : 'install.php';
$mensaje = $instalado
    ? "Sistema detectado. Redirigiendo al login..."
    : "No se ha detectado instalación. Redirigiendo al instalador...";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Redirigiendo...</title>
    <meta http-equiv="refresh" content="3;url=<?php echo $destino; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container text-center">
        <div class="card shadow p-4 mx-auto" style="max-width: 400px;">
            <h4 class="mb-3">Bienvenido al Sistema</h4>
            <div class="spinner-border text-primary mb-3" role="status"></div>
            <p class="lead"><?php echo $mensaje; ?></p>
            <p class="text-muted">Serás redirigido en unos segundos...</p>
            <a href="<?php echo $destino; ?>" class="btn btn-outline-primary">Ir ahora</a>
        </div>
    </div>
</body>
</html>
