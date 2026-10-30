<?php
// Página actual (pacientes.php, citas.php...) para que "Cancelar" vuelva a ella
$pagina = basename($_SERVER['PHP_SELF']);

// Tipo de campo (lo que define cada controlador) => tabla y texto a mostrar en el <select>
$referencias = [
    'pacientes'    => ['tabla' => 'paciente',    'etiqueta' => '`nombre`'],
    'odontologos'  => ['tabla' => 'odontologo',  'etiqueta' => '`nombre`'],
    'servicios'    => ['tabla' => 'servicio',    'etiqueta' => '`nombre`'],
    'citas'        => ['tabla' => 'cita',        'etiqueta' => "CONCAT('Cita #', id, ' · ', DATE_FORMAT(fecha_hora, '%d/%m/%Y %H:%i'))"],
    'diagnosticos' => ['tabla' => 'diagnostico', 'etiqueta' => "CONCAT('Diagnóstico #', id)"],
];

$listas = [
    'estado_cita' => ['pendiente', 'confirmada', 'atendida', 'cancelada'],
    'estado_pago' => ['pendiente', 'pagado', 'anulado'],
    'metodo_pago' => ['efectivo', 'tarjeta_credito', 'tarjeta_debito', 'yape'],
];
?>
<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <?php foreach ($campos as $campo => $tipo): ?>
        <label class="<?= $tipo === 'textarea' ? 'full' : '' ?>">
            <?= e(etiqueta($campo)) ?>

            <?php if (isset($referencias[$tipo])): ?>
                <?php
                $ref = $referencias[$tipo];
                $opciones = db()->query(
                    "SELECT id, {$ref['etiqueta']} AS etiqueta FROM `{$ref['tabla']}` ORDER BY id DESC LIMIT 500"
                )->fetchAll();
                ?>
                <select name="<?= e($campo) ?>" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($opciones as $opcion): ?>
                        <option
                            value="<?= e($opcion['id']) ?>"
                            <?= ($edit[$campo] ?? '') == $opcion['id'] ? 'selected' : '' ?>
                        >
                            <?= e($opcion['etiqueta']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            <?php elseif (isset($listas[$tipo])): ?>
                <?php $opciones = $listas[$tipo]; ?>
                <select name="<?= e($campo) ?>">
                    <?php foreach ($opciones as $opcion): ?>
                        <option
                            value="<?= e($opcion) ?>"
                            <?= ($edit[$campo] ?? $opciones[0]) === $opcion ? 'selected' : '' ?>
                        >
                            <?= e(ucwords(str_replace('_', ' ', $opcion))) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            <?php elseif ($tipo === 'textarea'): ?>
                <textarea name="<?= e($campo) ?>" rows="3"><?= e($edit[$campo] ?? '') ?></textarea>

            <?php else: ?>
                <?php
                $valor = $edit[$campo] ?? '';
                if ($tipo === 'datetime-local' && $valor) {
                    $valor = str_replace(' ', 'T', substr($valor, 0, 16));
                }
                ?>
                <input
                    name="<?= e($campo) ?>"
                    type="<?= e($tipo) ?>"
                    value="<?= e($valor) ?>"
                    <?= $tipo === 'number' ? 'step="0.01" min="0"' : '' ?>
                    <?= in_array($campo, ['nombre', 'fecha_hora', 'monto'], true) ? 'required' : '' ?>
                >
            <?php endif; ?>
        </label>
    <?php endforeach; ?>

    <div class="actions">
        <button type="submit" name="accion" value="guardar">Guardar</button>
        <a class="btn secondary" href="<?= e($pagina) ?>">Cancelar</a>
        <button type="reset" class="secondary">Limpiar</button>
    </div>
</form>