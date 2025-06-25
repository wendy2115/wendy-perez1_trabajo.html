<?php

require_once("../core/config.php");
require_once("../models/UsuarioModel.php");

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
                <td><button class='btn-rounded-danger' data-id='{$item['idUser']}' title='Eliminar'><i class='fa-solid fa-trash'></i></button></td>
                </tr>
                ";
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
    default:
        echo "No existe";
        break;
}

?>