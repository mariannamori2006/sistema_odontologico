<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <?php foreach ($campos as $campo => $tipo): ?>
        <label>
            <?= e(ucwords(str_replace('_', ' ', $campo))) ?>

            <?php if (in_array($tipo, ['duenos', 'mascotas', 'veterinarios', 'servicios', 'citas', 'diagnosticos'], true)): ?>
                <?php
                $tabla = $tipo;
                $columna = in_array($tipo, ['citas', 'diagnosticos'], true) ? 'id' : 'nombre';
                $opciones = db()->query(
                    "SELECT id, `$columna` AS etiqueta FROM `$tabla` ORDER BY id DESC LIMIT 500"
                )->fetchAll();
                ?>
                <select name="<?= e($campo) ?>" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($opciones as $opcion): ?>
                        <option
                            value="<?= e($opcion['id']) ?>"
                            <?= ($edit[$campo] ?? '') == $opcion['id'] ? 'selected' : '' ?>
                        >
                            <?= e($opcion['etiqueta']) ?> (ID <?= e($opcion['id']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

            <?php elseif (in_array($tipo, ['estado_cita', 'estado_pago', 'metodo_pago'], true)): ?>
                <?php
                $opciones = [
                    'estado_cita' => ['pendiente', 'confirmada', 'atendida', 'cancelada'],
                    'estado_pago' => ['pendiente', 'pagado', 'anulado'],
                    'metodo_pago' => ['contado', 'tarjeta_credito', 'tarjeta_debito', 'yape'],
                ][$tipo];
                ?>
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
                <textarea name="<?= e($campo) ?>"><?= e($edit[$campo] ?? '') ?></textarea>

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
        <a class="btn secondary" href="reportes.php">Cancelar</a>
        <button type="reset" class="secondary">Limpiar</button>
    </div>
</form>
