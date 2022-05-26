<?php
    require("head.php");
    require("config.php");
    require("librerias/basedatos.php");
	require("funciones_generales.php");

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        $id_cita = "";
        
        foreach ($_POST as $i => $v)
        {
            if (strstr($i, "sms_cita_id_cita_"))
            {
                $id_cita = $v;
            }
        }

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
                $mensaje = $_POST["sms_recordatorio_".$id_cita];

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
                {
                    ?>
                    <script>
                        alertify.alert("","MENSAJE ENVIADO").set('label', 'Aceptar');
                        $(".closeDetalleCitaModal").click();
                    </script>
                    <?php
                }
                else
                {
                    ?>
                    <script>
                        alertify.alert("","ERROR AL ENVIAR MENSAJE CONSULTE CON EL DESARROLLADOR").set('label', 'Aceptar');
                    </script>
                    <?php
                }
            }
        }
        else
            unset($result);
    }
?>