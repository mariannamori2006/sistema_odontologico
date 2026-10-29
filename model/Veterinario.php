<?php
require_once __DIR__ . '/BaseModel.php';

class Veterinario extends BaseModel
{
    protected string $tabla = 'veterinarios';
    protected array $columnas = ['nombre', 'colegiatura', 'especialidad', 'telefono', 'email'];
}
