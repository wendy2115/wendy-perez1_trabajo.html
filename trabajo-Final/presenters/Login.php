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
            //validar contraseña
            if($this->validate_hash($password , $usuario["password"])){
                $_SESSION["user_id"] = $usuario["idUser"];
                $_SESSION["rol"] = $usuario["rol"];
                echo "ok";
                exit;
            }else{
                echo "Error en credenciales";
                exit;
            }

            
        }

    }

    public function create_user(){
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
        if(isAdmin()){
            $info->rol = $_POST["rol"] ?? "";
        }else{
            $info->rol = "user";
        }
        
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
            if(isAdmin()){
                echo "admin_register";
                exit;
            }
            echo "register";
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

    public function isLogin(){
        if(isLoggedIn()){
            echo "1";
        }else{
            echo "0";
        }
    }

    public function isLoginAdmin(){
        if(isAdmin()){
            echo "1";
        }else{
            echo "0";
        }
    }

    public function logout(){
        session_destroy();
    }

    public function perfil(){
        requireLogin();
        $con = conexion();
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->find_by_idUser($_SESSION["user_id"] , $con);
        if(empty($user)){
            echo "Error al recuperar el usuario";
            exit;
        }
        $data = '<div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="'.$user["nombre"].'" placeholder="Ingresa tu nombre">
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Ingresa tus apellidos" value="'.$user["apellidos"].'">
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" value="'.$user["email"].'">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Ingresa tu número de teléfono" value="'.$user["telefono"].'">
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="'.$user["fecha_nacimiento"].'">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" placeholder="Ingresa tu dirección" value="'.$user["direccion"].'">
            </div>
            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo">
                    <option value="M" '.($user["sexo"] == "masculino" ? "selected" : "").'>Masculino</option>
                    <option value="F" '.($user["sexo"] == "femenino" ? "selected" : "").'>Femenino</option>
                   
                </select>
            </div>
            <button type="button" id="open_modal_contrasena" class="btn btn-primary">Actualizar contraseña</button>
            <button id="btn_update_perfil" class="btn btn-secondary">Actualizar Perfil</button>
        ';
        echo $data;
        exit;
    }

    public function update_contrasena(){
        requireLogin();
        $con = conexion();
        if(isset($_POST["contrasena"]) && isset($_POST["new_contrasena"])){
            $idUser = $_SESSION["user_id"];
            $usuarioModel = new UsuarioModel();
            $userLogin = $usuarioModel->find_by_idUser($idUser , $con);
            if($this->validate_hash($_POST["contrasena"] , $userLogin["password"])){
                $password = $this->generate_hash($_POST["new_contrasena"]);
                $id = $userLogin["idLogin"];
                $cambio = $usuarioModel->updated_password($password, $id , $con);
                if($cambio){
                    echo "success";
                    $this->logout();
                    exit;
                }
            }else{
                echo "Actual contraseña no coincide";
            }

        }else{
            echo "Datos incompletos";
            exit;
        }
    }

    public function actualizar_perfil(){
        requireLogin();
        $con = conexion();
        $info = new StdClass();
        $info->idUser = $_SESSION["user_id"];
        $info->nombre = $_POST["nombre"] ?? "";
        $info->apellidos = $_POST["apellidos"] ?? "";
        $info->email = $_POST["email"] ?? "";
        $info->telefono = $_POST["telefono"] ?? "";
        $info->fecha_nacimiento = $_POST["fecha_nacimiento"] ?? ""; 
        $info->direccion = $_POST["direccion"] ?? "";
        $info->sexo = $_POST["sexo"] ?? "";
        if(empty($info->nombre) || empty($info->apellidos) || empty($info->email) || 
        empty($info->telefono) || empty($info->fecha_nacimiento) || empty($info->direccion)
        || empty($info->sexo)){
            echo "No puede haber campos vacios";
            exit;
        }
        $usuarioModel = new UsuarioModel();
        $result = $usuarioModel->updated_user($info , $con);
        if($result){
            echo "success";
            exit;
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
        $login->login_user();
        break;
    case "create_user":
        $login->create_user();
        break;
    case "isLogin":
        $login->isLogin();
        break;
    case "isAdmin":
        $login->isLoginAdmin();
        break;
    case "perfil":
        $login->perfil();
        break;
    case "update_contrasena":
        $login->update_contrasena();
        break;
    case "actualizar_perfil":
        $login->actualizar_perfil();
        break;
    case "logout":
        $login->logout();
        break;
    default:
    echo "Title no reconocido: ".$title;
    exit;
}

?>