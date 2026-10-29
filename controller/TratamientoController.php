<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Tratamiento.php';

class TratamientoController
{
    public Tratamiento $modelo;

    public array $campos = [
        'diagnostico_id' => 'diagnosticos',
        'medicamento' => 'text',
        'indicaciones' => 'textarea',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'costo' => 'number',
    ];

    public string $titulo = 'Tratamientos';

    public function __construct()
    {
        $this->modelo = new Tratamiento(db());
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
            redirigir('tratamientos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
