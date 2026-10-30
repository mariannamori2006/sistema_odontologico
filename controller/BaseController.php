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
    $referencias = ['pacientes', 'odontologos', 'servicios', 'citas', 'diagnosticos'];
    $opciones = [
        'estado_cita' => ['pendiente', 'confirmada', 'atendida', 'cancelada'],
        'estado_pago' => ['pendiente', 'pagado', 'anulado'],
        'metodo_pago' => ['efectivo', 'tarjeta_credito', 'tarjeta_debito', 'yape'],
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
        'pacientes' => ['nombre' => 'Pacientes', 'url' => 'pacientes.php'],
        'odontologos' => ['nombre' => 'Odontólogos', 'url' => 'odontologos.php'],
        'servicios' => ['nombre' => 'Servicios', 'url' => 'servicios.php'],
        'citas' => ['nombre' => 'Citas', 'url' => 'citas.php'],
        'diagnosticos' => ['nombre' => 'Diagnósticos', 'url' => 'diagnosticos.php'],
        'tratamientos' => ['nombre' => 'Tratamientos', 'url' => 'tratamientos.php'],
        'pagos' => ['nombre' => 'Pagos', 'url' => 'pagos.php'],
        'reportes' => ['nombre' => 'Reportes clínicos', 'url' => 'reportes.php'],
    ];
}

function etiqueta(string $campo): string
{
    $nombres = [
        'paciente_id' => 'Paciente', 'odontologo_id' => 'Odontólogo',
        'servicio_id' => 'Servicio', 'cita_id' => 'Cita',
        'diagnostico_id' => 'Diagnóstico',
        'fecha_nacimiento' => 'Fecha de nacimiento', 'fecha_hora' => 'Fecha y hora',
        'fecha_inicio' => 'Fecha de inicio', 'fecha_fin' => 'Fecha de fin',
        'fecha_pago' => 'Fecha de pago', 'fecha' => 'Fecha',
        'telefono' => 'Teléfono', 'direccion' => 'Dirección',
        'descripcion' => 'Descripción', 'duracion_min' => 'Duración (min)',
        'email' => 'Correo electrónico', 'metodo' => 'Método de pago',
        'titulo' => 'Título', 'colegiatura' => 'Colegiatura',
    ];
    return $nombres[$campo] ?? ucfirst(str_replace('_', ' ', $campo));
}