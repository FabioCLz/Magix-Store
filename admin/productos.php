<?php

require_once "../config/conexion.php";
require_once "../config/session.php";


// =======================
// ELIMINAR PRODUCTO
// =======================

if(isset($_GET['eliminar'])){

    $id = $_GET['eliminar'];


    $producto = $conn->query("
        SELECT imagen 
        FROM productos
        WHERE id_producto=$id
    ")->fetch_assoc();



    if($producto && $producto['imagen']!=""){


        $archivo="../assets/uploads/".$producto['imagen'];


        if(file_exists($archivo)){

            unlink($archivo);

        }

    }



    $conn->query("
        DELETE FROM productos
        WHERE id_producto=$id
    ");



    header("Location: productos.php");
    exit();

}





// =======================
// GUARDAR PRODUCTO
// =======================

if($_SERVER["REQUEST_METHOD"]=="POST"){



    $id = $_POST['id_producto'];


    $categoria = $_POST['categoria'];

    $marca = $_POST['marca'];

    $nombre = $_POST['nombre'];

    $descripcion = $_POST['descripcion'];

    $precio = $_POST['precio'];

    $precio_oferta = $_POST['precio_oferta'];

    $stock = $_POST['stock'];



    // CHECKBOX DESTACADO

    $destacado = isset($_POST['destacado']) ? 1 : 0;




    // PRECIO OFERTA

    if($precio_oferta==""){

        $precio_oferta = "NULL";

    }else{

        $precio_oferta = "'".$precio_oferta."'";

    }





    // =======================
    // SUBIR IMAGEN
    // =======================


    $imagen_actual="";



    if(isset($_FILES['imagen']) && $_FILES['imagen']['name']!=""){



        $extension = pathinfo(
            $_FILES['imagen']['name'],
            PATHINFO_EXTENSION
        );



        $nombreImagen = uniqid("product_").".".$extension;



        $ruta = "../assets/uploads/".$nombreImagen;



        move_uploaded_file(
            $_FILES['imagen']['tmp_name'],
            $ruta
        );



        $imagen_actual=$nombreImagen;



    }






    // =======================
    // NUEVO PRODUCTO
    // =======================


    if($id==""){



        $sql="
        INSERT INTO productos
        (
            id_categoria,
            id_marca,
            nombre,
            descripcion,
            precio,
            precio_oferta,
            imagen,
            stock,
            destacado
        )

        VALUES

        (
            '$categoria',
            '$marca',
            '$nombre',
            '$descripcion',
            '$precio',
            $precio_oferta,
            '$imagen_actual',
            '$stock',
            '$destacado'
        )
        ";




    }else{



        // =======================
        // EDITAR CON IMAGEN
        // =======================


        if($imagen_actual!=""){



            $sql="
            UPDATE productos SET


            id_categoria='$categoria',

            id_marca='$marca',

            nombre='$nombre',

            descripcion='$descripcion',

            precio='$precio',

            precio_oferta=$precio_oferta,

            imagen='$imagen_actual',

            stock='$stock',

            destacado='$destacado'


            WHERE id_producto=$id
            ";



        }else{



            // =======================
            // EDITAR SIN IMAGEN
            // =======================


            $sql="
            UPDATE productos SET


            id_categoria='$categoria',

            id_marca='$marca',

            nombre='$nombre',

            descripcion='$descripcion',

            precio='$precio',

            precio_oferta=$precio_oferta,

            stock='$stock',

            destacado='$destacado'


            WHERE id_producto=$id
            ";


        }



    }




    $conn->query($sql);



    header("Location: productos.php");

    exit();



}






// =======================
// LISTAR PRODUCTOS
// =======================


$productos=$conn->query("

SELECT

p.*,

c.nombre AS categoria,

m.nombre AS marca


FROM productos p


LEFT JOIN categorias c

ON p.id_categoria=c.id_categoria



LEFT JOIN marcas m

ON p.id_marca=m.id_marca



ORDER BY p.id_producto DESC

");





// CATEGORIAS

$categorias=$conn->query("
SELECT * FROM categorias
");




// MARCAS

$marcas=$conn->query("
SELECT * FROM marcas
");

?>
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Productos</title>


<link rel="stylesheet" href="../assets/css/admin.css">


</head>


<body>


<?php include "../includes/sidebar.php"; ?>


<div class="main">


<?php include "../includes/navbar.php"; ?>



<h1>
Productos
</h1>




<button 
class="btn btn-primary"
onclick="abrirModal('modalProducto')">

+ Nuevo Producto

</button>





<table>


<tr>

<th>
Imagen
</th>

<th>
Nombre
</th>

<th>
Categoría
</th>

<th>
Marca
</th>

<th>
Precio
</th>

<th>
Oferta
</th>

<th>
Stock
</th>

<th>
Destacado
</th>

<th>
Acciones
</th>


</tr>





<?php while($p=$productos->fetch_assoc()){ ?>


<tr>


<td>


<?php if($p['imagen']!=""){ ?>


<img

src="../assets/uploads/<?= $p['imagen'] ?>"

width="70"

height="70"

style="
object-fit:cover;
border-radius:10px;
">


<?php }else{ ?>


Sin imagen


<?php } ?>


</td>




<td>

<?= $p['nombre'] ?>

</td>





<td>

<?= $p['categoria'] ?>

</td>





<td>

<?= $p['marca'] ?>

</td>





<td>

$<?= number_format($p['precio'],2) ?>

</td>





<td>


<?php if($p['precio_oferta']!=NULL){ ?>


$<?= number_format($p['precio_oferta'],2) ?>


<?php }else{ ?>


Sin oferta


<?php } ?>


</td>





<td>

<?= $p['stock'] ?>

</td>





<td>


<?php if($p['destacado']==1){ ?>


⭐ Sí


<?php }else{ ?>


No


<?php } ?>


</td>





<td>


<button

class="btn btn-edit"


onclick="editarProducto(

'<?= $p['id_producto'] ?>',

'<?= $p['id_categoria'] ?>',

'<?= $p['id_marca'] ?>',

'<?= htmlspecialchars($p['nombre']) ?>',

'<?= htmlspecialchars($p['descripcion']) ?>',

'<?= $p['precio'] ?>',

'<?= $p['precio_oferta'] ?>',

'<?= $p['stock'] ?>',

'<?= $p['destacado'] ?>'

)"

>

Editar

</button>





<a

class="btn btn-danger"

href="productos.php?eliminar=<?= $p['id_producto'] ?>"

onclick="return confirm('¿Eliminar producto?')">

Eliminar

</a>



</td>



</tr>


<?php } ?>



</table>




</div>









<!-- =======================
MODAL PRODUCTO
======================= -->


<div class="modal" id="modalProducto">


<div class="modal-content">



<h2>

Producto

</h2>





<form method="POST" enctype="multipart/form-data">





<input

type="hidden"

name="id_producto"

id="id_producto">






<select

class="form-control"

name="categoria"

id="categoria"

required>


<option value="">

Seleccione categoría

</option>



<?php while($c=$categorias->fetch_assoc()){ ?>


<option value="<?= $c['id_categoria'] ?>">

<?= $c['nombre'] ?>

</option>


<?php } ?>


</select>








<select

class="form-control"

name="marca"

id="marca"

required>


<option value="">

Seleccione marca

</option>



<?php while($m=$marcas->fetch_assoc()){ ?>


<option value="<?= $m['id_marca'] ?>">

<?= $m['nombre'] ?>

</option>


<?php } ?>


</select>







<input

class="form-control"

type="text"

name="nombre"

id="nombre_producto"

placeholder="Nombre del producto"

required>







<textarea

class="form-control"

name="descripcion"

id="descripcion_producto"

placeholder="Descripción">

</textarea>







<input

class="form-control"

type="number"

step="0.01"

name="precio"

id="precio"

placeholder="Precio"

required>







<input

class="form-control"

type="number"

step="0.01"

name="precio_oferta"

id="precio_oferta"

placeholder="Precio oferta (opcional)">







<input

class="form-control"

type="number"

name="stock"

id="stock"

placeholder="Stock"

required>








<label>

Imagen del producto

</label>


<input

class="form-control"

type="file"

name="imagen"

accept="image/*">







<br>



<label>


<input

type="checkbox"

name="destacado"

id="destacado"

value="1">


Producto destacado ⭐


</label>







<br><br>




<button

class="btn btn-primary">

Guardar

</button>






<button

type="button"

class="btn btn-danger"

onclick="cerrarModal('modalProducto')">

Cancelar

</button>






</form>




</div>


</div>








<script src="../assets/js/admin.js"></script>



</body>

</html>