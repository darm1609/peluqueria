<?php
    require("head.php");
    require("config.php");
    require("librerias/basedatos.php");
	require("funciones_generales.php");

    function guardar_cita_nueva($bd)
    {
        global $basedatos;
        $fechaIniUnix = $_POST["bfecha_unix"];
        $horaInicio = $fechaIniUnix + ($_POST["add_cita_hora"] * 60 * 60) + ($_POST["add_cita_minuto"] * 60);
        $horaFin = $horaInicio + $_POST["add_cita_duracion"];
        $estado = 1; //asignada;
        if ($bd->insertar_datos(7,$basedatos,"citas","desde	","hasta","empleado_telf","id_cliente","id_motivo_ingreso","estado","nota",$horaInicio,$horaFin,$_POST["add_cita_empleado_telf"],$_POST["add_cita_id_cliente"],$_POST["add_cita_id_motivo_ingreso"],$estado,$_POST["add_cita_nota"]))
            return true;
        return false;
    }

    function validar_cita_nueva($bd)
    {
        $fechaIniUnix = $_POST["bfecha_unix"];
        $horaInicio = $fechaIniUnix + ($_POST["add_cita_hora"] * 60 * 60) + ($_POST["add_cita_minuto"] * 60);
        $horaFin = $horaInicio + $_POST["add_cita_duracion"];
        $estado_asignada = 1; //asignada;
        $sql = "select * from citas where empleado_telf = '".$_POST["add_cita_empleado_telf"]."' and 
        estado = ".$estado_asignada." and 
        ((desde > ".$horaInicio." and desde = ".($horaFin - 1).") or
        (desde > ".$horaInicio." and desde < ".$horaFin." and hasta > ".$horaFin.") or
        (desde = ".$horaInicio." and desde < ".$horaFin." and hasta > ".$horaFin.") or
        (desde = ".$horaInicio." and desde = ".$horaFin.") or
        (desde < ".$horaInicio." and hasta > ".$horaFin.") or
        (desde < ".$horaInicio." and hasta = ".$horaFin.") or
        (desde < ".$horaInicio." and hasta > ".$horaInicio." and desde < ".$horaFin.") or
        (hasta = ".($horaInicio + 1)." and hasta < ".$horaFin."));";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if($result)
        {
            if (!empty($result->num_rows))
            {
                ?>
                <script>
                    alertify.alert("","HORARIO DE LA CITA OCUPADO").set('label', 'Aceptar');
                </script>
                <?php
                return false;
            }
        }
        return true;
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        if (validar_cita_nueva($bd))
        {
            if (guardar_cita_nueva($bd))
            {
                ?>
                <script>
                    alertify.alert("","CITA CREADA").set('label', 'Aceptar');
                    $("#closeAddCitaModal").click();
                    $("#enviar").click();
                </script>
                <?php
            }
        }
    }
?>