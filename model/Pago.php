<?php
require_once __DIR__ . '/BaseModel.php';

class Pago extends BaseModel
{
    protected string $tabla = 'pagos';
    protected array $columnas = ['cita_id', 'monto', 'metodo', 'estado', 'referencia', 'fecha_pago'];
}
