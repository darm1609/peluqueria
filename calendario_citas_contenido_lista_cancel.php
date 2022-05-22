<?php
    require("head.php");
    require("config.php");
    require("librerias/basedatos.php");
	require("funciones_generales.php");

    function cancelar_cita($basedatos, $bd)
    {
        $id_cita = "";
        foreach ($_POST as $i => $v)
        {
            if (strstr($i, "cancel_cita_id_cita_"))
            {
                $id_cita = $v;
            }
        }
        if (strlen($id_cita))
        {
            $estado_cancelado = 2;
            if ($bd->actualizar_datos(1,1,$basedatos,"citas","id_citas",$id_cita,"estado",0,$estado_cancelado))
                return true;
        }
        return false;
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        if (cancelar_cita($basedatos,$bd))
        {
            ?>
            <script>
                alertify.alert("","CITA CANCELADA").set('label', 'Aceptar');
                $(".closeDetalleCitaModal").click();
                $("#enviar").click();
            </script>
            <?php
        }
    }
?>