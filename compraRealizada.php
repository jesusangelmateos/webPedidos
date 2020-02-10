<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>RESUMEN COMPRA</h1>
<?php
session_start();
include "conexion.php";

$fechaSolicitud=date("Y-m-d H:m:s");

actualizar($db, $fechaSolicitud);

//vaciar la cesta
$_SESION["carrito"]=array();

?>
</BR>
<input type="button" value="INICIO" onclick="window.location.href='pe_inicio.html'">
<?php
//funciones propias del programa

function actualizar($db, $fechaSolicitud){

    $id = $_SESSION['admin'];
    $fechaPedido = $_SESSION['fechaPedido'];

    $select="SELECT max(orderNumber) as maximo from orders";  
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    $orderNumber=$row['maximo']+1;

    $sql="INSERT INTO orders (orderNumber, orderDate, requiredDate, status, customerNumber) VALUES ('$orderNumber','$fechaPedido','$fechaSolicitud', 'improcess', '$id')";
    if(mysqli_query($db, $sql)){
        echo 'INSERTADO EN TABLA orders CORRECTAMENTE</br>';
    }
    else{
        echo "Error: ".$sql."<br>".mysqli_error($db)."<br>";
    }


    //hacer checknumber
    $sql="SELECT max(checkNumber) as checkNumber FROM payments";
    $resultado=mysqli_query($db, $sql); //el $resultado no es valido y hay que tratarlo
    $row=mysqli_fetch_assoc($resultado);
    $checkNumber=$row['checkNumber'];
    $letas=substr($checkNumber, 0, 2);
    $numeros=substr($checkNumber, -6);
    $numeros=$numeros+1;
    $checkNumber=$letas.$numeros;

    $id=$_SESSION['admin'];
    $amount=$_SESSION['totalCompra'];

    //insertar en payments
    $sql = "INSERT INTO payments (customerNumber, checkNumber, paymentDate, amount) VALUES ('$id', '$checkNumber', '$fechaSolicitud', '$amount')";
    if(mysqli_query($db, $sql)){
        echo 'INSERTADO EN TABLA payments CORRECTAMENTE</br>';
    }
    else{
        echo "Error: ".$sql."<br>".mysqli_error($db)."<br>";
    }
    
    $contLineas=1;
    foreach ($_SESSION["carrito"] as $productCode => $unidades){

        $select="SELECT buyPrice from products where productCode='$productCode'";  
        $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
        $row=mysqli_fetch_assoc($resultado);
        $princeEach=$row['buyPrice'];


        $sql="INSERT INTO orderdetails (orderNumber, productCode, quantityOrdered, priceEach, orderLineNumber) VALUES ('$orderNumber','$productCode','$unidades','$princeEach', '$contLineas')";
        if(mysqli_query($db, $sql)){
            echo 'INSERTADO EN TABLA ordersdetails CORRECTAMENTE</br>';
        }
        else{
            echo "Error: ".$sql."<br>".mysqli_error($db)."<br>";
        }
        $contLineas+=1;
       
        
        $sql="UPDATE products SET quantityInStock=quantityInStock-$unidades WHERE productCode='$productCode'";
        if(mysqli_query($db, $sql)){
            echo 'SE HA ACTUALIZADO LA TABLA products</br>';
        }
        else{
            echo "Error: ".$sql."<br>".mysqli_error($db)."<br>";
        }
           unset($_SESSION["carrito"][$productCode]);
    }
    

}

?>

</body>

</html>