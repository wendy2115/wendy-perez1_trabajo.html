<?php

// Incluye el archivo de configuración principal
require_once("../core/config.php");
// Incluye el modelo de usuario
require_once("../models/UsuarioModel.php");
// Incluye el modelo de citas
require_once("../models/CitasModel.php");

// Define la clase Usuario para manejar operaciones relacionadas con usuarios
class Usuario{

    // Obtiene y muestra todos los usuarios
    public function get_all_usuarios(){
        $usuarios = new UsuarioModel(); // Instancia el modelo de usuario
        $con = conexion(); // Obtiene la conexión a la base de datos
        $get_all_users = $usuarios->get_all_users_data($con); // Obtiene todos los usuarios
        $table = ""; // Inicializa la variable para la tabla HTML
        if($get_all_users > 0){ // Verifica si hay usuarios
            foreach($get_all_users as $item){ // Recorre cada usuario
                // Agrega una fila a la tabla con los datos del usuario
                $table .= "<tr>
                <td>".$item['idUser']." </td>
                <td>".$item['nombre']." ".$item['apellidos']."</td>
                <td>".$item['email']."</td>
                <td>".$item['telefono']."</td>
                <td>".$item['fecha_nacimiento']."</td>
                <td>".$item['direccion']."</td>
                <td>".$item['sexo']."</td>
                <td>
                <button class='btn-rounded-success' data-user='".json_encode($item)."' title='Actualizar'><i class='fa-solid fa-pencil'></i></button>
                
                <button class='btn-rounded-danger' data-id='{$item['idUser']}' title='Eliminar'><i class='fa-solid fa-trash'></i></button>
                </td>
                </tr>
                ";
            }
            // Muestra la tabla generada
            echo $table;
        }
    }

    // Actualiza los datos de un usuario
    public function updated_user(){
        requireAdmin(); // Verifica que el usuario sea administrador
        $con = conexion(); // Obtiene la conexión a la base de datos
        $data = new stdClass(); // Crea un objeto para los datos del usuario
        $data->idUser = $_POST["idUser"] ?? ""; // Asigna el id del usuario
        $data->nombre = $_POST["nombre"] ?? ""; // Asigna el nombre
        $data->apellidos = $_POST["apellidos"] ?? ""; // Asigna los apellidos
        $data->email = $_POST["email"] ?? ""; // Asigna el email
        $data->telefono = $_POST["telefono"] ?? ""; // Asigna el teléfono
        $data->direccion = $_POST ["direccion"] ?? ""; // Asigna la dirección
        $data->fecha_nacimiento = $_POST["fecha_nacimiento"] ?? ""; // Asigna la fecha de nacimiento
        $data->sexo = $_POST["sexo"] ?? ""; // Asigna el sexo
        $data->rol = $_POST["rol"] ?? ""; // Asigna el rol
        $data->idLogin = $_POST["idLogin"] ?? ""; // Asigna el id de login
        // Verifica que ningún campo esté vacío
        if(empty($data->idUser) || empty($data->nombre) || empty($data->apellidos) || empty($data->email) || empty($data->telefono) || empty($data->direccion)
        || empty($data->fecha_nacimiento) || empty($data->sexo) || empty($data->rol) || empty($data->idLogin)){
            echo "campos vacios"; // Muestra mensaje si hay campos vacíos
            exit;
        }
        $usuariosModel = new UsuarioModel(); // Instancia el modelo de usuario
        $result = $usuariosModel->updated_user($data , $con); // Actualiza el usuario en la base de datos
        if($result){
            $rol = $usuariosModel->edit_rol($data , $con); // Actualiza el rol del usuario
            if($rol){
                echo "ok"; // Muestra mensaje de éxito
                exit;
            }else{
                echo "error"; // Muestra mensaje de error
                exit;
            }
        }
    }

    // Muestra los usuarios que tienen citas
    public function user_by_citas(){
        requireAdmin(); // Verifica que el usuario sea administrador
        $citasModels = new UsuarioModel(); // Instancia el modelo de usuario
        $con = conexion(); // Obtiene la conexión a la base de datos
        $get_user_by_citas = $citasModels->get_all_users_data($con); // Obtiene los usuarios
        $table = ""; // Inicializa la variable para la tabla HTML
        if($get_user_by_citas > 0){ // Verifica si hay usuarios
            foreach($get_user_by_citas as $item){ // Recorre cada usuario
                // Agrega una fila a la tabla con los datos del usuario y un botón para ver citas
                $table .= "<tr>
                <td>".$item['idUser']." </td>
                <td>".$item['nombre']." ".$item['apellidos']."</td>
                <td><button class='btn btn-rounded-success' data-id='".$item["idUser"]."'><i class='fa fa-eye' aria-hidden='true'></i></button></td>
                </tr>";
            }
            // Muestra la tabla generada
            echo $table;
        }
    }

    // Elimina un usuario
    public function delete_user(){
        $con = conexion(); // Obtiene la conexión a la base de datos
        $usuarioModel = new UsuarioModel(); // Instancia el modelo de usuario
        try {
            // Intenta eliminar el usuario por id
           $delete =  $usuarioModel->delete($_POST["idUser"] , $con);
            if($delete){
                echo "eliminado"; // Muestra mensaje de éxito
                exit;
            }
        } catch (\Throwable $th) {
            // Muestra el mensaje de error si ocurre una excepción
            echo $th->getMessage();
            exit;
        }
    }
}

// Obtiene el valor de 'title' enviado por POST para determinar la acción a ejecutar
$title = $_POST["title"];
$usuario = new Usuario(); // Instancia la clase Usuario

// Ejecuta la acción correspondiente según el valor de $title
switch($title){
    case "get_all_usuarios":
        $usuario->get_all_usuarios(); // Llama al método para obtener todos los usuarios
        break;
    case "updated_user":
        $usuario->updated_user(); // Llama al método para actualizar un usuario
        break;
    case "user_by_citas":
        $usuario->user_by_citas(); // Llama al método para mostrar usuarios con citas
        break;
    case "delete_user":
        requireAdmin(); // Verifica que el usuario sea administrador antes de eliminar
        $usuario->delete_user(); // Llama al método para eliminar un usuario
        break;
    default:
        echo "No existe"; // Muestra mensaje si la acción no existe
        break;
}

?>