<?php
require_once 'config/sesion.php';

$ruta = $_GET['ruta'] ?? 'login';

switch ($ruta) {

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'controllers/AuthController.php';
            $auth = new AuthController();
            $mensaje = $auth->login(
            trim($_POST['correo']),
            $_POST['password']
            );
        }

        if (isset($_SESSION['usuario_id'])) {
            header("Location: index.php?ruta=catalogo");
            exit;
        }

        require 'views/auth/login.php';
        break;

    case 'registro':
        require_once 'controllers/AuthController.php';
        $auth = new AuthController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mensaje = $auth->registro(
                trim($_POST['nombre']),
                trim($_POST['correo']),
                $_POST['password']
            );
        }

        require 'views/auth/registro.php';
        break;

    case 'catalogo':
        verificarSesion();

        require_once 'controllers/ProductoController.php';
        $controller = new ProductoController();
        $productos = $controller->catalogo();

        require 'views/productos/catalogo.php';
        break;

    case 'agregar':
        verificarSesion();
        require_once 'controllers/CarritoController.php';
        $carrito = new CarritoController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mensaje = $carrito->agregar(
                $_POST['producto_id'] ?? 0,
                $_POST['cantidad'] ?? 0
            );
        }

        header("Location: index.php?ruta=carrito");
        exit;

    case 'carrito':
        verificarSesion();
        require_once 'controllers/CarritoController.php';
        $carrito = new CarritoController();
        $total = $carrito->calcularTotal();

        require 'views/carrito/index.php';
        break;

    case 'eliminar':
        verificarSesion();
        require_once 'controllers/CarritoController.php';
        $carrito = new CarritoController();
        $carrito->eliminar($_GET['id'] ?? 0);

        header("Location: index.php?ruta=carrito");
        exit;

    case 'vaciar':
        verificarSesion();
        require_once 'controllers/CarritoController.php';
        $carrito = new CarritoController();
        $carrito->vaciar();

        header("Location: index.php?ruta=carrito");
        exit;

    case 'actualizar':
        verificarSesion();
        require_once 'controllers/CarritoController.php';
        $carrito = new CarritoController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mensaje = $carrito->actualizarCantidad(
                $_POST['producto_id'] ?? 0,
                $_POST['cantidad'] ?? 0
            );
        }

        header("Location: index.php?ruta=carrito");
        exit;

    case 'confirmar':
        verificarSesion();
        require_once 'controllers/VentaController.php';
        $ventaController = new VentaController();
        $resultado = $ventaController->confirmar();
        require 'views/ventas/confirmar.php';
        break;

    case 'historial':
        verificarSesion();
        require_once 'controllers/VentaController.php';
        $ventaController = new VentaController();
        $ventas = $ventaController->historial();
        require 'views/ventas/historial.php';
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
}