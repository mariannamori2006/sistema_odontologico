<?php
require_once __DIR__ . '/BaseModel.php';

class Diagnostico extends BaseModel
{
    protected string $tabla = 'diagnostico';
    protected array $columnas = ['cita_id', 'descripcion', 'observaciones', 'fecha'];
}
