<?php
// Incluye el archivo de configuración principal
require_once('../core/config.php');
// Incluye el modelo de usuario
require_once('../models/UsuarioModel.php');

// Creamos la clase Login para manejar autenticación y registro
class Login{

    // Función para iniciar sesión de usuario
    public function login_user(){
        // Instancia las variables enviadas por POST desde JavaScript
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        // Valida que no hayan campos vacíos
        if(empty($usuario) || empty($password)){
            // Si existen campos vacíos imprime el error
            echo "Error campos vacios";
            exit;
        }else{
            // Instancia la conexión a la base de datos
            $con = conexion();
            // Instancia la clase de UsuarioModel
            $usuarioModel = new UsuarioModel();
            // Guarda el resultado de la consulta a la base de datos en una variable
            $usuario = $usuarioModel->find_by_username($usuario , $con);
            // Si no existe el usuario, muestra error de credenciales
            if(empty($usuario)){
                echo "Error en credenciales";
                exit;
            }
            // Valida la contraseña
            if($this->validate_hash($password , $usuario["password"])){
                // Si la contraseña es correcta, guarda datos en la sesión
                $_SESSION["user_id"] = $usuario["idUser"];
                $_SESSION["rol"] = $usuario["rol"];
                echo "ok";
                exit;
            }else{
                // Si la contraseña es incorrecta, muestra error
                echo "Error en credenciales";
                exit;
            }
        }
    }

    // Función para crear un nuevo usuario
    public function create_user(){
        $con = conexion(); // Obtiene la conexión a la base de datos
        // Crea un objeto para almacenar la información del usuario
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
        // Si es admin, asigna el rol recibido, si no, asigna 'user'
        if(isAdmin()){
            $info->rol = $_POST["rol"] ?? "";
        }else{
            $info->rol = "user";
        }
        // Valida que no haya campos vacíos
        if(empty($info->nombre) || empty($info->apellidos) || empty($info->email) || 
        empty($info->telefono) || empty($info->fecha_nacimiento) || empty($info->direccion)
        || empty($info->sexo) || empty($info->usuario) || empty($info->password)){
            echo "No puede haber campos vacios";
            exit;
        }
        // Genera el hash de la contraseña
        $info->password = $this->generate_hash($info->password);
        $usuarioModel = new UsuarioModel(); // Instancia el modelo de usuario
        try{
            // Verifica si el usuario ya existe por nombre de usuario
            $userlogin = $usuarioModel->find_by_username($info->usuario ,$con);
            if(!empty($userlogin)){
                echo "Error ya existe el usuario";
                exit;
            }
            // Verifica si el email ya existe
            $userData = $usuarioModel->find_by_email($info->email , $con);
            if(!empty($userData)){
                echo "Error ya existe el email";
                exit;
            }
            // Crea el usuario en la base de datos
            $usuarioModel->create_user_data($info , $con);
            // Si es admin, muestra mensaje especial
            if(isAdmin()){
                echo "admin_register";
                exit;
            }
            // Si es usuario normal, muestra mensaje de registro
            echo "register";
            exit;
        } catch (\Throwable $th) {
            // Muestra mensaje de error si ocurre una excepción
            echo "error creando: ".$th;
            exit;
        }
    }

    // Función privada para generar hash de contraseña
    private function generate_hash($password){
        return password_hash($password , PASSWORD_DEFAULT);
    }

    // Función privada para validar hash de contraseña
    private function validate_hash($password , $hasPassword){
        return password_verify($password , $hasPassword);
    }

    // Verifica si el usuario está logueado
    public function isLogin(){
        if(isLoggedIn()){
            echo "1";
        }else{
            echo "0";
        }
    }

    // Verifica si el usuario logueado es admin
    public function isLoginAdmin(){
        if(isAdmin()){
            echo "1";
        }else{
            echo "0";
        }
    }

    // Cierra la sesión del usuario
    public function logout(){
        session_destroy();
    }

    // Muestra el perfil del usuario logueado
    public function perfil(){
        requireLogin(); // Verifica que el usuario esté logueado
        $con = conexion(); // Obtiene la conexión a la base de datos
        $usuarioModel = new UsuarioModel(); // Instancia el modelo de usuario
        $user = $usuarioModel->find_by_idUser($_SESSION["user_id"] , $con); // Obtiene los datos del usuario
        if(empty($user)){
            echo "Error al recuperar el usuario";
            exit;
        }
        // Genera el formulario HTML con los datos del usuario
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

    // Actualiza la contraseña del usuario logueado
    public function update_contrasena(){
        requireLogin(); // Verifica que el usuario esté logueado
        $con = conexion(); // Obtiene la conexión a la base de datos
        // Verifica que se hayan enviado las contraseñas por POST
        if(isset($_POST["contrasena"]) && isset($_POST["new_contrasena"])){
            $idUser = $_SESSION["user_id"];
            $usuarioModel = new UsuarioModel();
            $userLogin = $usuarioModel->find_by_idUser($idUser , $con);
            // Valida la contraseña actual
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

    // Actualiza el perfil del usuario logueado
    public function actualizar_perfil(){
        requireLogin(); // Verifica que el usuario esté logueado
        $con = conexion(); // Obtiene la conexión a la base de datos
        $info = new StdClass(); // Crea un objeto para los datos del usuario
        $info->idUser = $_SESSION["user_id"];
        $info->nombre = $_POST["nombre"] ?? "";
        $info->apellidos = $_POST["apellidos"] ?? "";
        $info->email = $_POST["email"] ?? "";
        $info->telefono = $_POST["telefono"] ?? "";
        $info->fecha_nacimiento = $_POST["fecha_nacimiento"] ?? ""; 
        $info->direccion = $_POST["direccion"] ?? "";
        $info->sexo = $_POST["sexo"] ?? "";
        // Valida que no haya campos vacíos
        if(empty($info->nombre) || empty($info->apellidos) || empty($info->email) || 
        empty($info->telefono) || empty($info->fecha_nacimiento) || empty($info->direccion)
        || empty($info->sexo)){
            echo "No puede haber campos vacios";
            exit;
        }
        $usuarioModel = new UsuarioModel(); // Instancia el modelo de usuario
        $result = $usuarioModel->updated_user($info , $con); // Actualiza el usuario en la base de datos
        if($result){
            echo "success";
            exit;
        }
    }
}

// Recupera el post title para saber qué acción ejecutar
$title = $_POST['title'];
// Instancia la clase Login
$login = new Login();
// Crea un switch para saber qué función usar de acuerdo a la variable title
switch($title){
    // En caso de login se ejecuta la función login
    case "login":
        // Ejecuta la función login
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
    // Muestra mensaje si el título no es reconocido
    echo "Title no reconocido: ".$title;
    exit;
}

?>