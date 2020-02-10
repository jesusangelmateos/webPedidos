<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>CONSULTAR STOCK POR PRODUCTO</h1>
<?php
session_start();
include "conexion.php";

$productos = obtenerProductos($db);

?>
<form action="" method="POST">
    <div class="form-group">
        <label for="nombreProducto">PRODUCTO:</label>
        <select name="nombreProducto">
        <?php foreach($productos as $productos) : ?>
                    <option> <?php echo $productos ?> </option>
                <?php endforeach; ?></select><br>
        </select>
    </div>
<input type="submit" action="" method="POST" value="MOSTRAR"></form>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombreProducto=$_REQUEST['nombreProducto'];
    $productCode=selectCode($db, $nombreProducto);

    $sql = "SELECT quantityInStock FROM products WHERE productCode='$productCode'";
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    $cantidad=$row['quantityInStock'];

    echo "Producto: $nombreProducto || Cantidad: $cantidad";
}
?>
</BR>
<input type="button" value="INICIO" onclick="window.location.href='pe_inicio.html'">
<?php

//Funciones propias del programa

function obtenerProductos($db){
    $productos = array();

    $sql = "SELECT productName FROM products where quantityInStock>=0";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$productos[] = $row['productName'];
		}
	}
	return $productos;
}

function selectCode($db, $nombreProducto){

    $sql = "SELECT productCode FROM products WHERE productName='$nombreProducto'";
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    $productCode=$row['productCode'];

    return $productCode;

}

?>

</body>

</html>