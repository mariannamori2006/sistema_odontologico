<?php
require_once __DIR__ . '/../controller/BaseController.php';
exigirSesion();
require __DIR__ . '/layout.php';

// Solo el ID de la foto de Unsplash
$imagenes = [
    'pacientes'    => 'photo-1663755489920-5e09f66d011a',
    'odontologos'  => 'photo-1588776814546-daab30f310ce',
    'servicios'    => 'photo-1629909613654-28e377c37b09',
    'citas'        => 'photo-1698749778813-ad5f2814e50f',
    'diagnosticos' => 'photo-1600170311833-c2cf5280ce49',
    'tratamientos' => 'photo-1606811971618-4486d14f3f99',
    'pagos'        => 'photo-1628527304948-06157ee3c8a6',
    'reportes'     => 'photo-1562240020-ce31ccb0fa7d',
];

// Descripciones cortas para cada tarjeta
$textos = [
    'pacientes'    => 'Fichas y datos de contacto',
    'odontologos'  => 'Equipo y especialidades',
    'servicios'    => 'Catálogo y precios',
    'citas'        => 'Agenda de consultas',
    'diagnosticos' => 'Evaluaciones clínicas',
    'tratamientos' => 'Planes y seguimiento',
    'pagos'        => 'Cobros y comprobantes',
    'reportes'     => 'Estadísticas e historial',
];

function imagenModulo(array $imagenes, string $id): string
{
    if (!isset($imagenes[$id])) {
        return 'imagenes/dental_logo.png'; // imagen de respaldo
    }
    return 'https://images.unsplash.com/' . $imagenes[$id] . '?auto=format&fit=crop&w=700&h=420&q=75';
}

layoutStart('Panel de control', 'style_home.css');
?>
<p>Administra la atención, el historial clínico, los servicios y los pagos.</p>

<div class="cards">
    <?php foreach (modulos() as $id => $modulo): ?>
        <a class="card" href="<?= e($modulo['url']) ?>">
            <img
                loading="lazy"
                alt="<?= e($modulo['nombre']) ?>"
                src="<?= e(imagenModulo($imagenes, $id)) ?>"
            >
            <div>
                <h2><?= e($modulo['nombre']) ?></h2>
                <span><?= e($textos[$id] ?? 'Administrar registros') ?> →</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php layoutEnd(); ?>