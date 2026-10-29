<?php
require_once __DIR__ . '/BaseModel.php';

class Dueno extends BaseModel
{
    protected string $tabla = 'duenos';
    protected array $columnas = ['nombre', 'telefono', 'email', 'direccion', 'documento'];
}
