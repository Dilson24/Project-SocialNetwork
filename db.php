<?php
class Database
{
    private static $instance = null;
    private $connection;
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "redsocialnet";

    private function __construct()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Establecer la codificación de caracteres en UTF-8
        $this->connection->set_charset("utf8");

        if ($this->connection->connect_error) {
            die("Error de conexión a la base de datos: " . $this->connection->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
?>
