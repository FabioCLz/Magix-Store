<?php

session_start();


if(!isset($_SESSION['id_usuario'])){

    header("Location: ../login.php");
    exit();

}



if($_SESSION['rol'] != "admin"){

    header("Location: ../index.php");
    exit();

}


?>