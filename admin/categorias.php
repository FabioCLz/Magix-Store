<?php

require_once "../config/conexion.php";
require_once "../config/session.php";



// ELIMINAR

if(isset($_GET['eliminar'])){

$id=$_GET['eliminar'];

$conn->query("
DELETE FROM categorias 
WHERE id_categoria=$id
");


header("Location: categorias.php");
exit();

}



// GUARDAR / EDITAR

if($_SERVER["REQUEST_METHOD"]=="POST"){


$id=$_POST['id_categoria'];

$nombre=$_POST['nombre'];

$descripcion=$_POST['descripcion'];



if($id==""){


$sql="
INSERT INTO categorias
(nombre,descripcion)

VALUES
('$nombre','$descripcion')
";



}else{


$sql="
UPDATE categorias SET

nombre='$nombre',

descripcion='$descripcion'

WHERE id_categoria=$id
";


}



$conn->query($sql);


header("Location: categorias.php");

exit();


}




$categorias=$conn->query("
SELECT * FROM categorias
ORDER BY id_categoria DESC
");


?>


<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Categorias</title>


<link rel="stylesheet" href="../assets/css/admin.css">


</head>


<body>



<?php include "../includes/sidebar.php"; ?>


<div class="main">


<?php include "../includes/navbar.php"; ?>



<h1>
Categorías
</h1>



<button class="btn btn-primary"
onclick="abrirModal('modalCategoria')">

+ Nueva categoría

</button>





<table>


<tr>

<th>ID</th>
<th>Nombre</th>
<th>Descripción</th>
<th>Acciones</th>

</tr>




<?php while($c=$categorias->fetch_assoc()){ ?>


<tr>


<td>
<?= $c['id_categoria'] ?>
</td>



<td>
<?= $c['nombre'] ?>
</td>



<td>
<?= $c['descripcion'] ?>
</td>



<td>


<button 
class="btn btn-edit"

onclick="editarCategoria(
'<?= $c['id_categoria']?>',
'<?= $c['nombre']?>',
'<?= $c['descripcion']?>'
)">

Editar

</button>



<a 
class="btn btn-danger"

href="categorias.php?eliminar=<?=$c['id_categoria']?>"

onclick="return confirm('Eliminar categoria?')">

Eliminar

</a>


</td>


</tr>


<?php } ?>


</table>



</div>






<!-- MODAL -->


<div class="modal" id="modalCategoria">


<div class="modal-content">


<h2>
Categoría
</h2>



<form method="POST">



<input 
type="hidden"
name="id_categoria"
id="id_categoria">





<input
class="form-control"
type="text"
name="nombre"
id="nombre_categoria"
placeholder="Nombre"
required>





<textarea
class="form-control"
name="descripcion"
id="descripcion_categoria"
placeholder="Descripción">
</textarea>





<button class="btn btn-primary">

Guardar

</button>



<button 
type="button"
class="btn btn-danger"
onclick="cerrarModal('modalCategoria')">

Cancelar

</button>




</form>



</div>


</div>





<script src="../assets/js/admin.js"></script>


</body>

</html>