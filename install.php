<?php
session_start();

$message = '';
$error = '';

// Procesar instalación
if ($_POST) {
    $host = $_POST['host'] ?? 'localhost';
    $username = $_POST['username'] ?? 'root';
    $password = $_POST['password'] ?? 'santa123#';
    $database = $_POST['database'] ?? 'LaRubiaApp_Db';
    
    try {
        // Crear conexión
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Crear base de datos
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$database`");
        
        // Crear tabla usuarios
        $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            nombre VARCHAR(100),
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Crear usuario demo
        $demo_password = password_hash('tareafacil25', PASSWORD_BCRYPT);
        $pdo->prepare("INSERT IGNORE INTO usuarios (username, password, nombre) VALUES (?, ?, ?)")
            ->execute(['demo', $demo_password, 'Usuario Demo']);
        
        // Crear archivo config.php
        $config = "<?php
define('DB_HOST', '$host');
define('DB_USER', '$username');
define('DB_PASS', '$password');
define('DB_NAME', '$database');

function getDB() {
    \$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return \$pdo;
}
?>";
if (file_exists('config.php')) {
    die("El sistema ya fue instalado. Borra 'config.php' si deseas reinstalar.");
}

        
        file_put_contents('config.php', $config);
        
        $message = "Instalación completada. Usuario: demo, Contraseña: tareafacil25";
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Instalador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Instalador del Sistema</h3>
                    </div>
                    <div class="card-body">
                        <?php if($message): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                            <a href="login.php" class="btn btn-primary">Ir al Login</a>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if(!$message): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Servidor</label>
                                <input type="text" name="host" class="form-control" value="localhost" required>
                            </div>
                            <div class="mb-3">
                                <label>Usuario DB</label>
                                <input type="text" name="username" class="form-control" value="root" required>
                            </div>
                            <div class="mb-3">
                                <label>Contraseña DB</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Nombre BD</label>
                                <input type="text" name="database" class="form-control" value="Sistema_Ventas" required>
                            </div>
                            <button type="submit" class="btn btn-success">Instalar</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>