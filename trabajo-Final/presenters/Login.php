<?php
require_once('../core/config.php');
//Creamos la clase Login
class Login{

    //creamos la funcion login
    function login(){
        //instanciamos las variables enviadas por post desde javascript
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        //validamos que no hayan campos vacios
        if(empty($usuario) || empty($password)){
            //si existen campos vacios imprimimos el error
            echo "error campos vacios";
        }else{
            $con = conexion();
            
        }

    }
}
//recuperamos el post title
$title = $_POST['title'];
//instanciamos la clase Login
$login = new Login();
//creamos un switch para saber que funcion usar de acuerdo a la variable title
switch($title){
    //en caso de login se ejecuta la funcion login
    case "login":
        //ejecutamos la funcion login
        $login->login();
}

?>