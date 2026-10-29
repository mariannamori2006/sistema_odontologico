<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Diagnostico.php';

class DiagnosticoController
{
    public Diagnostico $modelo;

    public array $campos = [
        'cita_id' => 'citas',
        'descripcion' => 'textarea',
        'observaciones' => 'textarea',
        'fecha' => 'date',
    ];

    public string $titulo = 'Diagnósticos';

    public function __construct()
    {
        $this->modelo = new Diagnostico(db());
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
            redirigir('diagnosticos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
