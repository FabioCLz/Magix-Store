function abrirModal(id){
    document.getElementById(id).style.display="flex";
}


function cerrarModal(id){
    document.getElementById(id).style.display="none";
}



window.onclick=function(e){

    let modales=document.querySelectorAll(".modal");

    modales.forEach(modal=>{

        if(e.target==modal){
            modal.style.display="none";
        }

    });

}

function editarProducto(
id,
categoria,
marca,
nombre,
descripcion,
precio,
oferta,
stock,
destacado
){

document.getElementById("id_producto").value=id;

document.getElementById("categoria").value=categoria;

document.getElementById("marca").value=marca;

document.getElementById("nombre_producto").value=nombre;

document.getElementById("descripcion_producto").value=descripcion;

document.getElementById("precio").value=precio;

document.getElementById("precio_oferta").value=oferta ?? "";

document.getElementById("stock").value=stock;


document.getElementById("destacado").checked = destacado == 1;


abrirModal("modalProducto");

}

// cargar datos editar categoria

function editarCategoria(id,nombre,descripcion){

    abrirModal("modalCategoria");

    document.getElementById("id_categoria").value=id;
    document.getElementById("nombre_categoria").value=nombre;
    document.getElementById("descripcion_categoria").value=descripcion;

}



// cargar datos editar producto

function editarProducto(
id,
categoria,
marca,
nombre,
descripcion,
precio,
oferta,
stock
){

    abrirModal("modalProducto");


    document.getElementById("id_producto").value=id;
    document.getElementById("categoria").value=categoria;
    document.getElementById("marca").value=marca;
    document.getElementById("nombre_producto").value=nombre;
    document.getElementById("descripcion_producto").value=descripcion;
    document.getElementById("precio").value=precio;
    document.getElementById("precio_oferta").value=oferta;
    document.getElementById("stock").value=stock;

}