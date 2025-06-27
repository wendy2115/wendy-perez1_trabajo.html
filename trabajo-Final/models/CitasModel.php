<?php
class CitasModel{

    public find_by_user($idUser , $con){
        $sql = "SELECT * FROM citas c INNER JOIN users_data ud ON ud.idUser = c.idUser
        WHERE c.idUser = :id";
        $stm = $con->prepare($sql);
        $stm->bindParam("id" , $id);
        $stm->execute();

        $result = $stm->fetchAll();

        return $result;
    }

    public get_all_citas($con){
        $sql = "SELECT * FROM citas c INNER JOIN users_data ud on ud.idUser = c.idUser";
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
    }

    public function delete($id , $con){
        $sql = "DELETE FROM citas WHERE citas.idCita = :id";
        $stm = $con->prepare($sql);
        $stm->bindParam("id" , $id);
        $stm->execute();
    }

}

?>