<?php
require_once __DIR__ . '/BaseModel.php';

class Reporte extends BaseModel
{
    protected string $tabla = 'reporte';
    protected array $columnas = ['paciente_id', 'odontologo_id', 'titulo', 'contenido', 'fecha'];
}
