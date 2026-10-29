<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Dueno.php';

class DuenoController
{
    public Dueno $modelo;

    public array $campos = [
        'nombre' => 'text',
        'telefono' => 'text',
        'email' => 'email',
        'direccion' => 'text',
        'documento' => 'text',
    ];

    public string $titulo = 'Dueños';

    public function __construct()
    {
        $this->modelo = new Dueno(db());
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
            redirigir('duenos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
