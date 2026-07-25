<?php

session_start();

require_once "config/conexion.php";

$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = trim($_POST['email']);
    $password = $_POST['password'];



    // Buscar usuario

    $stmt = $conn->prepare(
        "SELECT * FROM usuarios WHERE email = ?"
    );


    $stmt->bind_param(
        "s",
        $email
    );


    $stmt->execute();


    $resultado = $stmt->get_result();



    if($resultado->num_rows > 0){


        $usuario = $resultado->fetch_assoc();



        // Verificar contraseña hash

        if(password_verify($password, $usuario['password'])){



            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellido'] = $usuario['apellido'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['rol'] = $usuario['rol'];



            $rol = strtolower(trim($usuario['rol']));



            // Redireccionar según rol

            if($rol == "admin"){


                header("Location: admin/dashboard.php");
                exit();



            }else{


                header("Location: index.php");
                exit();


            }




        }else{


            $error = "Correo o contraseña incorrectos";


        }



    }else{


        $error = "Correo o contraseña incorrectos";


    }



}


?>



<!DOCTYPE html>

<html lang="es">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">


<link rel="stylesheet"
href="assets/css/login.css">



<title>Login</title>


<style>


.login__error{

color:red;
text-align:center;
margin-bottom:15px;
font-weight:600;

}


</style>


</head>



<body>



<section class="login">


<div class="login__content">



<div>



<h2 class="login__title">

Bienvenido

</h2>





<?php if($error!=""){ ?>


<p class="login__error">

<?= $error ?>

</p>


<?php } ?>





<form method="POST" class="login__form">





<div class="login__group">





<div class="login__box">


<i class="ri-mail-fill login__icon"></i>


<input

type="email"

name="email"

required

placeholder=" "

class="login__input"

id="email"



>


<label class="login__label">

Correo electrónico

</label>


</div>







<div class="login__box">


<i class="ri-lock-2-fill login__icon"></i>


<input

type="password"

name="password"

required

placeholder=" "

class="login__input"

id="password"


>


<label class="login__label">

Contraseña

</label>


</div>





</div>








<a href="#" class="login__forgot">

¿Olvidaste tu contraseña?

</a>







<button type="submit" class="login__button">


Log In

<i class="ri-send-plane-2-fill"></i>


</button>







<p class="login__sign">


¿Todavía no tienes cuenta?


<a href="signup.php">

Registrarse

</a>


</p>





</form>





</div>








<div class="login__image">


<img src="assets/img/Login.jpg"

class="login__img">


</div>






</div>


</section>







<script src="https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/gsap.min.js"></script>






<script>


const tl = gsap.timeline();



/* Entrada tarjeta */

tl.fromTo(

".login__content",

{

y:-800,

scaleX:.2,

scaleY:.5,

opacity:0

},

{

y:0,

scaleX:.2,

scaleY:.5,

opacity:1,

duration:1.5,

ease:"power3.out"

}

)



.to(

".login__content",

{

scaleY:1,

duration:.6,

ease:"power3.out"

},

"-=.3"

)



.to(

".login__content",

{

scaleX:1,

duration:.7,

ease:"power3.out"

},

"-=.2"

);







/* Movimiento imagen */

gsap.to(

".login__img",

{

scale:1.08,

duration:5,

repeat:-1,

yoyo:true,

ease:"power1.inOut"

}

);







/* Entrada textos */

gsap.defaults({

opacity:0,

y:-60,

ease:"power2.out",

duration:1.2

});




gsap.from(

".login__title",

{

delay:2.5

}

);





gsap.from(

".login__form > *",

{

delay:2.7,

stagger:.2

}

);





gsap.from(

".login__img",

{

x:100,

delay:3.2,

ease:"elastic.out(1,0.6)"

}

);






<?php if($error!=""){ ?>



// Animación del error

gsap.from(

".login__error",

{

delay:2.8,

y:-40,

opacity:0,

duration:1,

ease:"back.out(1.7)"

}

);


<?php } ?>



</script>



</body>

</html>