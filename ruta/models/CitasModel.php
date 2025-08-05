<?php
// Definición de la clase CitasModel para manejar operaciones relacionadas con la tabla 'citas'
class CitasModel{

    //

    // Obtiene todas las citas asociadas a un usuario específico
    public function get_all_citas_by_user($idLogin, $con){
        // Consulta SQL para seleccionar todas las citas y datos del usuario por idUser
        $sql = "SELECT * FROM citas c 
        INNER JOIN users_data ud ON ud.idUser = c.idUser
        WHERE ud.idUser = :id";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia el parámetro :id con el valor de $idLogin
        $stm->bindParam("id" , $idLogin);
        // Ejecuta la consulta
        $stm->execute();
        // Obtiene todos los resultados de la consulta
        $result = $stm->fetchAll();
        // Retorna el resultado obtenido
        return $result;
    }

    // Obtiene todas las citas junto con los datos de usuario
    public function get_all_citas($con){
        // Consulta SQL para seleccionar todas las citas y datos de usuario
        $sql = "SELECT * FROM citas c INNER JOIN users_data ud on ud.idUser = c.idUser";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Ejecuta la consulta
        $stm->execute();
        // Obtiene todos los resultados de la consulta
        $result = $stm->fetchAll();
        // Retorna el resultado obtenido
        return $result;
    }

    // Obtiene los usuarios que tienen al menos una cita
    public function get_user_by_citas($con){
        // Consulta SQL para seleccionar usuarios únicos que tienen citas
        $sql = "SELECT ud.idUser , ud.nombre , ud.apellidos FROM users_data ud 
        INNER JOIN citas c ON c.idUser = ud.idUser 
        group by ud.idUser , ud.nombre, ud.apellidos";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Ejecuta la consulta
        $stm->execute();
        // Obtiene todos los resultados de la consulta
        $result = $stm->fetchAll();
        // Retorna el resultado obtenido
        return $result;
    }

    // Crea una nueva cita en la base de datos
    public function create($data , $con){
        // Consulta SQL para insertar una nueva cita
        $sql = "INSERT INTO citas(idUser , fecha_cita, motivo_cita)
        VALUES (:idUser , :fecha_cita , :motivo_cita)";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia los parámetros con los valores del objeto $data
        $stm->bindParam("idUser" , $data->idUser);
        $stm->bindParam("fecha_cita" , $data->fecha_cita);
        $stm->bindParam("motivo_cita" , $data->motivo_cita);
        // Ejecuta la consulta
        $stm->execute();
        // Obtiene el último id insertado
        $result = $con->lastInsertId();
        // Retorna el id de la nueva cita
        return $result;
    }

    // Actualiza una cita existente en la base de datos
    public function update($data , $con){
        try {
            // Consulta SQL para actualizar una cita por idCita
            $sql = "UPDATE citas SET fecha_cita = :fecha_cita, motivo_cita = :motivo_cita , idUser= :idUser
            WHERE idCita = :idCita";
            // Prepara la consulta SQL
            $stm = $con->prepare($sql);
            // Asocia los parámetros con los valores del objeto $data
            $stm->bindParam("fecha_cita" , $data->fecha_cita);
            $stm->bindParam("motivo_cita" , $data->motivo_cita);
            $stm->bindParam("idCita" , $data->idCita);
            $stm->bindParam("idUser" , $data->idUser);
            // Ejecuta la consulta
            $stm->execute();
            // Retorna true si la actualización fue exitosa
            return true;
        } catch (\Throwable $th) {
            // Muestra el mensaje de error si ocurre una excepción
            echo $th->getMessage();
        }
    }

    // Elimina una cita de la base de datos por su id
    public function delete($id , $con){
       try {
        // Consulta SQL para eliminar una cita por idCita
         $sql = "DELETE FROM citas WHERE citas.idCita = :id";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia el parámetro :id con el valor de $id
        $stm->bindParam("id" , $id);
        // Ejecuta la consulta
        $stm->execute();
        // Retorna true si la eliminación fue exitosa
        return true;
       } catch (\Throwable $th) {
        // Muestra el mensaje de error si ocurre una excepción
        echo $th->getMessage();
       }
    }

}

?>