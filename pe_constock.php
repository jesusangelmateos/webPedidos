<html>
<head>
</head>

<body>
<h1>CONSULTAR STOCK TOTAL</h1>
<?php
session_start();
include "conexion.php";

$productos = obtenerProductos($db);

?>
<form action="" method="POST">
    <div class="form-group">
        <label for="productLine">PRODUCTO:</label>
        <select name="productLine">
        <?php foreach($productos as $productos) : ?>
                    <option> <?php echo $productos ?> </option>
                <?php endforeach; ?></select><br>
        </select>
    </div>
<input type="submit" value="MOSTRAR"></form>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $productLine=$_REQUEST['productLine'];

    $sql = "SELECT sum(quantityInStock) as cantidad FROM products WHERE productLine='$productLine'";
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    $cantidad=$row['cantidad'];

    echo "Linea de Producto: $productLine || Cantidad: $cantidad";
}
    ?>
    </BR>
    <input type="button" value="INICIO" onclick="window.location.href='pe_inicio.html'">
    <?php

//Funciones propias del programa

function obtenerProductos($db){
    $productos = array();

    $sql = "SELECT distinct productLine FROM products";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$productos[] = $row['productLine'];
		}
	}
	return $productos;
}


?>

</body>

</html>