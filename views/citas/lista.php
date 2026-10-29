<div class="table-scroll">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <?php foreach ($campos as $campo => $tipo): ?>
                    <th><?= e(ucwords(str_replace('_', ' ', $campo))) ?></th>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $fila): ?>
                <tr>
                    <td><?= e($fila['id']) ?></td>
                    <?php foreach ($campos as $campo => $tipo): ?>
                        <td><?= e($fila[$campo] ?? '') ?></td>
                    <?php endforeach; ?>
                    <td>
                        <a href="citas.php?editar=<?= e($fila['id']) ?>">Editar</a>
                        <form method="post" onsubmit="return confirm('¿Eliminar registro?')">
                            <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                            <input type="hidden" name="id" value="<?= e($fila['id']) ?>">
                            <button type="submit" class="danger" name="accion" value="eliminar">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
