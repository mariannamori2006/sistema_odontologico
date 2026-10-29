<?php
require_once __DIR__ . '/BaseModel.php';

class Mascota extends BaseModel
{
    protected string $tabla = 'mascotas';
    protected array $columnas = ['dueno_id', 'nombre', 'especie', 'raza', 'sexo', 'fecha_nacimiento', 'peso_kg'];
}
