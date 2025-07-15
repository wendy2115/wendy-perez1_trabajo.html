<?php

// Define la clase UsuarioModel para manejar operaciones de la tabla de usuarios
class UsuarioModel{

    // Busca un usuario por su idUser
    public function find_by_idUser($idUser, $con){
        // Consulta SQL para obtener datos del usuario y su login
        $sql = "SELECT * FROM users_data 
        INNER JOIN users_login ON users_login.idUser = users_data.idUser
        WHERE users_data.idUser = :idUser";
        // Prepara la consulta SQL
        $stm = $con->prepare($sql);
        // Asocia el parámetro :idUser con el valor recibido
        $stm->bindParam("idUser" , $idUser);
        // Ejecuta la consulta
        $stm->execute();
        // Obtiene el resultado
        $result = $stm->fetch();
        // Retorna el resultado obtenido
        return $result;
    }

    // Actualiza la contraseña de un usuario por idLogin
    public function updated_password($pass , $id , $con){
        $sql = "UPDATE  users_login set password = :password WHERE idLogin = :idLogin";
        $stm = $con->prepare($sql);
        $stm->bindParam("password", $pass);
        $stm->bindParam("idLogin" , $id);
        if($stm->execute()){
            return true;
        }else{
            return false;
        }
    }

    // Actualiza los datos de un usuario
    public function updated_user($data , $con){
        $sql = "UPDATE users_data set nombre=:nombre , apellidos=:apellidos , email=:email , 
        telefono = :telefono, fecha_nacimiento = :fecha_nacimiento , direccion = :direccion , sexo = :sexo
         WHERE idUser = :idUser";
         $stm = $con->prepare($sql);
         $stm->bindParam("idUser" , $data->idUser);
         $stm->bindParam("nombre" , $data->nombre);
         $stm->bindParam("apellidos" , $data->apellidos);
         $stm->bindParam("email" , $data->email);
         $stm->bindParam("telefono" , $data->telefono);
         $stm->bindParam("fecha_nacimiento" , $data->fecha_nacimiento);
         $stm->bindParam("direccion" , $data->direccion);
         $stm->bindParam("sexo" , $data->sexo);
        if($stm->execute()){
            return true;
        }else{
            return false;
        }
    }

    // Actualiza el rol de un usuario en users_login
    public function edit_rol($data , $con){
        $sql = "UPDATE users_login SET rol = :rol WHERE idLogin = :idLogin";
        $stm = $con->prepare($sql);
        $stm->bindParam("rol" , $data->rol);
        $stm->bindParam("idLogin" , $data->idLogin);
        if($stm->execute()){
            return true;
        }else{
            return false;
        }
    }

    // Busca un usuario por su nombre de usuario
    public function find_by_username($username, $con){
        $sql = "SELECT ul.*, ud.idUser FROM users_login ul
        INNER JOIN users_data ud ON ud.idUser = ul.idUser
        WHERE usuario = :usuario";
        $stm = $con->prepare($sql);
        $stm->bindParam("usuario" , $username);
        $stm->execute();
        $result = $stm->fetch();
        return $result;
    }

    // Obtiene todos los usuarios con su rol y login
    public function get_all_users_data($con){
        $sql = "SELECT ud.* , ul.rol , ul.idLogin FROM users_data ud 
        INNER JOIN users_login ul ON ul.idUser = ud.idUser";
        $stm = $con->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();
        return $result;
    }

    // Busca un usuario por su email
    public function find_by_email($email , $con){
        $sql = "SELECT * FROM users_data WHERE email = :email";
        $stm = $con->prepare($sql);
        $stm->bindParam("email" , $email);
        $stm->execute();
        $result =  $stm->fetch();
        return $result;
    }

    // Crea un nuevo usuario en users_data y su login asociado
    public function create_user_data($info , $con){
        $sql = "INSERT INTO users_data(nombre, apellidos, email,telefono,fecha_nacimiento, direccion , sexo)
        VALUES(:nombre, :apellidos , :email , :telefono, :fecha_nacimiento, :direccion, :sexo)";
        $stm = $con->prepare($sql);
        $stm->bindParam('nombre' , $info->nombre);
        $stm->bindParam('apellidos' , $info->apellidos);
        $stm->bindParam('email' , $info->email);
        $stm->bindParam('telefono' , $info->telefono);
        $stm->bindParam('fecha_nacimiento' , $info->fecha_nacimiento);
        $stm->bindParam('direccion' , $info->direccion);
        $stm->bindParam('sexo' , $info->sexo);
        if($stm->execute())
        {
            $id = $con->lastInsertId(); // Obtiene el id insertado
            $this->create_user_login($con, $id , $info); // Crea el login asociado
            return true;
        }else{
            echo "error ud";
        }
    }

    // Crea el registro de login para un usuario
    public function create_user_login($con , $id, $info){
        $sql = "INSERT INTO users_login(idUser, usuario, password , rol)
        VALUES(:id , :usuario , :password, :rol)";
        $stm = $con->prepare($sql);
        $stm->bindParam("id" , $id);
        $stm->bindParam("usuario" , $info->usuario);
        $stm->bindParam("password" , $info->password);
        $stm->bindParam("rol" , $info->rol);
        if(!$stm->execute()){
            echo "error ul";
        }
    }

    // Elimina un usuario y su login asociado
    public function delete($idUser , $con){
        $sql = "DELETE FROM users_login WHERE idUser = :idUser";
        $stm = $con->prepare($sql);
        $stm->bindParam("idUser" , $idUser);
        if($stm->execute()){
            $sql2 = "DELETE FROM users_data WHERE idUser = :idUser";
            $stm2 = $con->prepare($sql2);
            $stm2->bindParam("idUser" , $idUser);
            if($stm2->execute()){
                return true;
            }
        }
    }
}
?>