<?php
    require("head.php");
    require("config.php");
    require("librerias/basedatos.php");
	require("funciones_generales.php");

    function guardar_cita_nueva($bd)
    {
        
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        guardar_cita_nueva($bd);
        ?>
        <script>
            $("#closeAddCitaModal").click();
            $("#enviar").click();
        </script>
        <?php
    }
?>