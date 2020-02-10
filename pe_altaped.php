<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
</head>

<body>
<h1>REALIZAR PEDIDOS</h1>
<?php
session_start();
include "conexion.php";

$productos = obtenerProductos($db);

	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';


?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Producto</div>
<div class="card-body">
	<div class="form-group">
		<label for="nombreProducto">PRODUCTO:</label>
		<select name="nombreProducto">
		<?php foreach($productos as $productos) : ?>
					<option> <?php echo $productos ?> </option>
				<?php endforeach; ?></select><br>
		</select>
	</div>
		<div class="form-group">
        UNIDADES DEL PRODUCTO <input type="number" name="unidades" placeholder="0" required>
        </div>
		<!--<div class="form-group">
        NUMERO DE PAGO <input type="text" name="numPago" placeholder="0" required>
        </div>-->
	</BR>

	<input type="submit" value="Añadir al Carrito">
    <input type="button" value="ATRAS" onclick="window.location.href='pe_inicio.html'">
    
	</br><h3>CARRITO DE LA COMPRA</h3>
    </form>
    

<?php

if($_SERVER["REQUEST_METHOD"]=="POST") { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores

	$nombreProducto=$_REQUEST['nombreProducto'];
	$unidades=limpiar_campo($_REQUEST['unidades']);
	/*$numPago=limpiar_campo($_REQUEST['numPago']);
	if(!preg_match("/^[A-Z]{2}[\d]{5}$/",$numPago)){
		trigger_error('El numero de pago es incorrecto');
	}*/

	$fechaPedido=date("Y-m-d H:m:s");
	$_SESSION['fechaPedido']=$fechaPedido;
	$productCode = selectCodigo($db, $nombreProducto);
	
	carrito($db, $unidades, $productCode);
	visualizarCarrito($db);
	
}


// Funciones utilizadas en el programa

function visualizarCarrito($db){

	$totalCompra=0;

	foreach ($_SESSION['carrito'] as $productCode => $unidades){

		$select="SELECT productName from products where productCode='$productCode'";  
		$resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
		$row=mysqli_fetch_assoc($resultado);
		$nombre=$row['productName'];

		$select="SELECT buyPrice from products where productCode='$productCode'";  
		$resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
		$row=mysqli_fetch_assoc($resultado);
		$precio=$row['buyPrice'];

		$totalCompra=$totalCompra+($precio*$unidades);

		echo 'producto: '.$nombre.' || unidades: '.$unidades.'</br>';
   
	}

	$_SESSION['totalCompra']=$totalCompra;

	echo 'Importe Total: '.$totalCompra.'</br>';

	echo '</br><form action="compraRealizada.php" method=="post"><input type="submit" value="COMPRAR"></form>';
	/*?> <form action="" method=="post"><input type="submit" value="VACIAR CARRITO" onclick="<?php vaciarCarrito()?>"></form>
	<?php*/
}

function carrito($db, $unidades, $productCode){

	$sql="SELECT SUM(quantityInStock) as cantidad from products where productCode= '$productCode'";//Ponerle un alias para el SUM
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$cantidad=$row['cantidad'];//Cantidad del producto de todos los almacenes

	if(!empty($_SESSION["carrito"][$productCode])){
		$unidades=$_SESSION["carrito"][$productCode]+$unidades;
	}
	if($cantidad<$unidades){
		echo 'Las Unidades sobrepasan el stock </br>';
	}
	else{
		$_SESSION["carrito"][$productCode]=$unidades;
	}
	if(!empty($_SESSION["carrito"][$productCode]) && $_SESSION["carrito"][$productCode]<=0){
		unset($_SESSION["carrito"][$productCode]);
	}
	
}

function vaciarCarrito(){
	$_SESSION["carrito"]=array();
}

function selectCodigo($db, $nombreProducto){

    $select="SELECT productCode from products where productName='$nombreProducto'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$productCode=$row['productCode'];

	return $productCode;
}

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

function limpiar_campo($campoformulario) {
    $campoformulario = trim($campoformulario);
    $campoformulario = stripslashes($campoformulario);
    $campoformulario = htmlspecialchars($campoformulario);  
    return $campoformulario;
}

function errores ($error_level, $error_message, $error_file, $error_line, $error_context){
	echo "<b>Codigo error: </b> $error_level - <b> Mensaje: $error_message </b><br>";
	echo "<b>Fichero: $error_file</b><br>";
	echo "<b>Linea: $error_line</b><br>";
	//var_dump($error_context);
	echo "Finalizando script <br>";
	die(); 
//set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
//trigger_error('El DNI '.$DNI.' ya existe previamente');	
}


?>



</body>

</html>