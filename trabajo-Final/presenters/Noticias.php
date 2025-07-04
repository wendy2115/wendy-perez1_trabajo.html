<?php
require_once("../core/config.php");
require_once("../models/NoticiasModel.php");
class Noticias{

    public function get_all(){
        requireAdmin();
        $con = conexion();
        $noticiasModel = new NoticiasModel();
        $noticias = $noticiasModel->get_all();
        if($noticias > 0){
            foreach($noticias as $noti){
                echo "<tr>
                <td>".$noti["fecha"]."</td>
                <td>".$noti["titulo"]."</td>
                <td><img width='200px' height='200px'  src=".$noti["imagen"]."</td>
                <td>
                    <button class='btn-rounded-success' id='edit_noticia' data-noti='".json_encode($noti)."' ><i class='fa-solid fa-pencil'></i></button>
                    <button class='btn-rounded-danger' id='delete_noticia' data-id='".$noti["idNoticia"]."'><i class='fa-solid fa-trash'></i> </button>
                </td>
                ";
            }
            exit;
        }else {
            echo "<span>Sin registros</span>";
        }

    }

}
$title = $_POST["title"];
$noticias = new Noticias();

switch ($variable) {
    case 'noticias_all':
        # code...
        $noticias->get_all();
        break;
    
    default:
        # code...
        break;
}
?>