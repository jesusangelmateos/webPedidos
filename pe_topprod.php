<html>
<head>
</head>
<body>
<h1>PRODUCTOS ENTRE DOS FECHAS</h1>
<?php
session_start();
include "conexion.php";
set_error_handler("errores");


?>
<form action="" method="POST">
    <div>
        <label for="fechaIni">FECHA DESDE:</label>
        <input type="date" name="fechaDesde" placeholder="fecha de inicio">
    </div>
    <div></br>
        <label for="fechaFin">FECHA HASTA:</label>
        <input type="date" name="fechaHasta" placeholder="fecha de fin">
    </div></br>
<input type="submit" value="MOSTRAR"></form>

<?php


if($_SERVER["REQUEST_METHOD"] == "POST"){

    $fechaDesde=strtotime($_REQUEST['fechaDesde']);
	$fechaDesde=date("Y-m-d", $fechaDesde);
    if($fechaDesde==""){
		trigger_error('Fecha desde no puede estar vacia');	
	}
	$fechaHasta=strtotime($_REQUEST['fechaHasta']);
	$fechaHasta=date("Y-m-d", $fechaHasta);
	if($fechaHasta==""){
		trigger_error('Fecha hasta no puede estar vacia');	
    }
    
    $productos = array();
    $productos = obtenerProductos($db);

    listar($db, $productos, $fechaDesde, $fechaHasta);

}

//Funciones utilizadas en el programa
function errores ($error_level, $error_message, $error_file, $error_line, $error_context){
	echo "<b>Codigo error: </b> $error_level - <b> Mensaje: $error_message </b><br>";
	echo "<b>Fichero: $error_file</b><br>";
	echo "<b>Linea: $error_line</b><br>";
	//var_dump($error_context);
	echo "Finalizando script <br>";
    die(); 
    	
}

function obtenerProductos($db){
    $productos = array();

    $sql = "SELECT productCode FROM products";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$productos[] = $row['productCode'];
		}
	}
	return $productos;
}


function listar($db, $productos, $fechaDesde, $fechaHasta){
     
    foreach($productos as $producto){

        $sql="SELECT SUM(orderdetails.quantityOrdered) as cantidadTotal, orderDate FROM orderdetails, orders, products WHERE orderdetails.orderNumber=orders.orderNumber and products.productCode=orderdetails.productCode and products.productCode='$producto' and DATE_FORMAT(orders.orderDate,'%Y-%m-%d')>='$fechaDesde' AND DATE_FORMAT(orders.orderDate,'%Y-%m-%d')<='$fechaHasta'";
        $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
        $row=mysqli_fetch_assoc($resultado);
        $cantidadTotal=$row['cantidadTotal'];
        $orderDate=$row['orderDate'];

        if(is_numeric($cantidadTotal)){
        echo "Producto: $producto || Unidades vendidas: $cantidadTotal || fecha: $orderDate</br>";
        }

    }

}


?>
</BR>
    <input type="button" value="INICIO" onclick="window.location.href='pe_inicio.html'">
    
</body>

</html>