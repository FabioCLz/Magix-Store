<?php

require_once "../config/conexion.php";
require_once "../config/session.php";


// =======================
// CONTADORES
// =======================

$usuarios = $conn->query("
    SELECT COUNT(*) total 
    FROM usuarios
")->fetch_assoc()['total'];


$productos = $conn->query("
    SELECT COUNT(*) total 
    FROM productos
")->fetch_assoc()['total'];


$categorias = $conn->query("
    SELECT COUNT(*) total 
    FROM categorias
")->fetch_assoc()['total'];


$ventas = $conn->query("
    SELECT COUNT(*) total 
    FROM pedidos
")->fetch_assoc()['total'];



// =======================
// ULTIMOS PRODUCTOS
// =======================

$listaProductos = $conn->query("

SELECT 

p.nombre,
p.precio,
p.stock,
c.nombre AS categoria

FROM productos p

LEFT JOIN categorias c

ON p.id_categoria = c.id_categoria

ORDER BY p.id_producto DESC

LIMIT 5

");


?>


<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Dashboard Admin</title>


<link rel="stylesheet" href="../assets/css/admin.css">


</head>


<body>



<!-- SIDEBAR GENERAL -->

<?php include "../includes/sidebar.php"; ?>





<div class="main">



<!-- NAVBAR -->

<?php include "../includes/navbar.php"; ?>





<h1>
Dashboard
</h1>





<div class="cards">



<div class="card">

<h3>
Usuarios
</h3>

<p>
<?= $usuarios ?>
</p>

</div>






<div class="card">

<h3>
Productos
</h3>

<p>
<?= $productos ?>
</p>

</div>






<div class="card">

<h3>
Categorías
</h3>

<p>
<?= $categorias ?>
</p>

</div>






<div class="card">

<h3>
Ventas
</h3>

<p>
<?= $ventas ?>
</p>

</div>




</div>






<h2>
Últimos productos agregados
</h2>





<table>


<thead>

<tr>

<th>
Producto
</th>


<th>
Categoría
</th>


<th>
Precio
</th>


<th>
Stock
</th>


</tr>

</thead>




<tbody>



<?php while($p = $listaProductos->fetch_assoc()){ ?>


<tr>


<td>

<?= $p['nombre'] ?>

</td>



<td>

<?= $p['categoria'] ?? 'Sin categoría' ?>

</td>




<td>

$<?= number_format($p['precio'],2) ?>

</td>




<td>

<?= $p['stock'] ?>

</td>



</tr>



<?php } ?>



</tbody>


</table>





</div>




</body>

</html>