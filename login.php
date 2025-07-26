<?php
session_start();

// if (isset($_SESSION['user_id'])) {
//     header('Location: dashboard.php');
//     exit();
// }

if (!file_exists('config.php')) {
    header('Location: install.php');
    exit();
}

require_once 'config.php';

$error = '';

if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT id, username, password, nombre FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nombre'] = $user['nombre'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
        } catch (Exception $e) {
            $error = 'Error de conexión';
        }
    } else {
        $error = 'Complete todos los campos';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Sistema de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Sistema de Ventas</h3>
                    </div>
                    <div class="card-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label>Usuario</label>
                                <input type="text" name="username" class="form-control" required 
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>
                        
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong>Credenciales Demo:</strong><br>
                            Usuario: <code>demo</code><br>
                            Contraseña: <code>tareafacil25</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>