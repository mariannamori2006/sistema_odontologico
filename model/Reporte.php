<?php
require_once __DIR__ . '/BaseModel.php';

class Reporte extends BaseModel
{
    protected string $tabla = 'reportes';
    protected array $columnas = ['mascota_id', 'veterinario_id', 'titulo', 'contenido', 'fecha'];
}
