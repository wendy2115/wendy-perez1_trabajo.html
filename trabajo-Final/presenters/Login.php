<?php
require_once('../core/config.php');
require_once('../models/UsuarioModel.php');
//Creamos la clase Login
class Login{

    //creamos la funcion login
    public function login_user(){
        //instanciamos las variables enviadas por post desde javascript
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        //validamos que no hayan campos vacios
        if(empty($usuario) || empty($password)){
            //si existen campos vacios imprimimos el error
            echo "Error campos vacios";
            exit;
        }else{
            //instanaciamos la conexion a la base de datos
            $con = conexion();
            //instanciamos la clase de UsuarioModel
            $usuarioModel = new UsuarioModel();
            //guardamos el resultado de la consulta a la base de datos en una variable
            $usuario = $usuarioModel->find_by_username($usuario , $con);
            if(empty($usuario)){
                echo "Error en credenciales";
                exit;
            }
            
        }

    }

    public function create_user_external(){
        $con = conexion();
        //creamos un object
        $info = new StdClass();
        $info->nombre = $_POST["nombre"] ?? "";
        $info->apellidos = $_POST["apellidos"] ?? "";
        $info->email = $_POST["email"] ?? "";
        $info->telefono = $_POST["telefono"] ?? "";
        $info->fecha_nacimiento = $_POST["fecha_nacimiento"] ?? ""; 
        $info->direccion = $_POST["direccion"] ?? "";
        $info->sexo = $_POST["sexo"] ?? "";
        $info->usuario = $_POST["usuario"] ?? "";
        $info->password = $_POST["password"] ?? "";
        $info->rol = "user";
        if(empty($info->nombre) || empty($info->apellidos) || empty($info->email) || 
        empty($info->telefono) || empty($info->fecha_nacimiento) || empty($info->direccion)
        || empty($info->sexo) || empty($info->usuario) || empty($info->password)){
            echo "No puede haber campos vacios";
            exit;
        }
        $info->password = $this->generate_hash($info->password);
        $usuarioModel = new UsuarioModel();
        try{
            
            $userlogin = $usuarioModel->find_by_username($info->usuario ,$con);
            
            if(!empty($userlogin)){
                echo "Error ya existe el usuario";
                exit;
            }
            $userData = $usuarioModel->find_by_email($info->email , $con);
            if(!empty($userData)){
                echo "Error ya existe el email";
                exit;
            }
        
            $usuarioModel->create_user_data($info , $con);
            echo "creado";
            exit;
        } catch (\Throwable $th) {
            echo "error creando: ".$th;

            exit;
        }

    }

    private function generate_hash($password){
        return password_hash($password , PASSWORD_DEFAULT);
    }

    private function validate_hash($password , $hasPassword){
        return password_verify($password , $hasPassword);
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
        $login->login_user();
        break;
    case "create_external_user":
        $login->create_user_external();
        break;
    default:
    echo "Title no reconocido: ".$title;
    exit;
}

?>