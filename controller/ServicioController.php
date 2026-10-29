<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Servicio.php';

class ServicioController
{
    public Servicio $modelo;

    public array $campos = [
        'nombre' => 'text',
        'descripcion' => 'textarea',
        'precio' => 'number',
        'duracion_min' => 'number',
    ];

    public string $titulo = 'Servicios';

    public function __construct()
    {
        $this->modelo = new Servicio(db());
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
            redirigir('servicios.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
