<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");
	if(isset($_SESSION["login"]))
	{
		require("superior.php");
		$c=true;
		require_once("menu_lateral.php");
		require_once("contenido_head.php");
		echo"<br>";
		require("cliente_contenido.php");
		unset($c);
	}
	else
	{
		?>
		<script>
			window.location.replace('.');
		</script>
		<?php
	}
	require("pie.php");
?>