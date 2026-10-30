<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Paciente.php';

class PacienteController
{
    public Paciente $modelo;

    public array $campos = [
        'nombre' => 'text',
        'documento' => 'text',
        'fecha_nacimiento' => 'date',
        'telefono' => 'text',
        'email' => 'email',
        'direccion' => 'text',
    ];

    public string $titulo = 'Pacientes';

    public function __construct()
    {
        $this->modelo = new Paciente(db());
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
                if ($datos['nombre'] === null) {
                    throw new RuntimeException('Indica el nombre del paciente.');
                }
                $this->modelo->guardar($datos, $id);
            }

            notificar('Operación realizada correctamente');
            redirigir('pacientes.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique referencias, duplicados y datos.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}
