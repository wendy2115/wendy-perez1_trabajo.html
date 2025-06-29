<?php

class UsuarioModel{

    

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
}
?>