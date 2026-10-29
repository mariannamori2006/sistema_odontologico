<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Cita.php';

class CitaController
{
    public Cita $modelo;

    public array $campos = [
        'mascota_id' => 'mascotas',
        'veterinario_id' => 'veterinarios',
        'servicio_id' => 'servicios',
        'fecha_hora' => 'datetime-local',
        'estado' => 'estado_cita',
        'motivo' => 'textarea',
    ];

    public string $titulo = 'Citas veterinarias';

    public function __construct()
    {
        $this->modelo = new Cita(db());
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
            redirigir('citas.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
