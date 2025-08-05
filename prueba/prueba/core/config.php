<?php
session_start();

// Configuración de base de datos
define('DB_HOST', '192.168.1.101');
define('DB_USER', 'final');
define('DB_PASS', 'Lp1221jo');
define('DB_NAME', 'site_web');
define('DB_PORT' , 33006);

// Conexión a la base de datos
function conexion(){
    try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=".DB_PORT.";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
}

// Funciones de sesión
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
// Funciòn para vàlidar si esta conectado
function isAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

//redireccionar si no esta logueado
function requireLogin() {
    if (!isLoggedIn()) {
            echo "redirect";
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        echo "admin";
        exit;
    }
}
?>