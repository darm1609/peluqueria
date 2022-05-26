<?php
    require("../config.php");
	require("../librerias/basedatos.php");
    require("../funciones_generales.php");

    function validar_hora_de_envio($inicio)
    {
        $actual = time();
        if (($actual + 3600) >= $inicio)
            return true;
        return false;
    }

    function verificar_citas($mensajeRecordatorio, $altiriaSMS, $bd)
    {
        $actual = date("d-m-Y",time());
        $actual_unix = strtotime($actual[6].$actual[7].$actual[8].$actual[9]."-".$actual[3].$actual[4]."-".$actual[0].$actual[1]);
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
                c.recordatorio_automatico = 0 and c.estado = 1 and c.desde >= ".$actual_unix.";";
            $result = $bd->mysql->query($sql);
            unset($sql);
            if ($result)
            {
                if (!empty($result->num_rows))
                {
                    $array = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                    foreach ($array as $row)
                    {
                        if (validar_hora_de_envio($row["desde"]))
                        {
                            $empleado = $row["empleado"];
                            $cliente_telf = "57".$row["cliente_telf"];
                            $diaInicio = date("d-m-Y",$row["desde"]);
                            $horaInicio = date("h:i a",$row["desde"]);
                            $tipo = $row["tipo"];
                            $mensajeSeparado = explode(" ", $mensajeRecordatorio);
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

                            $sql = "update citas set recordatorio_automatico=1 where id_citas='".$row["id_citas"]."';";
                            $bd->mysql->query($sql);
                        }
                    }
                }
            }
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos, $mensajeRecordatorioUnaHora;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        verificar_citas($mensajeRecordatorio, $altiriaSMS, $bd);
    }
?>