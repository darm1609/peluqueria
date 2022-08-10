<?php
    require("head.php");
    require("config.php");
    require("librerias/basedatos.php");
	require("funciones_generales.php");

    function enviar_sms($altiriaSMS, $id_cita, $bd)
    {
        if (isset($_POST["add_cita_sms"]))
        {
            $sql = "select c.*,
            concat(e.nombre,' ',e.apellido) empleado,
            e.empleado_telf empleado_telf,
            concat(cc.nombre,' ',cc.apellido) cliente,
            cc.telf cliente_telf,
            m.motivo tipo
            from
                citas c
                inner join empleado e on c.empleado_telf = e.empleado_telf
                inner join cliente cc on c.id_cliente = cc.id_cliente
                left join motivo_ingreso m on c.id_motivo_ingreso = m.id_motivo_ingreso
            where 
                c.id_citas = ".$id_cita.";";
            $result = $bd->mysql->query($sql);
            unset($sql);
            if ($result)
            {
                if (!empty($result->num_rows))
                {
                    $array = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                    $empleado = $array[0]["empleado"];
                    $cliente_telf = "57".$array[0]["cliente_telf"];
                    $mensaje = $_POST["sms_crecion"];

                    $diaInicio = date("d-m-Y",$array[0]["desde"]);
                    $horaInicio = date("h:i a",$array[0]["desde"]);

                    $tipo = $array[0]["tipo"];

                    $mensajeSeparado = explode(" ", $mensaje);

                    foreach ($mensajeSeparado as $i => $v)
                    {
                        if ($v == "[empleado]") $mensajeSeparado[$i] = $empleado;
                        if ($v == "[dia]") $mensajeSeparado[$i] = $diaInicio;
                        if ($v == "[hora]") $mensajeSeparado[$i] = $horaInicio;
                        if (!empty($tipo)) 
                        {
                            if ($v == "[tipo]") 
                                $mensajeSeparado[$i] = "para ".$tipo;
                        }
                        else
                            $mensajeSeparado[$i] = " ";
                    }

                    $mensaje = implode(" ", $mensajeSeparado);

                    $response = $altiriaSMS->sendSMS($cliente_telf, $mensaje);

                    if ($response)
                        return true;
                    return false;
                }
            }
            else
                unset($result);
        }
    }

    function guardar_cita_nueva($bd)
    {
        global $basedatos;
        $fechaIniUnix = $_POST["bfecha_unix"];
        $horaInicio = $fechaIniUnix + ($_POST["add_cita_hora"] * 60 * 60) + ($_POST["add_cita_minuto"] * 60);
        $horaFin = $horaInicio + $_POST["add_cita_duracion"];
        $estado = 1; //asignada;
        if ($bd->insertar_datos(7,$basedatos,"citas","desde	","hasta","empleado_telf","id_cliente","id_motivo_ingreso","estado","nota",$horaInicio,$horaFin,$_POST["add_cita_empleado_telf"],$_POST["add_cita_id_cliente"],$_POST["add_cita_id_motivo_ingreso"],$estado,$_POST["add_cita_nota"]))
            return $bd->ultimo_result;
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
        (desde > ".$horaInicio." and hasta < ".$horaFin.") or
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
            $id_cita = guardar_cita_nueva($bd);
            if ($id_cita)
            {
                enviar_sms($altiriaSMS, $id_cita, $bd);
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