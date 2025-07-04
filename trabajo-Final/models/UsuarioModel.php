<?php

class UsuarioModel{

    public function find_by_idUser($idUser, $con){
        $sql = "SELECT * FROM users_data 
        INNER JOIN users_login ON users_login.idUser = users_data.idUser
        WHERE users_data.idUser = :idUser";
        $stm = $con->prepare($sql);
        $stm->bindParam("idUser" , $idUser);
        $stm->execute();
        $result = $stm->fetch();

        return $result;
    }

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

    public function get_all_users_data($con){
        $sql = "SELECT ud.* , ul.rol FROM users_data ud 
        INNER JOIN users_login ul ON ul.idUser = ud.idUser";
        $stm = $con->prepare($sql);
        $stm->execute();
        $result = $stm->fetchAll();

        return $result;
    }

    public function find_by_email($email , $con){
        $sql = "SELECT * FROM users_data WHERE email = :email";
        $stm = $con->prepare($sql);
        $stm->bindParam("email" , $email);
        $stm->execute();
        $result =  $stm->fetch();

        return $result;
    }

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
            $id = $con->lastInsertId();
            $this->create_user_login($con, $id , $info);
            
            return true;
        }else{
            echo "error ud";
        }
    }

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