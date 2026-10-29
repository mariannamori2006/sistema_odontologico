<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/BaseModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db(): PDO
{
    static $db = null;
    return $db ??= (new Database())->conectar();
}

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function csrf(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function validarCsrf(): void
{
    if (!hash_equals(csrf(), (string) ($_POST['csrf'] ?? ''))) {
        throw new RuntimeException('Token de seguridad no válido');
    }
}

function exigirSesion(): void
{
    if (empty($_SESSION['uid'])) {
        header('Location: login.php');
        exit;
    }
}

function redirigir(string $ruta): void
{
    header('Location: ' . $ruta);
    exit;
}

function notificar(string $mensaje): void
{
    $_SESSION['flash'] = $mensaje;
}

function validarDatos(array $campos, array $datos): array
{
    $salida = [];
    $referencias = ['duenos', 'mascotas', 'veterinarios', 'servicios', 'citas', 'diagnosticos'];
    $opciones = [
        'estado_cita' => ['pendiente', 'confirmada', 'atendida', 'cancelada'],
        'estado_pago' => ['pendiente', 'pagado', 'anulado'],
        'metodo_pago' => ['contado', 'tarjeta_credito', 'tarjeta_debito', 'yape'],
    ];

    foreach ($campos as $campo => $tipo) {
        $valor = trim((string) ($datos[$campo] ?? ''));

        if ($tipo === 'number' && $valor !== '' && (!is_numeric($valor) || (float) $valor < 0)) {
            throw new RuntimeException('Número inválido: ' . $campo);
        }

        if ($tipo === 'email' && $valor !== '' && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Correo inválido');
        }

        if (in_array($tipo, $referencias, true) && ($valor === '' || !ctype_digit($valor) || (int) $valor < 1)) {
            throw new RuntimeException('Seleccione una referencia válida: ' . $campo);
        }

        if ($tipo === 'datetime-local' && $valor !== '') {
            $fecha = DateTime::createFromFormat('!Y-m-d\TH:i', $valor);
            if (!$fecha || $fecha->format('Y-m-d\TH:i') !== $valor) {
                throw new RuntimeException('Fecha y hora inválidas');
            }
            $valor = $fecha->format('Y-m-d H:i:s');
        }

        if ($tipo === 'date' && $valor !== '') {
            $fecha = DateTime::createFromFormat('!Y-m-d', $valor);
            if (!$fecha || $fecha->format('Y-m-d') !== $valor) {
                throw new RuntimeException('Fecha inválida');
            }
        }

        if (isset($opciones[$tipo]) && !in_array($valor, $opciones[$tipo], true)) {
            throw new RuntimeException('Selección inválida');
        }

        $salida[$campo] = $valor === '' ? null : $valor;
    }

    return $salida;
}

function modulos(): array
{
    return [
        'duenos' => ['nombre' => 'Dueños', 'url' => 'duenos.php'],
        'veterinarios' => ['nombre' => 'Veterinarios', 'url' => 'veterinarios.php'],
        'mascotas' => ['nombre' => 'Mascotas', 'url' => 'mascotas.php'],
        'servicios' => ['nombre' => 'Servicios', 'url' => 'servicios.php'],
        'citas' => ['nombre' => 'Citas veterinarias', 'url' => 'citas.php'],
        'diagnosticos' => ['nombre' => 'Diagnósticos', 'url' => 'diagnosticos.php'],
        'tratamientos' => ['nombre' => 'Tratamientos', 'url' => 'tratamientos.php'],
        'pagos' => ['nombre' => 'Pagos', 'url' => 'pagos.php'],
        'reportes' => ['nombre' => 'Reportes clínicos', 'url' => 'reportes.php'],
    ];
}
