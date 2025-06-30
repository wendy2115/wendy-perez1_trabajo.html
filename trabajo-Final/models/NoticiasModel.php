<?php
 class NoticiasModel{

    public function find_by_user($id , $con){
        $sql = "SELECT * FROM noticias n INNER JOIN users_data ud ON ud.idUser = n.idUser
         WHERE n.idUser = :id ";
        $stm = $con->prepare($sql);
        $stm->bindParam("id" , $id);
        $stm->execute();

        $result = $stm->fetchAll();

        return $result;
    }

    public function get_all($con){
        $sql = "SELECT * FROM noticias n INNER JOIN users_data ud ON ud.idUser = n.idUser";
        $stm = $con->prepare();
        $stm->execute();

        $result = $stm->fetchAll();

        return $result;
    }

    public function create($data , $con){
        $sql = "INSERT INTO noticias(titulo, imagen , texto, fecha,idUser)
        VALUES(:titulo , :imagen , :texto , :fecha , :idUser)";
        $stm = $con->prepare($sql);
        $stm->bindParam("titulo" , $data->titulo);
        $stm->bindParam("imagen" , $data->imagen);
        $stm->bindParam("texto" , $data->texto);
        $stm->bindParam("fecha" , $data->fecha);
        $stm->bindParam("idUser" , $data->idUser);

    }

    public function delete($id , $con){
        $sql = "DELETE FROM noticias WHERE idNoticias = :id";
        $stm = $con->prepare($sql);
        $stm->bindParam("id" , $id);
        $stm->execute();
    }

    public function updated($data , $con){

    }

 }

?>