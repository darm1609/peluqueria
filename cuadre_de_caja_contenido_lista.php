<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");

    function mostrar_busqueda($bd)
    {
        
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        mostrar_busqueda($bd);
    }
?>