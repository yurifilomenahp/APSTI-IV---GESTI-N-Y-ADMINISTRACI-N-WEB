<?php
require_once 'config/sesion.php';

// Eliminar variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Regresar al inicio
header("Location: index.php?ruta=login");
exit;