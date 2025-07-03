<?php
class CitasModel{

    //

    public function get_all_citas_by_user($idLogin, $con){
        $sql = "SELECT * FROM citas c 
        INNER JOIN users_data ud ON ud.idUser = c.idUser
        WHERE ud.idUser = :id";
        $stm = $con->prepare($sql);
        $stm->bindParam("id" , $idLogin);
        $stm->execute();

        $result = $stm->fetchAll();

        return $result;
    }

    public function get_all_citas($con){
        $sql = "SELECT * FROM citas c INNER JOIN users_data ud on ud.idUser = c.idUser";
        $stm = $con->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();
        return $result;


    }

    public function get_user_by_citas($con){
        $sql = "SELECT ud.idUser , ud.nombre , ud.apellidos FROM users_data ud 
        INNER JOIN citas c ON c.idUser = ud.idUser 
        group by ud.idUser , ud.nombre, ud.apellidos";
        $stm = $con->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        return $result;
    }

    public function create($data , $con){
        $sql = "INSERT INTO citas(idUser , fecha_cita, motivo_cita)
        VALUES (:idUser , :fecha_cita , :motivo_cita)";
        $stm = $con->prepare($sql);
        $stm->bindParam("idUser" , $data->idUser);
        $stm->bindParam("fecha_cita" , $data->fecha_cita);
        $stm->bindParam("motivo_cita" , $data->motivo_cita);
        $stm->execute();
        $result = $con->lastInsertId();
        return $result;
    }

    public function update($data , $con){
        try {
            //code...
            $sql = "UPDATE citas SET fecha_cita = :fecha_cita, motivo_cita = :motivo_cita , idUser= :idUser
            WHERE idCita = :idCita";
            $stm = $con->prepare($sql);
            $stm->bindParam("fecha_cita" , $data->fecha_cita);
            $stm->bindParam("motivo_cita" , $data->motivo_cita);
            $stm->bindParam("idCita" , $data->idCita);
            $stm->bindParam("idUser" , $data->idUser);
            $stm->execute();
            return true;
            
        } catch (\Throwable $th) {
            //throw $th;
            echo $th->getMessage();
        }
    }

    public function delete($id , $con){
       try {
        //code...
         $sql = "DELETE FROM citas WHERE citas.idCita = :id";
        $stm = $con->prepare($sql);
        $stm->bindParam("id" , $id);
        $stm->execute();
        return true;
        
       } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
       }
    }

}

?>