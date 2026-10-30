<?php $pagina = basename($_SERVER['PHP_SELF']); ?>
<div class="table-scroll">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <?php foreach ($campos as $campo => $tipo): ?>
                    <th><?= e(etiqueta($campo)) ?></th>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$rows): ?>
                <tr>
                    <td class="empty" colspan="<?= count($campos) + 2 ?>">
                        Aún no hay registros. Usa el formulario de arriba para agregar el primero.
                    </td>
                </tr>
            <?php endif; ?>

            <?php foreach ($rows as $fila): ?>
                <tr>
                    <td><?= e($fila['id']) ?></td>
                    <?php foreach ($campos as $campo => $tipo): ?>
                        <td><?= e($fila[$campo] ?? '') ?></td>
                    <?php endforeach; ?>
                    <td>
                        <div class="row-actions">
                            <a class="btn-sm" href="<?= e($pagina) ?>?editar=<?= e($fila['id']) ?>">Editar</a>
                            <form method="post" onsubmit="return confirm('¿Eliminar registro?')">
                                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                                <input type="hidden" name="id" value="<?= e($fila['id']) ?>">
                                <button type="submit" class="btn-sm danger" name="accion" value="eliminar">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>