<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");

    function mostrar_busqueda($bd)
    {
        //Ingresos netos del dia
        $sql = "select i.fecha_num, i.id_ingreso, i.fecha, mi.motivo, i.efectivo, i.transferencia, i.debito, i.deuda, i.observacion, case when i.efectivo = 1 then ie.monto else '' end efectivo_monto, case when i.transferencia = 1 then it.monto else '' end transferencia_monto, case when i.transferencia = 1 then it.referencia else '' end transferencia_referencia, case when i.debito = 1 then id.monto else '' end debito_monto, concat(e.nombre,' ',e.apellido) empleado, concat(c.nombre,' ',c.apellido) cliente, case when i.id_ingreso_padre is not null then 1 else 0 end por_pago_de_deuda from ingreso i inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso inner join empleado e on i.empleado_cedula = e.empleado_cedula left join cliente c on i.cliente_telf = c.telf left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso left join ingreso_debito id on id.id_ingreso = i.id_ingreso where i.fecha = '".$_POST["bfecha"]."' and (i.efectivo != 0 or i.debito != 0 or i.transferencia != 0 or i.deuda != 1) union all select v.fecha_num, v.id_venta as id_ingreso, v.fecha, v.motivo, v.efectivo, v.transferencia, v.debito, v.deuda, '' as observacion, case when v.efectivo = 1 then ve.monto else '' end efectivo_monto, case when v.transferencia = 1 then vt.monto else '' end transferencia_monto, case when v.transferencia = 1 then vt.referencia else '' end transferencia_referencia, case when v.debito = 1 then vd.monto else '' end debito_monto, 'Venta' empleado, concat(c.nombre,' ',c.apellido) cliente, case when v.id_venta_padre is not null then 1 else 0 end por_pago_de_deuda from venta v left join cliente c on v.cliente_telf = c.telf left join venta_efectivo ve on v.id_venta = ve.id_venta left join venta_transferencia vt on v.id_venta = vt.id_venta left join venta_debito vd on vd.id_venta = v.id_venta where v.fecha = '".$_POST["bfecha"]."' and (v.efectivo != 0 or v.debito != 0 or v.transferencia != 0 or v.deuda != 1) order by fecha_num asc;";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            $n = $result->num_rows;
            if (!empty($n))
            {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
                ?>
                <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Ingresos netos del d&iacute;a</div>
                    <div class="w3-row w3-section">    
                        <table border='1' cellpadding='5' cellspacing='0' style='border-color: floralwhite;'>
                            <thead>
                                <tr>
                                    <th align="center">Empleado</th>
                                    <th align="center">Tipo De Trabajo / Venta</th>
                                    <th align="center">Efectivo</th>
                                    <th align="center">Dat&aacute;fono</th>
                                    <th align="center">Transferencia</th>
                                    <th align="center">Referencia</th>
                                    <th align="center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total_ingreso_del_dia = 0;
                                    $total_ingreso_trabajo = 0;
                                    $total_ingreso_efectivo = 0;
                                    $total_ingreso_datafono = 0;
                                    $total_ingreso_transferencia = 0;
                                    $por_pago_de_deuda = 0;
                                    $por_pago_de_deuda_encontrado = 0;
                                    foreach ($rows as $row)
                                    {
                                        $total_ingreso_del_dia += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_ingreso_del_dia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $total_ingreso_del_dia += $row["debito_monto"] ? $row["debito_monto"] : 0;
                                        $total_ingreso_trabajo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_ingreso_trabajo += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $total_ingreso_trabajo += $row["debito_monto"] ? $row["debito_monto"] : 0;
                                        $total_ingreso_efectivo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_ingreso_datafono += $row["debito_monto"] ? $row["debito_monto"] : 0;
                                        $total_ingreso_transferencia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $por_pago_de_deuda = $row["por_pago_de_deuda"];
                                        if ($por_pago_de_deuda_encontrado == 0 and $por_pago_de_deuda == 1) $por_pago_de_deuda_encontrado = 1;
                                        echo"<tr style='";
                                        if ($por_pago_de_deuda == 1) 
                                        {
                                            echo"background-color: #C8A2C8";
                                        }
                                        echo"'>";
                                        echo"<td>".$row["empleado"]."</td>";
                                        echo"<td>".$row["motivo"]."</td>";
                                        echo"<td align='right'>".$row["efectivo_monto"]."</td>";
                                        echo"<td align='right'>".$row["debito_monto"]."</td>";
                                        echo"<td align='right'>".$row["transferencia_monto"]."</td>";
                                        echo"<td align='left'>".$row["transferencia_referencia"]."</td>";
                                        echo"<td align='right'>".$total_ingreso_trabajo."</td>";
                                        echo"</tr>";
                                        $total_ingreso_trabajo = 0;
                                    }
                                    echo "<tr>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td align='right' style='font-weight: bolder;'>".$total_ingreso_efectivo."</td>";
                                    echo "<td align='right' style='font-weight: bolder;'>".$total_ingreso_datafono."</td>";
                                    echo "<td align='right' style='font-weight: bolder;'>".$total_ingreso_transferencia."</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td align='right' style='font-weight: bolder;'>".$total_ingreso_del_dia."</td>";
                                    echo "</tr>";
                                    unset($total_ingreso_del_dia,$total_ingreso_trabajo,$total_ingreso_efectivo,$total_ingreso_datafono,$total_ingreso_transferencia);
                                ?>
                            </tbody>
                        </table>
                        <?php
                            if ($por_pago_de_deuda_encontrado == 1)
                            {
                                echo"<table border=0><tr><td style='background-color: #C8A2C8' width='25em'></td><td>Pago por deuda</td></tr></table>";
                            }
                        ?>
                    </div>
                </form>
                <?php
            }
            else 
            {
                ?>
                <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Ingresos netos: <i style='color:crimson;'>No hubo ingresos</i></div>
                </form>
                <?php
            }
            unset($rows);
        }
        else
            unset($result);
        
        //Ingresos x Egresos - Ventas - Pagos - Vales
        $sql = "";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            $n = $result->num_rows;
            if (!empty($n))
            {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            }
            else
            {
                ?>
                <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Ingresos x Pagos / Vales / Egresos: <i style='color:crimson;'>No hubo ingresos</i></div>
                </form>
                <?php
            }
            unset($rows);
        }
        else
            unset($result);
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        mostrar_busqueda($bd);
    }
?>