<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Pago.php';

class PagoController
{
    public Pago $modelo;

    public array $campos = [
        'cita_id' => 'citas',
        'monto' => 'number',
        'metodo' => 'metodo_pago',
        'estado' => 'estado_pago',
        'referencia' => 'text',
        'fecha_pago' => 'datetime-local',
    ];

    public string $titulo = 'Pagos';

    public function __construct()
    {
        $this->modelo = new Pago(db());
    }

    public function procesar(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        try {
            validarCsrf();
            $id = filter_var(
                $_POST['id'] ?? 0,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            ) ?: 0;

            if (($_POST['accion'] ?? '') === 'eliminar') {
                $this->modelo->eliminar($id);
            } else {
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
            }

            notificar('Operación realizada correctamente');
            redirigir('pagos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
