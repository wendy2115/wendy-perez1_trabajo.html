<?php
require_once '../core/config.php';
require_once '../models/CitasModel.php';
 class Citas{

    public function get_all_citas_by_user(){
        requireLogin();
        $citasModel = new CitasModel();
        $con = conexion();
        $idLogin = $_SESSION["user_id"];
        $citas = $citasModel->get_all_citas_by_user($idLogin , $con);
        if(empty($citas)){
            echo "<span>No tienes citas programadas.</span>";
            exit;
        }else{
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

    public function create_cita(){
        requireLogin();
        $citasModel = new CitasModel();
        $con = conexion();
        $idLogin = $_SESSION["user_id"];
        if(isset($_POST['fecha_cita']) && isset($_POST['motivo_cita'])){
            $fecha_cita = $_POST['fecha_cita'];
            $motivo_cita = $_POST['motivo_cita'];
            
            if(empty($fecha_cita) || empty($motivo_cita)){
                echo "Error: Todos los campos son obligatorios.";
                exit;
            }else if($fecha_cita <= date('Y-m-d')){
                echo "Error: La fecha de la cita no puede ser anterior a la fecha actual.";
                exit;
            }
            $data = new StdClass();
            if($_POST["title"]== "citas_admin"){
                $data->idUser = $_POST['idUser'] ?? $idLogin; // Si es admin, puede seleccionar otro usuario
            }else{
                // Si es usuario normal, se usa su ID de sesión
                $data->idUser = $idLogin;
            }
            $data->fecha_cita = $fecha_cita;
            $data->motivo_cita = $motivo_cita;
            if(isset($_POST['idCita']) && !empty($_POST['idCita'])){
                $data->idCita = $_POST['idCita'];
                $result = $citasModel->update($data , $con);
            }else{
                $data->idCita = null; // Para crear una nueva cita
                $result = $citasModel->create($data , $con);
            }
            
            
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

    public function delete_cita(){
        requireLogin();
        $citasModel = new CitasModel();
        $con = conexion();
        if(isset($_POST['idCita'])){
            $idCita = $_POST['idCita'];
            if(empty($idCita)){
                echo "Error: ID de cita no proporcionado.";
                exit;
            }
            $result = $citasModel->delete($idCita, $con);
            if($result){
                echo "success";
            }else{
                echo "Error al eliminar la cita: ".$result;
            }
        }else{
            echo "Error: Datos incompletos.";
        }
    }

 }

 $citas = new Citas();

 $title = $_POST['title'] ?? '';

 switch ($title) {
     case 'cita_create':
         $citas->create_cita();
         break;
    case 'cita_update':
            $citas->create_cita();
            break;
        break;
     case 'citas_admin':
         requireAdmin();
         $citas->create_cita();
         break;
     case 'citas_user':
         $citas->get_all_citas_by_user();
         break;
    case 'delete_cita':
        $citas->delete_cita();
        break;
     default:
         echo "Error: Acción no reconocida.";
         break;
 }

?>