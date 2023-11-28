<?php 
use PHPUnit\Framework\TestCase

class UsuarioTest extends TestCase
{
    public function testRegistrar()
    {
        // Crea una instancia de Usuario (puedes necesitar ajustar esto según la estructura real de tu proyecto)
        $usuario = new Usuario();

        // Simula una solicitud POST
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Simula datos del formulario
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'secretpassword';
        $_POST['name'] = 'John';
        $_POST['lastName'] = 'Doe';
        $_POST['dateOfBirth'] = '01/01/1990';
        $_POST['country'] = 'Country';
        $_POST['city'] = 'City';

        // Usa Reflection para acceder a métodos protegidos o privados
        $method = new ReflectionMethod($usuario, 'obtenerDatosFormularioRegistro');
        $method->setAccessible(true);
        $formData = $method->invoke($usuario);

        // Ahora puedes hacer aserciones sobre $formData

        // Por ejemplo, verifica que el email sea el esperado
        $this->assertEquals('test@example.com', $formData['email']);

        // Restablece las superglobales después de las pruebas
        unset($_POST['email'], $_POST['password'], $_POST['name'], $_POST['lastName'], $_POST['dateOfBirth'], $_POST['country'], $_POST['city']);
    }
}

?>