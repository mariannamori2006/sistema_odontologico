<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Odontologo.php';

class OdontologoController
{
    public Odontologo $modelo;

    public array $campos = [
        'nombre' => 'text',
        'colegiatura' => 'text',
        'especialidad' => 'text',
        'telefono' => 'text',
        'email' => 'email',
    ];

    public string $titulo = 'Odontólogos';

    public function __construct()
    {
        $this->modelo = new Odontologo(db());
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
            redirigir('veterinarios.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
