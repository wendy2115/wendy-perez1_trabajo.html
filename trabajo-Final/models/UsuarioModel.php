<?php

class UsuarioModel{

    function find_by_username($username, $con){
        $sql = "SELECT * FROM users_login WHERE usuario = :usuario limit 1,0";
        $stm = $con->prepare($sql);
        $stm->bindParam("usuario" , $usuario);
        $stm->execute();
        $result = $stml->fetchObject();

        return $result;
    }
}
?>