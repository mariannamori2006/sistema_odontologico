# Sistema de Veterinaria MVC

## Estructura
- `config/database.php`: **único archivo de configuración**, conexión MySQL mediante PDO.
- `controller/`: controlador de autenticación, controlador base y un controlador por módulo.
- `model/`: modelo de usuarios, modelo base y un modelo por tabla operativa.
- `views/`: login, panel, layout y carpeta propia por módulo con `index.php`, `form.php` y `lista.php`.
- `database/veterinaria.sql`: estructura MySQL y un servicio de ejemplo.
- `assetes/` e `imagenes/`: archivos de la interfaz.

## Instalación con XAMPP
1. Copia la carpeta `SistemaVeterinariaMVC` dentro de `htdocs`.
2. Inicia Apache y MySQL en XAMPP.
3. Importa `database/veterinaria.sql` desde MySQL Workbench o phpMyAdmin.
4. Ajusta usuario, contraseña y nombre de base de datos en `config/database.php` si es necesario.
5. Abre `http://localhost/SistemaVeterinariaMVC/`.

## ¿Cómo ingreso al login?
**No hay usuarios predeterminados.** La primera vez:
1. En la pantalla de login completa **Nombre**, **Correo** y **Contraseña** (mínimo 10 caracteres).
2. Presiona **Registrarme**.
3. Ingresa con el **mismo correo y la misma contraseña** y presiona **Ingresar**.
4. **Cancelar** limpia el formulario.

El registro público crea usuarios con rol `recepcion`. No se suministran cuentas ni contraseñas de administrador por defecto.

## Notas
- Cada módulo dispone de acciones de creación, consulta, edición y eliminación.
- El sistema utiliza parámetros preparados PDO y tokens CSRF.
- Pagos con tarjeta y Yape son registros internos; **no** ejecutan cobros reales.
- Para uso productivo hacen falta permisos por rol, HTTPS, auditoría, copias de seguridad y pruebas de integración.
- El código está dividido en bloques e indentado para su edición con Visual Studio Code.
