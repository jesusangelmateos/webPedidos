<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>CONSULTAR PEDIDO</h1>
<?php
session_start();
include "conexion.php";

$customerNumber=$_SESSION['admin'];//id de la tabla admin

$nombreCliente=selectNombre($db, $customerNumber);

$pedidos=array();
$select="SELECT orderNumber from orders where customerNumber='$customerNumber'";  
$resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
$row=mysqli_fetch_assoc($resultado);
if ($resultado) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        $pedidos[] = $row['orderNumber'];
    }
}
//var_dump($pedidos);

echo "<h2>Pedidos del cliente: $customerNumber || con nombre: $nombreCliente</h2>";

foreach($pedidos as $pedido){

    $sql = "SELECT orderNumber, orderDate, status FROM orders WHERE orderNumber='$pedido'";
		$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
		$row=mysqli_fetch_assoc($resultado);
		$orderNumber=$row['orderNumber'];
        $orderDate=$row['orderDate'];
        $status=$row['status'];

    echo "<h3>Pedido: $pedido || orderNumber: $orderNumber orderDate: $orderDate status: $status</h3></br>";

    $productos=array();
    $select="SELECT productCode from orderdetails where orderNumber='$pedido' order by orderLineNumber";  
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    if ($resultado) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $productos[] = $row['productCode'];
        }
    }

    foreach($productos as $producto){

    $sql = "SELECT products.productName, orderdetails.orderLineNumber, orderdetails.productCode, orderdetails.quantityOrdered, orderdetails.priceEach FROM orderdetails, products WHERE orderdetails.productCode='$producto' and orderdetails.productCode=products.productCode and orderdetails.orderNumber=$pedido";
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    $orderLineNumber=$row['orderLineNumber'];
    $productCode=$row['productCode'];
    $quantityOrdered=$row['quantityOrdered'];
    $priceEach=$row['priceEach'];
    $productName=$row['productName'];

    

    echo "Producto: $producto || orderLineNumber: $orderLineNumber productName: $productName quantityOrdered: $quantityOrdered priceEach: $priceEach</br>";

    }


}

//funciones propias del programa

function selectNombre($db, $customerNumber){

    $sql = "SELECT customerName FROM customers WHERE customerNumber='$customerNumber'";
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    $nombreCliente=$row['customerName'];

    return $nombreCliente;
}

?>
</BR>
<input type="button" value="INICIO" onclick="window.location.href='pe_inicio.html'">
<?php

?>

</body>

</html>