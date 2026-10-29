<?php
require_once __DIR__ . '/../controller/AuthController.php';

$error = '';
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        validarCsrf();
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'registro') {
            $auth->registrar(
                trim($_POST['nombre'] ?? ''),
                trim($_POST['email'] ?? ''),
                $_POST['password'] ?? ''
            );
            $error = 'Usuario creado. Ahora puedes iniciar sesión.';
        } elseif ($accion === 'login') {
            if ($auth->ingresar(trim($_POST['email'] ?? ''), $_POST['password'] ?? '')) {
                redirigir('home.php');
            }
            $error = 'Credenciales incorrectas.';
        }
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetCare | Acceso</title>
    <link rel="stylesheet" href="../assetes/css/vet.css">
</head>
<body class="auth">
    <div class="auth-box">
        <div class="brand">🐾 VetCare <small>Gestión veterinaria</small></div>
        <h2>Bienvenido</h2>

        <?php if ($error !== ''): ?>
            <p role="status"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">

            <label for="nombre">Nombre (solo para registrarse)</label>
            <input id="nombre" name="nombre" placeholder="Nombre completo">

            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" autocomplete="email" required>

            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>

            <div class="actions">
                <button type="submit" name="accion" value="login">Ingresar</button>
                <button type="submit" name="accion" value="registro" class="secondary">Registrarme</button>
                <button type="reset" class="secondary">Cancelar</button>
            </div>
        </form>
        <p class="auth-note">Para registrarte, escribe tu nombre, correo y una contraseña de al menos 10 caracteres.</p>
    </div>
</body>
</html>
