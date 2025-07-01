<?php

require_once("../core/config.php");
require_once("../models/UsuarioModel.php");
require_once("../models/CitasModel.php");
class Usuario{

    public function get_all_usuarios(){
        $usuarios = new UsuarioModel();
        $con = conexion();
        $get_all_users = $usuarios->get_all_users_data($con);
        $table = "";
        if($get_all_users > 0){
            foreach($get_all_users as $item){
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
            

            echo $table;
        }
    
    }

    public function create_user(){
        
    }

    public function user_by_citas(){
        requireAdmin();
        $citasModels = new CitasModel();
        $con = conexion();
        $get_user_by_citas = $citasModels->get_user_by_citas($con);
        $table = "";
        if($get_user_by_citas > 0){
            foreach($get_user_by_citas as $item){
                $table .= "<tr>
                <td>".$item['idUser']." </td>
                <td>".$item['nombre']." ".$item['apellidos']."</td>
                <td><button class='btn btn-rounded-success' data-id='".$item["idUser"]."'><i class='fa fa-eye' aria-hidden='true'></i></button></td>
                </tr>";
            }
            echo $table;
        }
    }
}

$title = $_POST["title"];
$usuario = new Usuario();
switch($title){
    case "get_all_usuarios":
        $usuario->get_all_usuarios();
        break;
    case "create_user":
        $usuario->create_user();
        break;
    case "user_by_citas":
        $usuario->user_by_citas();
        break;
    default:
        echo "No existe";
        break;
}

?>