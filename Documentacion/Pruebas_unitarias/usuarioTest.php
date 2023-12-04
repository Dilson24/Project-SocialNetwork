<?php
use PHPUnit\Framework\TestCase;

class usuarioTest extends TestCase
{
    public function setUp(): void
    {
        // Establecer el directorio base para las pruebas
        chdir(dirname(__DIR__) . '../../Clases'); // Apunta al directorio "Clases"
    }
    public function testRegistrar()
    {
        // Crea una instancia de Usuario
        $usuario = new Usuario();

        // Simula una solicitud POST
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Simula datos del formulario
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = '123';
        $_POST['name'] = 'John';
        $_POST['lastName'] = 'Doe';
        $_POST['dateOfBirth'] = '01/01/1990';
        $_POST['country'] = 'Colombia';
        $_POST['city'] = 'Bogota  D.C.';

        // Utiliza Reflection para acceder a métodos protegidos o privados
        $method = new ReflectionMethod($usuario, 'registrar');
        $method->setAccessible(true);
        $method->invoke($usuario);

        // Aserciones después de la ejecución del método

        // Verifica que la sesión se ha iniciado correctamente
        $this->assertArrayHasKey('user_id', $_SESSION);
        $this->assertArrayHasKey('name', $_SESSION);

        // Verifica que se ha establecido correctamente la cookie de token
        $this->assertArrayHasKey('token', $_COOKIE);

        // Restablece las superglobales después de las pruebas
        unset($_POST['email'], $_POST['password'], $_POST['name'], $_POST['lastName'], $_POST['dateOfBirth'], $_POST['country'], $_POST['city']);
    }
}