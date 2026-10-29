<?php
// Las tablas y columnas están definidas en los modelos, nunca llegan desde el usuario.
abstract class BaseModel
{
    protected PDO $db;
    protected string $tabla;
    protected array $columnas;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function listar(): array
    {
        return $this->db->query(
            'SELECT * FROM `' . $this->tabla . '` ORDER BY id DESC LIMIT 300'
        )->fetchAll();
    }

    public function obtener(int $id): array|false
    {
        $consulta = $this->db->prepare('SELECT * FROM `' . $this->tabla . '` WHERE id = ?');
        $consulta->execute([$id]);
        return $consulta->fetch();
    }

    public function guardar(array $datos, int $id = 0): void
    {
        $columnas = $this->columnas;
        $valores = array_map(fn($columna) => $datos[$columna] ?? null, $columnas);

        if ($id > 0) {
            $asignaciones = implode(', ', array_map(fn($columna) => "`$columna` = ?", $columnas));
            $consulta = $this->db->prepare(
                "UPDATE `{$this->tabla}` SET $asignaciones WHERE id = ?"
            );
            $consulta->execute([...$valores, $id]);
        } else {
            $nombres = implode(', ', array_map(fn($columna) => "`$columna`", $columnas));
            $marcadores = implode(', ', array_fill(0, count($columnas), '?'));
            $consulta = $this->db->prepare(
                "INSERT INTO `{$this->tabla}` ($nombres) VALUES ($marcadores)"
            );
            $consulta->execute($valores);
        }
    }

    public function eliminar(int $id): void
    {
        $consulta = $this->db->prepare('DELETE FROM `' . $this->tabla . '` WHERE id = ?');
        $consulta->execute([$id]);
    }
}
