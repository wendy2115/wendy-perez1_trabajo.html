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
                echo '
                    <div class="cita-card">
                            <h4>Cita # '.$cita['idCita'].'</h4>
                            <p><strong>Fecha:'.$cita['fecha_cita'].' </strong> </p>
                            <p><strong>Motivo:'.$cita['motivo_cita'].'</strong> </p>
                            
                            
                                <div class="cita-actions">
                                    <button  class="btn btn-small" data-id='.$cita['idCita'].' id="cita_editar">Editar</button>
                                    
                                    <button class="btn btn-small btn-danger" data-id='.$cita['idCita'].' id="cita_delete">Eliminar</button>
                                    
                                </div>
                            
                        </div> 
                ';
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
            }
            $data = new StdClass();
            $data->idUser = $idLogin;
            $data->fecha_cita = $fecha_cita;
            $data->motivo_cita = $motivo_cita;
            $result = $citasModel->create($data , $con);
            
            if($result){
                echo "success";
            }else{
                echo "Error al crear la cita.";
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
     case 'citas_admin':
         
         break;
     case 'citas_user':
         $citas->get_all_citas_by_user();
         break;
     default:
         echo "Error: Acción no reconocida.";
         break;
 }

?>