<?php
require_once __DIR__ . '/BaseModel.php';

class Cita extends BaseModel
{
    protected string $tabla = 'citas';
    protected array $columnas = ['mascota_id', 'veterinario_id', 'servicio_id', 'fecha_hora', 'estado', 'motivo'];
}
