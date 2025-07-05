<?php
// Incluye el archivo de configuración principal
require_once("../core/config.php");
// Incluye el modelo de Noticias
require_once("../models/NoticiasModel.php");

// Define la clase Noticias para manejar operaciones relacionadas con noticias
class Noticias{

    // Obtiene y muestra todas las noticias
    public function get_all(){
        requireAdmin(); // Verifica que el usuario sea administrador
        $con = conexion(); // Obtiene la conexión a la base de datos
        $noticiasModel = new NoticiasModel(); // Instancia el modelo de noticias
        $noticias = $noticiasModel->get_all($con); // Obtiene todas las noticias
        if($noticias > 0){ // Verifica si hay noticias
            foreach($noticias as $noti){ // Recorre cada noticia
                // Imprime una fila de tabla con los datos de la noticia
                echo "<tr>
                <td>".$noti["fecha"]."</td>
                <td>".$noti["titulo"]."</td>
                <td><img width='200px' height='200px'  src='/assets/media/".$noti["imagen"]."'</td>
                <td>
                    <button class='btn-rounded-success' id='edit_noticia' data-noti='".json_encode($noti)."' ><i class='fa-solid fa-pencil'></i></button>
                    <button class='btn-rounded-danger' id='delete_noticia' data-id='".$noti["idNoticia"]."'><i class='fa-solid fa-trash'></i> </button>
                </td>
                ";
            }
            exit; // Finaliza la ejecución después de mostrar las noticias
        }else {
            // Muestra mensaje si no hay registros
            echo "<span>Sin registros</span>";
        }
    }

    // Crea una nueva noticia
    public function create_noticia(){
        requireAdmin(); // Verifica que el usuario sea administrador
        // Verifica si se ha subido una imagen correctamente
        if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK){
            $nombreTmp = $_FILES['imagen']['tmp_name']; // Nombre temporal del archivo subido
            $nombreOriginal = basename($_FILES['imagen']['name']); // Nombre original del archivo
            $directory = "../assets/media/"; // Directorio de destino para la imagen
            if(!file_exists($directory)){
                mkdir($directory , 0777, true); // Crea el directorio si no existe
            }
            $nombreFinal = uniqid()."_".$nombreOriginal; // Genera un nombre único para la imagen
            $rutaFinal = $directory . $nombreFinal; // Ruta final de la imagen
            // Mueve la imagen subida al directorio destino
            if(move_uploaded_file($nombreTmp , $rutaFinal)){
                $con = conexion(); // Obtiene la conexión a la base de datos
                $noticiasModel = new NoticiasModel(); // Instancia el modelo de noticias
                $data = new stdClass(); // Crea un objeto para los datos de la noticia
                $data->titulo = $_POST["titulo"]; // Asigna el título
                $data->texto = $_POST["texto"]; // Asigna el texto
                $data->fecha = $_POST["fecha"]; // Asigna la fecha
                $data->imagen = $nombreFinal; // Asigna el nombre de la imagen
                $data->idUser = $_SESSION["user_id"]; // Asigna el id del usuario actual

                $result = $noticiasModel->create($data , $con); // Inserta la noticia en la base de datos
                if($result){
                    echo "ok"; // Muestra mensaje de éxito
                    exit;
                }
            }else{
                // Muestra mensaje si no se pudo cargar la imagen
                echo "No se logro cargar";
                exit;
            }
        }else{
            // Muestra mensaje si no se subió imagen
            echo "no imagen";
            exit;
        }
    }

    // Elimina una noticia
    public function delete(){
        requireAdmin(); // Verifica que el usuario sea administrador
        $con = conexion(); // Obtiene la conexión a la base de datos
        $noticiaModel = new NoticiasModel(); // Instancia el modelo de noticias
        if(isset($_POST["idNoticia"])){ // Verifica si se recibió el id de la noticia
            $result = $noticiaModel->delete($_POST["idNoticia"] , $con); // Elimina la noticia
            if($result){
                echo "ok"; // Muestra mensaje de éxito
                exit;
            }else{
                echo "Error"; // Muestra mensaje de error
                exit;
            }
        }
    }

    // Actualiza una noticia existente
    public function update_noticias(){
        requireAdmin(); // Verifica que el usuario sea administrador
        $con = conexion(); // Obtiene la conexión a la base de datos
        $data = new stdClass(); // Crea un objeto para los datos de la noticia
        $data->idNoticia = $_POST["idNoticia"]; // Asigna el id de la noticia
        $data->titulo = $_POST["titulo"]; // Asigna el título
        $data->fecha = $_POST["fecha"]; // Asigna la fecha
        $data->texto = $_POST["texto"]; // Asigna el texto
        $noticiasModel = new NoticiasModel(); // Instancia el modelo de noticias
        $result = $noticiasModel->updated($data , $con); // Actualiza la noticia en la base de datos
        if($result){
            echo "ok"; // Muestra mensaje de éxito
            exit;
        }
    }

}

// Obtiene el valor de 'title' enviado por POST para determinar la acción a ejecutar
$title = $_POST["title"];
$noticias = new Noticias(); // Instancia la clase Noticias

// Ejecuta la acción correspondiente según el valor de $title
switch ($title) {
    case 'noticias_all':
        # code...
        $noticias->get_all(); // Llama al método para obtener todas las noticias
        break;
    case "create_noticia":
        $noticias->create_noticia(); // Llama al método para crear una noticia
        break;
    case "delete":
        $noticias->delete(); // Llama al método para eliminar una noticia
        break;
    case "update_noticias":
        $noticias->update_noticias(); // Llama al método para actualizar una noticia
        break;
    default:
        # code...
        break;
}
?>