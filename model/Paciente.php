<?php
require_once __DIR__ . '/BaseModel.php';

class Paciente extends BaseModel
{
    protected string $tabla = 'paciente';
    protected array $columnas = ['nombre',  'documento', 'fecha_nacimiento', 'telefono', 'email', 'direccion',];
}
