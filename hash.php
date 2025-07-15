<?php

$data = "123456";

$pass = password_hash($data , PASSWORD_DEFAULT);

echo $pass;

?>