<script type="text/javascript">
	
</script>
<?php
	session_start();
	require("head.php");
	require("config.php");
	require("funciones_generales.php");
	require("librerias/basedatos.php");

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		print_r($_POST);
	}
?>