<?php
require_once __DIR__ . '/../controller/BaseController.php';
exigirSesion();
require __DIR__ . '/layout.php';

$imagenes = [
    'duenos' => 'photo-1558788353-f76d92427f16',
    'veterinarios' => 'photo-1576091160399-112ba8d25d1d',
    'mascotas' => 'photo-1583511655857-d19b40a7a54e',
    'servicios' => 'photo-1516734212186-a967f81ad0d7',
    'citas' => 'photo-1517849845537-4d257902454a',
    'diagnosticos' => 'photo-1576200419199-391ae4be7a41',
    'tratamientos' => 'photo-1559757148-5c350d0d3c56',
    'pagos' => 'photo-1556742049-0cfed4f6a45d',
    'reportes' => 'photo-1456513080510-7bf3a84b82f8',
];

layoutStart('Panel de control');
?>
<p>Administra la atención, el historial clínico, los servicios y los pagos.</p>
<div class="cards">
    <?php foreach (modulos() as $id => $modulo): ?>
        <a class="card" href="<?= e($modulo['url']) ?>">
            <img
                loading="lazy"
                alt="<?= e($modulo['nombre']) ?>"
                src="https://images.unsplash.com/<?= e($imagenes[$id]) ?>?w=700&amp;q=75"
            >
            <div>
                <h2><?= e($modulo['nombre']) ?></h2>
                <span>Administrar registros →</span>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php layoutEnd(); ?>
