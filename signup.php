<?php

session_start();

require_once "config/conexion.php";


$error = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){


    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = $_POST['telefono'];

    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];



    // Verificar correo existente

    $verificar = $conn->query(
        "SELECT * FROM usuarios WHERE email='$email'"
    );


    if($verificar->num_rows > 0){


        $error = "El correo ya está registrado";


    }else{


        $sql = "INSERT INTO usuarios
        (
            nombre,
            apellido,
            email,
            password,
            telefono,
            latitud,
            longitud
        )
        VALUES
        (
            '$nombre',
            '$apellido',
            '$email',
            '$password',
            '$telefono',
            '$latitud',
            '$longitud'
        )";



        if($conn->query($sql)){


            $id_usuario = $conn->insert_id;



            // Login automático

            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido'] = $apellido;
            $_SESSION['email'] = $email;
            $_SESSION['rol'] = "cliente";



            header("Location:index.php");
            exit();



        }else{


            $error = "Error al registrar usuario";


        }


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


<title>Registro</title>


</head>



<body>


<section class="login">


<div class="login__content">



<div>


<h2 class="login__title">
Crear cuenta
</h2>



<?php if($error!=""){ ?>

<p style="
color:red;
text-align:center;
margin-bottom:15px;
">

<?= $error ?>

</p>

<?php } ?>





<form method="POST" class="login__form">



<div class="login__group">



<div class="login__box">

<i class="ri-user-fill login__icon"></i>


<input
type="text"
name="nombre"
required
placeholder=" "
class="login__input">


<label class="login__label">
Nombre
</label>


</div>





<div class="login__box">

<i class="ri-user-fill login__icon"></i>


<input
type="text"
name="apellido"
placeholder=" "
class="login__input">


<label class="login__label">
Apellido
</label>


</div>






<div class="login__box">

<i class="ri-mail-fill login__icon"></i>


<input
type="email"
name="email"
required
placeholder=" "
class="login__input">


<label class="login__label">
Correo electrónico
</label>


</div>






<div class="login__box">

<i class="ri-phone-fill login__icon"></i>


<input
type="text"
name="telefono"
placeholder=" "
class="login__input">


<label class="login__label">
Teléfono
</label>


</div>







<div class="login__box">

<i class="ri-lock-2-fill login__icon"></i>


<input
type="password"
name="password"
required
placeholder=" "
class="login__input">


<label class="login__label">
Contraseña
</label>


</div>



</div>





<p style="
text-align:center;
margin:15px 0;
">

Selecciona tu ubicación

</p>





<input 
type="hidden"
name="latitud"
id="latitud">



<input 
type="hidden"
name="longitud"
id="longitud">





<div id="map"
style="
width:100%;
height:250px;
border-radius:15px;
margin-bottom:20px;
">
</div>





<button
type="submit"
class="login__button">

Registrarse

<i class="ri-send-plane-2-fill"></i>

</button>






<p class="login__sign">

¿Ya tienes cuenta?

<a href="login.php">
Iniciar sesión
</a>


</p>





</form>




</div>






<div class="login__image">


<img src="assets/img/signup.jpg"
class="login__img">


</div>





</div>


</section>







<!-- GSAP -->

<script src="https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/gsap.min.js"></script>



<script>


/*=============== GSAP ANIMATION ===============*/


const tl = gsap.timeline({});



tl.fromTo(
'.login__content',
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
ease:'power3.out'
}
);



tl.to(
'.login__content',
{
scaleY:1,
duration:.6,
ease:'power3.out'
},
'-=0.3'
);



tl.to(
'.login__content',
{
scaleX:1,
duration:.7,
ease:'power3.out'
},
'-=0.2'
);



tl.to(
'.login__img',
{
scale:1.08,
duration:5,
ease:'power1.inOut',
repeat:-1,
yoyo:true
}
);



gsap.defaults({
opacity:0,
y:-60,
ease:'power2.out',
duration:1.2
});


gsap.from('.login__title',{
delay:2.5
});


gsap.from('.login__form > *',{
delay:2.7,
stagger:.2
});


gsap.from('.login__img',{
y:0,
x:100,
delay:3.2,
ease:'elastic.out(1,0.6)'
});



</script>






<script>


let map;

let marker;



function initMap(){


const ubicacion = {

lat:-16.500000,

lng:-68.150000

};



map = new google.maps.Map(
document.getElementById("map"),
{
center:ubicacion,
zoom:15
}
);



marker = new google.maps.Marker({

position:ubicacion,

map:map,

draggable:true

});




guardarCoordenadas(marker.getPosition());




marker.addListener(
"dragend",
function(){

guardarCoordenadas(
marker.getPosition()
);

});



}




function guardarCoordenadas(pos){


document.getElementById("latitud").value =
pos.lat();



document.getElementById("longitud").value =
pos.lng();



}



</script>











<script src="assets/js/login.js"></script>


</body>

</html>