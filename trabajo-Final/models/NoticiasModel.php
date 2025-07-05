<?php
// Define la clase NoticiasModel para manejar operaciones de la tabla noticias
class NoticiasModel{

    // Busca noticias por id de usuario
    public function find_by_user($id , $con){
        // Consulta SQL para obtener noticias y datos de usuario por id
        $sql = "SELECT * FROM noticias n INNER JOIN users_data ud ON ud.idUser = n.idUser
         WHERE n.idUser = :id ";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia el parámetro :id con el valor recibido
        $stm->bindParam("id" , $id);
        // Ejecuta la consulta
        $stm->execute();
        // Obtiene todos los resultados
        $result = $stm->fetchAll();
        // Retorna los resultados obtenidos
        return $result;
    }

    // Obtiene todas las noticias con datos de usuario
    public function get_all($con){
        // Consulta SQL para obtener todas las noticias y datos de usuario
        $sql = "SELECT * FROM noticias n INNER JOIN users_data ud ON ud.idUser = n.idUser";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Ejecuta la consulta
        $stm->execute();
        // Obtiene todos los resultados
        $result = $stm->fetchAll();
        // Retorna los resultados obtenidos
        return $result;
    }

    // Crea una nueva noticia
    public function create($data , $con){
        // Consulta SQL para insertar una noticia
        $sql = "INSERT INTO noticias(titulo, imagen , texto, fecha,idUser)
        VALUES(:titulo , :imagen , :texto , :fecha , :idUser)";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia los parámetros con los valores del objeto $data
        $stm->bindParam("titulo" , $data->titulo);
        $stm->bindParam("imagen" , $data->imagen);
        $stm->bindParam("texto" , $data->texto);
        $stm->bindParam("fecha" , $data->fecha);
        $stm->bindParam("idUser" , $data->idUser);
        // Ejecuta la consulta y retorna el id insertado si fue exitosa
        if($stm->execute()){
            return $con->lastInsertId();
        }
    }

    // Elimina una noticia por id
    public function delete($id , $con){
        // Consulta SQL para eliminar una noticia por id
        $sql = "DELETE FROM noticias WHERE idNoticia = :id";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia el parámetro :id con el valor recibido
        $stm->bindParam("id" , $id);
        // Ejecuta la consulta y retorna true si fue exitosa
        if($stm->execute()){
            return true;
        }
    }

    // Actualiza una noticia existente
    public function updated($data , $con){
        // Consulta SQL para actualizar los campos de una noticia por id
        $sql = "UPDATE noticias SET titulo = :titulo , fecha = :fecha , texto = :texto WHERE idNoticia = :idNoticia ";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia los parámetros con los valores del objeto $data
        $stm->bindParam("titulo" , $data->titulo);
        $stm->bindParam("fecha" , $data->fecha);
        $stm->bindParam("texto" , $data->texto);
        $stm->bindParam("idNoticia" , $data->idNoticia);
        // Ejecuta la consulta y retorna true si fue exitosa
        if($stm->execute()){
            return true;
        }
    }

}

?>