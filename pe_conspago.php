<html>
<head>
</head>
<body>
<h1>PAGOS ENTRE DOS FECHAS</h1>
<?php
session_start();
include "conexion.php";


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

    $fechaDesde=strtotime($_REQUEST["fechaDesde"]);
    $fechaDesde=date("Y-m-d", $fechaDesde);

    $fechaHasta=strtotime($_REQUEST["fechaHasta"]);
    $fechaHasta=date("Y-m-d", $fechaHasta);

    $customerNumber=$_SESSION['admin'];

    echo "<h3>Para el cliente $customerNumber</h3>";
    listar($db, $customerNumber, $fechaDesde, $fechaHasta);
}


//Funciones propias del programan

function listar($db, $customerNumber, $fechaDesde, $fechaHasta){

    $pagos = array();

    if($fechaDesde=="1970-01-01" && $fechaHasta=="1970-01-01"){//Si no pones nada en las fechas da esas por defecto

        //Si no se especifica fecha mostrar el historico
        $sql="SELECT checkNumber FROM payments WHERE customerNumber=$customerNumber ORDER BY paymentDate DESC";
        $resultado=mysqli_query($db, $sql);
        if ($resultado) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $pagos[] = $row['checkNumber'];
            }
        }
        if(count($pagos)==0){
            echo "El cliente no ha realizado ningun pago";
        }
        else{
            
            $total=0;
            foreach ($pagos as $pago){
                $sql="SELECT * FROM payments WHERE checkNumber='$pago'";
                $resultado=mysqli_query($db, $sql);
                $row=mysqli_fetch_assoc($resultado);
                $customerNumber=$row['customerNumber'];
                $checkNumber=$row['checkNumber'];
                $paymentDate=$row['paymentDate'];
                $amount=$row['amount'];
                $total=$total+$amount;
                ?><pre><?php
                echo "checkNumber: $checkNumber, paymentDate: $paymentDate, amount: $amount</br>";
                ?></pre><?php
            }
            echo "Total: $total</br>";
        }

    }
    else{//si se han especificado fechas
        
        //mostrar los pagos entre fechas
        $sql="SELECT checkNumber FROM payments WHERE customerNumber=$customerNumber AND DATE_FORMAT(paymentDate,'%Y-%m-%d')>='$fechaDesde' AND DATE_FORMAT(paymentDate,'%Y-%m-%d')<='$fechaHasta'";
        $resultado=mysqli_query($db, $sql);
        if ($resultado) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $pagos[] = $row['checkNumber'];
            }
        }
        if(count($pagos)==0){
            echo "El cliente no ha realizado ningun pago entre estas fechas";
        }
        else{
           
            $total=0;
            foreach ($pagos as $pago){
                $sql="SELECT * FROM payments WHERE checkNumber='$pago' ORDER BY paymentDate";
                $resultado=mysqli_query($db, $sql);
                $row=mysqli_fetch_assoc($resultado);
                $customerNumber=$row['customerNumber'];
                $checkNumber=$row['checkNumber'];
                $paymentDate=$row['paymentDate'];
                $amount=$row['amount'];
                $total=$total+$amount;
                ?><pre><?php
                echo "checkNumber: $checkNumber, paymentDate: $paymentDate, amount: $amount</br>";
                ?></pre><?php
            }
            echo "Total: $total</br>";
        }
    }

}

?>
</BR>
    <input type="button" value="INICIO" onclick="window.location.href='pe_inicio.html'">
    
</body>

</html>