<?php
require_once __DIR__ . '/BaseModel.php';

class Tratamiento extends BaseModel
{
    protected string $tabla = 'tratamientos';
    protected array $columnas = ['diagnostico_id', 'medicamento', 'indicaciones', 'fecha_inicio', 'fecha_fin', 'costo'];
}
