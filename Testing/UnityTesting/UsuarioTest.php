<?php
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
    public function setUp(): void
    {
        // Establecer el directorio base para las pruebas
        chdir(dirname(__DIR__) . '../../Clases'); // Apunta al directorio "Clases"
    }
    public function testRegistrar()
    {
        // Puedes modificar esta sección según tus necesidades
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = '123';
        $_POST['name'] = 'Lucumi';
        $_POST['lastName'] = 'Orion';
        $_POST['dateOfBirth'] = '01/01/1990';
        $_POST['country'] = 'Colombia';
        $_POST['city'] = 'Bogota  D.C.';

        // Crear una instancia del Usuario
        $usuario = new Usuario();

        // Establecer el modo de prueba
        $usuario->setTesting(true);

        // Simular una solicitud POST
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Iniciar un búfer de salida para capturar cualquier salida (por ejemplo, redirecciones)
        ob_start();

        // Llamar al método registrar y obtener los datos para la sesión
        $sessionData = $usuario->registrar();

        // Realizar afirmaciones según el comportamiento esperado
        $this->assertTrue($sessionData['success']);
        $this->assertEquals('Lucumi', $sessionData['name']);
        $this->assertIsInt($sessionData['user_id']);

        // Obtener el contenido del búfer de salida solo si hay algo en el búfer
        $output = ob_get_clean();

        // Restaurar la variable global POST después de las pruebas
        $_POST = [];
    }
}
?>