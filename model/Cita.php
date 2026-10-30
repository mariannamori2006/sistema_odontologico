<?php
require_once __DIR__ . '/BaseModel.php';

class Cita extends BaseModel
{
    protected string $tabla = 'cita';
    protected array $columnas = ['paciente_id', 'odontologo_id', 'servicio_id', 'fecha_hora', 'estado', 'motivo'];
}
