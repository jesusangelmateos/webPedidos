<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
</head>

<body>
<h1>ENTRAR
	
</h1>
<?php
session_start();
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) {

    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';

?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos</div>
<div class="card-body">
		<div class="form-group">
        NOMBRE DE USUARIO <input type="text" name="nombre" placeholder="usuario" class="form-control">
        </div>
		<div class="form-group">
        CLAVE <input type="text" name="clave" placeholder="clave" class="form-control">
		</div></br>
		<input type="submit" value="ENTRAR">	
	</BR>
<?php

} else { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
	
	$nombre=limpiar_campo($_REQUEST['nombre']);
	if($nombre==""){
		trigger_error('El nombre no puede estar vacio');	
	}
	$apellido=limpiar_campo($_REQUEST['clave']);
	if($apellido==""){
		trigger_error('La clave no puede estar vacia');	
	}

	$id=select_id($db, $nombre, $apellido);
	$_SESSION['admin']=$id;

	entrar($db, $id);
	
}
?>

<?php
// Funciones utilizadas en el programa

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

function entrar($db, $id){

    $select="SELECT id from admin where id='$id'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$idExiste=$row['id'];

	if($idExiste){
		
		$_SESSION['id']=$id;
		$_SESSION["carrito"] = array();
		//crear cookie
		$cookie_name = "usuario";
		$cookie_value =$id;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 segundos = 1 día

		header("location: pe_inicio.html");
	}
	else{
		
		trigger_error('El usuario o contraseña no son válidos');
	}
   
}

function select_id($db, $nombre, $apellido){

	$select="SELECT id from admin where username='$nombre' and passcode='$apellido'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$id=$row['id'];

	return $id;
}
	

?>



</body>

</html>