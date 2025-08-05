<?php
// Incluye el archivo de configuración principal
require_once '../core/config.php';
// Incluye el modelo de citas
require_once '../models/CitasModel.php';

// Define la clase Citas para manejar operaciones relacionadas con citas
class Citas{

    // Obtiene todas las citas del usuario logueado
    public function get_all_citas_by_user(){
        requireLogin(); // Verifica que el usuario esté logueado
        $citasModel = new CitasModel(); // Instancia el modelo de citas
        $con = conexion(); // Obtiene la conexión a la base de datos
        $idLogin = $_SESSION["user_id"]; // Obtiene el id del usuario logueado
        $citas = $citasModel->get_all_citas_by_user($idLogin , $con); // Obtiene las citas del usuario
        if(empty($citas)){
            // Muestra mensaje si no hay citas
            echo "<span>No tienes citas programadas.</span>";
            exit;
        }else{
            // Recorre y muestra cada cita
            foreach($citas as $cita){
                echo "
                    <div class='cita-card'>
                            <h4>Cita # ".$cita['idCita']."</h4>
                            <p><strong>Fecha:".$cita['fecha_cita']." </strong> </p>
                            <p><strong>Motivo:".$cita['motivo_cita']."</strong> </p>
                                <div class='cita-actions'>
                                    <button  class='btn btn-small' data-cita='".json_encode($cita)."' id='btn_cita_editar'>Editar</button>
                                    <button class='btn btn-small btn-danger' data-id='".$cita['idCita']."' id='btn_delete_cita'>Eliminar</button>
                                </div>
                        </div> 
                ";
            }
        }
    }

    // Crea o actualiza una cita
    public function create_cita(){
        requireLogin(); // Verifica que el usuario esté logueado
        $citasModel = new CitasModel(); // Instancia el modelo de citas
        $con = conexion(); // Obtiene la conexión a la base de datos
        $idLogin = $_SESSION["user_id"]; // Obtiene el id del usuario logueado
        // Verifica que se hayan enviado los datos necesarios por POST
        if(isset($_POST['fecha_cita']) && isset($_POST['motivo_cita'])){
            $fecha_cita = $_POST['fecha_cita']; // Fecha de la cita
            $motivo_cita = $_POST['motivo_cita']; // Motivo de la cita
            // Valida que los campos no estén vacíos
            if(empty($fecha_cita) || empty($motivo_cita)){
                echo "Error: Todos los campos son obligatorios.";
                exit;
            }else if($fecha_cita <= date('Y-m-d')){
                // Valida que la fecha no sea anterior a la actual
                echo "Error: La fecha de la cita no puede ser anterior a la fecha actual.";
                exit;
            }
            $data = new StdClass(); // Crea un objeto para los datos de la cita
            // Si es admin, puede seleccionar otro usuario
            if($_POST["title"]== "citas_admin"){
                $data->idUser = $_POST['idUser'] ?? $idLogin;
            }else{
                // Si es usuario normal, se usa su ID de sesión
                $data->idUser = $idLogin;
            }
            $data->fecha_cita = $fecha_cita;
            $data->motivo_cita = $motivo_cita;
            // Si se envía idCita, se actualiza la cita, si no, se crea una nueva
            if(isset($_POST['idCita']) && !empty($_POST['idCita'])){
                $data->idCita = $_POST['idCita'];
                $result = $citasModel->update($data , $con);
            }else{
                $data->idCita = null;
                $result = $citasModel->create($data , $con);
            }
            // Muestra mensaje según el resultado
            if($result){
                echo "success";
                exit;
            }else{
                echo "Error al crear la cita.";
                exit;
            }
        }else{
            echo "Error: Datos incompletos.";
            exit;
        }
    }

    // Elimina una cita
    public function delete_cita(){
        $citasModel = new CitasModel(); // Instancia el modelo de citas
        $con = conexion(); // Obtiene la conexión a la base de datos
        // Verifica que se haya enviado el id de la cita por POST
        if(isset($_POST['idCita'])){
            $idCita = $_POST['idCita'];
            // Valida que el id de la cita no esté vacío
            if(empty($idCita)){
                echo "Error: ID de cita no proporcionado.";
                exit;
            }
            $result = $citasModel->delete($idCita, $con); // Elimina la cita
            if($result){
                echo "success";
                exit;
            }else{
                echo "Error al eliminar la cita: ".$result;
                exit;
            }
        }else{
            echo "Error: Datos incompletos.";
            exit;
        }
    }

    // Muestra la tabla de citas de un usuario específico (solo admin)
    public function get_table_citas_by_user(){
        requireAdmin(); // Verifica que el usuario sea administrador
        $idUser = $_POST["idUser"] ?? ""; // Obtiene el id del usuario por POST
        $con = conexion(); // Obtiene la conexión a la base de datos
        if(!empty($idUser)){
            $citasModel = new CitasModel(); // Instancia el modelo de citas
            $citas = $citasModel->get_all_citas_by_user($idUser , $con); // Obtiene las citas del usuario
            if($citas > 0){
                // Recorre y muestra cada cita en una fila de tabla
                foreach($citas as $cita){
                    echo "<tr>
                            <td>".$cita["idCita"]."</td>
                            <td>".$cita["fecha_cita"]."</td>
                            <td>".$cita["motivo_cita"]."</td>
                            <td>
                            <button class='btn-rounded-success' data-item='".json_encode($cita)."' id='admin_edit_cita'><i class='fa-solid fa-pencil'></i></button>
                            <button class='btn-rounded-danger' data-id='".$cita["idCita"]."' id='admin_delete_cita'><i class='fa-solid fa-trash'></i></button>
                            </td>
                        </tr>";
                }
            }
        }
    }

}

// Instancia la clase Citas
$citas = new Citas();

// Obtiene el valor de 'title' enviado por POST para determinar la acción a ejecutar
$title = $_POST['title'] ?? '';

// Ejecuta la acción correspondiente según el valor de $title
switch ($title) {
    case 'cita_create':
        $citas->create_cita(); // Crea una cita
        break;
    case 'cita_update':
        $citas->create_cita(); // Actualiza una cita
        break;
    case 'citas_admin':
        requireAdmin(); // Verifica que el usuario sea administrador
        $citas->create_cita(); // Crea o actualiza una cita como admin
        break;
    case 'citas_user':
        $citas->get_all_citas_by_user(); // Muestra las citas del usuario logueado
        break;
    case 'delete_cita':
        $citas->delete_cita(); // Elimina una cita
        break;
    case 'get_table_citas_by_user':
        $citas->get_table_citas_by_user(); // Muestra la tabla de citas de un usuario (admin)
        break;
    default:
        // Muestra mensaje si la acción no es reconocida
        echo "Error: Acción no reconocida.";
        break;
}

?>