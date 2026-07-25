<?php

require_once "config/conexion.php";


// ===================================
// PRODUCTOS DESTACADOS
// ===================================

$productos = $conn->query("

SELECT 
    p.id_producto,
    p.nombre,
    p.descripcion,
    p.precio,
    p.precio_oferta,
    p.imagen,
    p.stock,
    p.destacado,

    c.nombre AS categoria,
    m.nombre AS marca

FROM productos p

LEFT JOIN categorias c
ON p.id_categoria = c.id_categoria

LEFT JOIN marcas m
ON p.id_marca = m.id_marca

WHERE p.destacado = 1

ORDER BY p.fecha_creacion DESC

LIMIT 6

");


if(!$productos){

    die("Error productos: ".$conn->error);

}




// ===================================
// PRODUCTOS EN OFERTA
// ===================================

$productosOferta = $conn->query("

SELECT *

FROM productos

WHERE precio_oferta IS NOT NULL

ORDER BY id_producto DESC

LIMIT 6

");


if(!$productosOferta){

    die("Error ofertas: ".$conn->error);

}




// ===================================
// PROMOCIONES
// ===================================

$promociones = $conn->query("

SELECT *

FROM promociones

ORDER BY fecha_inicio DESC

LIMIT 3

");




// ===================================
// BLOG
// ===================================

$blogs = $conn->query("

SELECT *

FROM blog

ORDER BY fecha_publicacion DESC

LIMIT 3

");



?>



<!DOCTYPE html>

<html lang="es">

<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">



<title>Magix Store</title>




<link rel="stylesheet" href="assets/css/style.css">



<link rel="preconnect" href="https://fonts.googleapis.com">


<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>



<link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">



</head>




<body id="top">


<header class="header">


<div class="alert">


<div class="container">


<p class="alert-text">

Envíos gratis en todas tus compras

</p>


</div>


</div>





<div class="header-top">



<div class="container">





<button class="nav-open-btn">

<span></span>
<span></span>
<span></span>

</button>





<div class="input-wrapper">


<input 
type="search"
placeholder="Buscar producto"
class="search-field">


<button class="search-submit">

<ion-icon name="search-outline"></ion-icon>

</button>


</div>






<a href="index.php" class="logo">


<img src="assets/images/logo.png"
width="179"
height="26">


</a>







<div class="header-actions">



<a href="login.php"
class="header-action-btn">

<ion-icon name="person-outline"></ion-icon>

</a>




<a href="#"
class="header-action-btn">

<ion-icon name="star-outline"></ion-icon>

<span class="btn-badge">

0

</span>

</a>




<a href="#"
class="header-action-btn">


<ion-icon name="bag-handle-outline"></ion-icon>


<span class="btn-badge">

0

</span>


</a>




</div>





<nav class="navbar">


<ul class="navbar-list">



<li>
<a href="#home" class="navbar-link">
Inicio
</a>
</li>



<li>
<a href="#shop" class="navbar-link">
Productos
</a>
</li>



<li>
<a href="#offer" class="navbar-link">
Ofertas
</a>
</li>



<li>
<a href="#blog" class="navbar-link">
Blog
</a>
</li>



</ul>


</nav>



</div>


</div>


</header>
<main>

<article>


<!-- ===============================
     HERO
================================ -->

<section class="section hero"
id="home">

<div class="container">


<ul class="has-scrollbar">


<li class="scrollbar-item">


<div class="hero-card has-bg-image"
style="background-image:url('assets/images/hero-banner-1.jpg')">


<div class="card-content">


<h1 class="h1 hero-title">

Productos de alta <br>
Gama para ti

</h1>


<p class="hero-text">

Productos 100% originales y de calidad.

</p>


<a href="#shop"
class="btn btn-primary">

Comprar ahora

</a>


</div>


</div>


</li>



</ul>


</div>


</section>






<!-- ===============================
     PROMOCIONES
================================ -->


<section class="section collection"
id="collection">


<div class="container">


<ul class="collection-list">



<?php if($promociones && $promociones->num_rows > 0){ ?>


<?php while($promo = $promociones->fetch_assoc()){ ?>


<li>


<div class="collection-card">



<h2 class="h2">

<?= $promo['nombre']; ?>

</h2>



<p>

<?= $promo['descripcion']; ?>

</p>




<div class="has-bg-image"
style="
background-image:url('assets/images/<?= $promo['imagen']; ?>')
">


</div>



</div>


</li>



<?php } ?>


<?php } ?>



</ul>


</div>


</section>






<!-- ===============================
     PRODUCTOS DESTACADOS
================================ -->

<section class="section shop"
id="shop">


<div class="container">


<h2 class="h2 section-title">

Productos destacados

</h2>





<ul class="has-scrollbar">



<?php if($productos && $productos->num_rows > 0){ ?>



<?php while($p = $productos->fetch_assoc()){ ?>



<li class="scrollbar-item">


<div class="shop-card">



<div class="card-banner img-holder">



<img 
src="./assets/uploads/<?= $p['imagen']; ?>"
width="540"
height="720"
loading="lazy"
alt="<?= $p['nombre']; ?>"
class="img-cover">




<?php if($p['precio_oferta'] != null){ ?>


<span class="badge">

Oferta

</span>


<?php } ?>



<div class="card-actions">



<button class="action-btn">


<ion-icon name="bag-handle-outline"></ion-icon>


</button>




<button class="action-btn">


<ion-icon name="star-outline"></ion-icon>


</button>



</div>


</div>







<div class="card-content">





<div class="price">


<?php if($p['precio_oferta'] != null){ ?>



<del>

$
<?= number_format($p['precio'],2); ?>

</del>



<span class="span">

$
<?= number_format($p['precio_oferta'],2); ?>

</span>



<?php }else{ ?>


<span class="span">

$
<?= number_format($p['precio'],2); ?>

</span>


<?php } ?>


</div>





<h3 class="h3">


<a href="#">

<?= $p['nombre']; ?>

</a>


</h3>






<p>

<?= $p['descripcion']; ?>

</p>





<p>

Stock:
<?= $p['stock']; ?>

</p>





<p>

Categoría:
<?= $p['categoria']; ?>

</p>






</div>


</div>


</li>



<?php } ?>



<?php }else{ ?>



<h3>

No existen productos destacados

</h3>



<?php } ?>





</ul>


</div>


</section>








<!-- ===============================
     PRODUCTOS EN OFERTA
================================ -->

<section class="section shop"
id="offer">


<div class="container">



<h2 class="h2 section-title">

Productos en oferta

</h2>





<ul class="has-scrollbar">



<?php if($productosOferta && $productosOferta->num_rows > 0){ ?>



<?php while($oferta = $productosOferta->fetch_assoc()){ ?>



<li class="scrollbar-item">


<div class="shop-card">



<div class="card-banner img-holder">



<img
src="./assets/uploads/<?= $oferta['imagen']; ?>"
width="540"
height="720"
loading="lazy"
alt="<?= $oferta['nombre']; ?>"
class="img-cover">




<span class="badge">

Oferta

</span>



</div>






<div class="card-content">



<div class="price">



<del>

$
<?= number_format($oferta['precio'],2); ?>

</del>



<span class="span">

$
<?= number_format($oferta['precio_oferta'],2); ?>

</span>



</div>






<h3 class="h3">


<a href="#">

<?= $oferta['nombre']; ?>

</a>


</h3>






<p>

<?= $oferta['descripcion']; ?>

</p>





<p>

Disponible:
<?= $oferta['stock']; ?>

unidades

</p>




</div>




</div>


</li>



<?php } ?>



<?php }else{ ?>

<h3>

No hay productos en oferta

</h3>


<?php } ?>




</ul>




</div>



</section>
<!-- ===============================
     BANNER
================================ -->

<section class="section banner">


<div class="container">


<ul class="banner-list">


<li>


<div class="banner-card banner-card-1">


<p class="card-subtitle">

Nueva colección

</p>


<h2 class="h2 card-title">

Descubre nuevas marcas que podrian gustarte

</h2>


<a href="#shop"
class="btn btn-secondary">

Explorar

</a>



<div class="has-bg-image"

style="
background-image:url('assets/images/banner-1.jpg')
">

</div>


</div>


</li>





<li>


<div class="banner-card banner-card-2">


<h2 class="h2 card-title">

Ofertas especiales

</h2>


<p class="card-text">

Descuentos exclusivos en nuestros productos.

</p>


<a href="#offer"
class="btn btn-secondary">

Comprar ahora

</a>



<div class="has-bg-image"

style="
background-image:url('assets/images/banner-2.jpg')
">

</div>



</div>


</li>



</ul>


</div>


</section>






<!-- ===============================
     CARACTERISTICAS
================================ -->


<section class="section feature">


<div class="container">


<h2 class="h2-large section-title">

¿Por qué comprar con nosotros?

</h2>





<ul class="flex-list">



<li class="flex-item">


<div class="feature-card">


<img 
src="assets/images/feature-1.jpg"
class="card-icon">



<h3 class="h3">

Productos de calidad

</h3>



<p>

Seleccionamos los mejores productos para ti.

</p>



</div>


</li>





<li class="flex-item">


<div class="feature-card">


<img 
src="assets/images/feature-2.jpg"
class="card-icon">



<h3 class="h3">

Compra segura

</h3>



<p>

Tus compras protegidas y fáciles.

</p>



</div>


</li>





<li class="flex-item">


<div class="feature-card">


<img 
src="assets/images/feature-3.jpg"
class="card-icon">



<h3 class="h3">

Atención personalizada

</h3>



<p>

Estamos para ayudarte.

</p>



</div>


</li>



</ul>



</div>


</section>








<!-- ===============================
     BLOG
================================ -->

<section class="section blog"
id="blog">


<div class="container">


<h2 class="h2-large section-title">

Consejos para limpieza de tus perifericos

</h2>





<ul class="flex-list">



<?php if($blogs && $blogs->num_rows > 0){ ?>



<?php while($blog = $blogs->fetch_assoc()){ ?>



<li class="flex-item">


<div class="blog-card">



<figure class="card-banner">


<img

src="assets/images/<?= $blog['imagen']; ?>"

class="img-cover"

alt="<?= $blog['titulo']; ?>"

onerror="this.src='assets/images/no-image.png'"

>


</figure>





<h3 class="h3">


<a href="#">

<?= $blog['titulo']; ?>

</a>


</h3>





<p>

<?= $blog['contenido']; ?>

</p>



<a href="#" class="btn-link">

Leer más

</a>




</div>


</li>



<?php } ?>



<?php } ?>



</ul>



</div>


</section>









</article>

</main>









<!-- ===============================
     FOOTER
================================ -->


<footer class="footer">


<div class="container">



<div class="footer-top">





<div class="footer-list">


<p class="footer-list-title">

Magix Store

</p>



<p>

Productos de belleza y cuidado personal.

</p>



<p>

+591 70000000

</p>



<p>

shop@gmail.com

</p>



</div>







<div class="footer-list">


<p class="footer-list-title">

Enlaces

</p>



<a href="#shop"
class="footer-link">

Productos

</a>



<a href="#offer"
class="footer-link">

Ofertas

</a>



<a href="#blog"
class="footer-link">

Blog

</a>



</div>







<div class="footer-list">



<p class="footer-list-title">

Newsletter

</p>




<p>

Recibe novedades y promociones.

</p>





<form>


<input

type="email"

placeholder="Tu correo"

class="email-field"

>




<button class="btn btn-primary">

Suscribirse

</button>



</form>




</div>





</div>









<div class="footer-bottom">


<p>


© 2026 Magix Store. Todos los derechos reservados.


</p>


</div>





</div>


</footer>








<a href="#top"
class="back-top-btn">


<ion-icon name="arrow-up"></ion-icon>


</a>










<!-- ===============================
     JAVASCRIPT
================================ -->


<script src="assets/js/script.js"></script>





<script type="module"
src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js">
</script>



<script nomodule
src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js">
</script>




</body>

</html> 