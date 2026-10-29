<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Reporte.php';

class ReporteController
{
    public Reporte $modelo;

    public array $campos = [
        'mascota_id' => 'mascotas',
        'veterinario_id' => 'veterinarios',
        'titulo' => 'text',
        'contenido' => 'textarea',
        'fecha' => 'date',
    ];

    public string $titulo = 'Reportes clínicos';

    public function __construct()
    {
        $this->modelo = new Reporte(db());
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
            redirigir('reportes.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
