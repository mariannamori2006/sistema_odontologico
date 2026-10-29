<?php
// Configuración de MySQL. Este es el único archivo dentro de config.
class Database
{
    private string $host = 'localhost';
    private string $name = 'veterinaria_db';
    private string $user = 'root';
    private string $port = '3307';
    private string $password = '';

    public function conectar(): PDO
    {
        return new PDO(
            "mysql:host={$this->host};port={$this->port};dbname={$this->name};charset=utf8mb4",
            $this->user,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}
