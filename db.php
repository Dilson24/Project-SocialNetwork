<?php
//Incluir el archvio necesario
require_once('config.php');
//Clase padre para la coneción de la base de datos
class Database
{
    // Instancia única de la clase Database
    private static $instance = null;

    // La conexión a la base de datos
    private $connection;

    // Constructor privado para prevenir la creación de instancias directas
    private function __construct()
    {
        // Crear una nueva conexión a la base de datos utilizando la información de configuración
        $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Establecer la codificación de caracteres en UTF-8
        $this->connection->set_charset("utf8mb4");

        // Verificar si hay errores de conexión
        if ($this->connection->connect_error) {
            die("Error de conexión a la base de datos: " . $this->connection->connect_error);
        }
    }

    // Método estático para obtener la instancia única de la clase Database
    public static function getInstance()
    {
        // Si la instancia aún no ha sido creada, crearla
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        // Devolver la instancia existente o recién creada
        return self::$instance;
    }

    // Método para obtener la conexión a la base de datos
    public function getConnection()
    {
        return $this->connection;
    }
}
// Patrón Singleton
/*Breve explicación de cómo funciona el patrón Singleton en este código:

1.Clase Database: Es la clase que representa la conexión a la base de datos.

2.Atributo private static $instance: Este atributo almacena la única instancia de la clase. 
Es estático para que sea compartido por todas las instancias de la clase.

3.Método privado __construct(): El constructor es privado para evitar que se pueda crear una 
instancia de la clase directamente desde fuera de la clase. Solo puede ser invocado desde dentro de la clase.

4.Método estático getInstance(): Este método es responsable de devolver la única instancia 
de la clase Database. Si la instancia aún no ha sido creada, la crea utilizando el constructor privado.

5.Método getConnection(): Este método devuelve la conexión a la base de datos.

Al seguir este diseño, se garantiza que solo existe una instancia de la clase Database 
en toda la aplicación, lo que puede ser útil en situaciones en las que se desea tener 
una única conexión a la base de datos para evitar problemas de concurrencia y mejorar 
la eficiencia en la gestión de recursos.*/
?>