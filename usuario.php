<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");
	if(isset($_SESSION["login"]))
	{
		require("superior.php");
		$u=true;
		require_once("menu_lateral.php");
		require_once("contenido_head.php");
		echo"<br>";
		require("usuario_contenido.php");
		unset($u);
	}
	require("pie.php");
?>