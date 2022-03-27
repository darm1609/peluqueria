<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");

    function crear_modal_detalle($empleado, $ingresos, $pagos)
    {
        //Modal de detalles pagos a empleados
        echo "<div id='modal_detalle_empleado_".$empleado["empleado_cedula"]."' class='w3-modal'>";
        echo "<div class='w3-modal-content'>";
        echo "<span onclick=\"document.getElementById('modal_detalle_empleado_".$empleado["empleado_cedula"]."').style.display='none'\" class='w3-button w3-display-topright'>&times;</span>";
        echo "<br>";
        echo "<div style='padding: 1em;'>";
        echo "<p>".$empleado["nombre"]."</p>";
        foreach ($ingresos as $row)
        {
            echo $row["fecha"]." ".$row["fecha_num"]."<br>";
        }
        print_r($pagos);
        echo "</div>";
        echo "</div>";
        echo "</div>";
        //Fin de modal de detalles pagos a empleados
    }

    function mostrar_busqueda($bd)
    {
        //Ingresos netos del dia
        $sql = "select 
        i.fecha_num, 
        i.id_ingreso, 
        i.fecha, 
        mi.motivo, 
        i.efectivo, 
        i.transferencia, 
        i.debito, 
        i.deuda, 
        i.observacion, 
        case 
            when i.efectivo = 1 then ie.monto 
            else '' 
        end efectivo_monto, 
        case 
            when i.transferencia = 1 then it.monto 
            else '' 
        end transferencia_monto, 
        case 
            when i.transferencia = 1 then it.referencia 
            else '' 
        end transferencia_referencia, 
        case 
            when i.debito = 1 then id.monto 
            else '' 
        end debito_monto, 
        concat(e.nombre,' ',e.apellido) empleado, 
        concat(c.nombre,' ',c.apellido) cliente, 
        case 
            when i.id_ingreso_padre is not null then 1 
            else 0 
        end por_pago_de_deuda 
        from 
            ingreso i 
            inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso 
            inner join empleado e on i.empleado_cedula = e.empleado_cedula 
            left join cliente c on i.cliente_telf = c.telf 
            left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso 
            left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso 
            left join ingreso_debito id on id.id_ingreso = i.id_ingreso 
        where 
            i.fecha = '".$_POST["bfecha"]."' and (i.efectivo != 0 or i.debito != 0 or i.transferencia != 0 or i.deuda != 1) 
    union all 
        select 
            v.fecha_num, 
            v.id_venta as id_ingreso, 
            v.fecha, 
            v.motivo, 
            v.efectivo, 
            v.transferencia, 
            v.debito, 
            v.deuda, 
            '' as observacion, 
            case 
                when v.efectivo = 1 then ve.monto 
                else '' 
            end efectivo_monto, 
            case 
                when v.transferencia = 1 then vt.monto 
                else '' 
            end transferencia_monto, 
            case 
                when v.transferencia = 1 then vt.referencia 
                else '' 
            end transferencia_referencia, 
            case 
                when v.debito = 1 then vd.monto 
                else '' 
            end debito_monto, 
            'Venta' empleado, 
            concat(c.nombre,' ',c.apellido) cliente, 
            case 
                when v.id_venta_padre is not null then 1 
                else 0 
            end por_pago_de_deuda 
        from 
            venta v 
            left join cliente c on v.cliente_telf = c.telf 
            left join venta_efectivo ve on v.id_venta = ve.id_venta 
            left join venta_transferencia vt on v.id_venta = vt.id_venta 
            left join venta_debito vd on vd.id_venta = v.id_venta 
        where v.fecha = '".$_POST["bfecha"]."' and (v.efectivo != 0 or v.debito != 0 or v.transferencia != 0 or v.deuda != 1)
    union all
        select
            ap.fecha_num,
            ap.id_abono_peluqueria as id_ingreso, 
            ap.fecha,
            'Abono a pelqueria' as motivo,
            ap.efectivo,
            ap.transferencia,
            '' debito,
            '' deuda,
            '' as observacion,
            case
                when ap.efectivo = 1 then ape.monto
                else 0
            end efectivo_monto,
            case
                when ap.transferencia = 1 then apt.monto
                else 0
            end transferencia_monto,
            case 
                when ap.transferencia = 1 then apt.referencia 
                else '' 
            end transferencia_referencia, 
            0 debito_monto,
            '' empleado,
            '' cliente,
            '' por_pago_de_deuda
        from
            abono_peluqueria ap
            left join abono_peluqueria_efectivo ape on ap.id_abono_peluqueria = ape.id_abono_peluqueria
            left join abono_peluqueria_transferencia apt on ap.id_abono_peluqueria = apt.id_abono_peluqueria
        where ap.fecha = '".$_POST["bfecha"]."' and (ap.efectivo != 0 or ap.transferencia != 0)
        order by fecha_num asc;";
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
                                    <th align="center">Tipo</th>
                                    <th align="center">Empleado</th>
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
                                    $total_ingreso_linea = 0;
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

                                        $total_ingreso_linea += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_ingreso_linea += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $total_ingreso_linea += $row["debito_monto"] ? $row["debito_monto"] : 0;

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
                                        echo"<td>".$row["motivo"]."</td>";
                                        echo"<td>".$row["empleado"]."</td>";
                                        echo"<td align='right'>".$row["efectivo_monto"]."</td>";
                                        echo"<td align='right'>".$row["debito_monto"]."</td>";
                                        echo"<td align='right'>".$row["transferencia_monto"]."</td>";
                                        echo"<td align='left'>".$row["transferencia_referencia"]."</td>";
                                        echo"<td align='right'>".$total_ingreso_linea."</td>";
                                        echo"</tr>";
                                        $total_ingreso_linea = 0;
                                    }
                                    echo"<tr>";
                                    echo"<td>&nbsp;</td>";
                                    echo"<td>&nbsp;</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_ingreso_efectivo."</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_ingreso_datafono."</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_ingreso_transferencia."</td>";
                                    echo"<td>&nbsp;</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_ingreso_del_dia."</td>";
                                    echo"</tr>";
                                    unset($total_ingreso_del_dia, $total_ingreso_linea, $total_ingreso_efectivo, $total_ingreso_datafono, $total_ingreso_transferencia);
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
        
        //Egresos - Pagos - Vales
        $sql = "select
            e.fecha_num,
            e.id_egreso id,
            e.fecha,
            e.motivo,
            e.efectivo, 
            e.transferencia, 
            e.debito, 
            case 
                when e.efectivo = 1 then ee.monto 
                else 0 
            end efectivo_monto, 
            case 
                when e.transferencia = 1 then et.monto 
                else 0 
            end transferencia_monto, 
            case 
                when e.transferencia = 1 then et.referencia 
                else '' 
            end transferencia_referencia, 
            case 
                when e.debito = 1 then ed.monto 
                else 0 
            end debito_monto, 
            '' empleado
        from
            egreso e
            left join egreso_debito ed on e.id_egreso = ed.id_egreso
            left join egreso_efectivo ee on e.id_egreso = ee.id_egreso
            left join egreso_transferencia et on e.id_egreso = et.id_egreso
        where 
            e.fecha = '".$_POST["bfecha"]."'
        union all
        select
            vp.fecha_num,
            vp.id_vale_pago id,
            vp.fecha,
            vp.vale_pago motivo,
            vp.efectivo,
            vp.transferencia,
            0 debito,
            case 
                when vp.efectivo = 1 then vpe.monto 
                else 0 
            end efectivo_monto, 
            case 
                when vp.transferencia = 1 then vpt.monto 
                else 0 
            end transferencia_monto, 
            case 
                when vp.transferencia = 1 then vpt.referencia 
                else '' 
            end transferencia_referencia, 
            0 debito_monto, 
            concat(e.nombre,' ',e.apellido) empleado
        from
            vale_pago vp
            inner join empleado e on vp.empleado_cedula = e.empleado_cedula
            left join vale_pago_efectivo vpe on vp.id_vale_pago = vpe.id_vale_pago
            left join vale_pago_transferencia vpt on vp.id_vale_pago = vpt.id_vale_pago
        where 
            vp.fecha = '".$_POST["bfecha"]."'
        union all
        select 
            ae.fecha_num,
            ae.id_abono_empleado id,
            ae.fecha,
            'Abono a empleado' motivo,
            ae.efectivo,
            ae.transferencia,
            0 debito,
            case 
                when ae.efectivo = 1 then aee.monto 
                else 0 
            end efectivo_monto, 
            case 
                when ae.transferencia = 1 then aet.monto 
                else 0 
            end transferencia_monto, 
            case 
                when ae.transferencia = 1 then aet.referencia 
                else '' 
            end transferencia_referencia, 
            0 debito_monto, 
            concat(e.nombre,' ',e.apellido) empleado
        from
            abono_empleado ae
            inner join empleado e on ae.empleado_cedula = e.empleado_cedula
            left join abono_empleado_efectivo aee on ae.id_abono_empleado = aee.id_abono_empleado
            left join abono_empleado_transferencia aet on ae.id_abono_empleado = aet.id_abono_empleado
        where
            ae.fecha = '".$_POST["bfecha"]."'
        order by fecha_num asc;";
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
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Pagos / Vales / Egresos</div>
                    <div class="w3-row w3-section">
                        <table border='1' cellpadding='5' cellspacing='0' style='border-color: floralwhite;'>
                            <thead>
                                <tr>
                                    <th align="center">Tipo</th>
                                    <th align="center">Empleado</th>
                                    <th align="center">Efectivo</th>
                                    <th align="center">Dat&aacute;fono</th>
                                    <th align="center">Transferencia</th>
                                    <th align="center">Referencia</th>
                                    <th align="center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total_egreso_del_dia = 0;
                                    $total_egreso_linea = 0;
                                    $total_egreso_efectivo = 0;
                                    $total_egreso_datafono = 0;
                                    $total_egreso_transferencia = 0;
                                    foreach ($rows as $row)
                                    {
                                        $total_egreso_del_dia += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_egreso_del_dia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $total_egreso_del_dia += $row["debito_monto"] ? $row["debito_monto"] : 0;

                                        $total_egreso_linea += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_egreso_linea += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $total_egreso_linea += $row["debito_monto"] ? $row["debito_monto"] : 0;

                                        $total_egreso_efectivo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_egreso_datafono += $row["debito_monto"] ? $row["debito_monto"] : 0;
                                        $total_egreso_transferencia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;

                                        echo"<tr>";
                                        echo"<td>".$row["motivo"]."</td>";
                                        echo"<td>".$row["empleado"]."</td>";
                                        echo"<td align='right'>".$row["efectivo_monto"]."</td>";
                                        echo"<td align='right'>".$row["debito_monto"]."</td>";
                                        echo"<td align='right'>".$row["transferencia_monto"]."</td>";
                                        echo"<td align='left'>".$row["transferencia_referencia"]."</td>";
                                        echo"<td align='right'>".$total_egreso_linea."</td>";
                                        echo"</tr>";

                                        $total_egreso_linea = 0;
                                    }
                                    echo"<tr>";
                                    echo"<td>&nbsp;</td>";
                                    echo"<td>&nbsp;</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_egreso_efectivo."</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_egreso_datafono."</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_egreso_transferencia."</td>";
                                    echo"<td>&nbsp;</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_egreso_del_dia."</td>";
                                    echo"</tr>";
                                    unset($total_egreso_del_dia, $total_egreso_linea, $total_egreso_efectivo, $total_egreso_datafono, $total_egreso_transferencia);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php
            }
            else
            {
                ?>
                <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Pagos / Vales / Egresos: <i style='color:crimson;'>No hubo egresos</i></div>
                </form>
                <?php
            }
            unset($rows);
        }
        else
            unset($result);

        //Acumulados por empleado
        $sql = "select concat(e.nombre,' ',e.apellido) nombre, e.empleado_cedula from empleado e";
        $result_empleado = $bd->mysql->query($sql);
        unset($sql);
        $fecha_num_consulta = strtotime($_POST["bfecha"][6].$_POST["bfecha"][7].$_POST["bfecha"][8].$_POST["bfecha"][9]."-".$_POST["bfecha"][3].$_POST["bfecha"][4]."-".$_POST["bfecha"][0].$_POST["bfecha"][1]);
        if ($result_empleado)
        {
            if (!empty($result_empleado->num_rows))
            {
                $rows_empleado = $result_empleado->fetch_all(MYSQLI_ASSOC);
                $result_empleado->free();
                $total_ingreso_empleado = 0;
                $total_ingreso_peluqueria = 0;
                $total_ingreso_dueño = 0;
                ?>
                <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
                <div class="w3-row  w3-section" style='font-weight: bolder;'>Pagos a empleados</div>
                <div class="w3-row w3-section">
                <table border='1' cellpadding='5' cellspacing='0' style='border-color: floralwhite;'>
                <thead>
                <tr>
                <th align="center">Empleado</th>
                <th align="center">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $arreglo_ingresos = array();
                $arreglo_vales_pagos = array();
                foreach ($rows_empleado as $row_empleado)
                {
                    $sql = "select 
                    i.fecha_num, 
                    i.id_ingreso, 
                    i.fecha, 
                    mi.motivo, 
                    i.efectivo, 
                    i.transferencia, 
                    i.debito, 
                    i.deuda, 
                    i.observacion, 
                    case 
                        when i.efectivo = 1 then ie.monto 
                        else 0 
                    end efectivo_monto, 
                    case 
                        when i.transferencia = 1 then it.monto 
                        else 0 
                    end transferencia_monto, 
                    case 
                        when i.transferencia = 1 then it.referencia 
                        else '' 
                    end transferencia_referencia, 
                    case 
                        when i.debito = 1 then id.monto 
                        else 0 
                    end debito_monto,
                    e.empleado_cedula, 
                    concat(e.nombre,' ',e.apellido) empleado, 
                    concat(c.nombre,' ',c.apellido) cliente, 
                    case 
                        when i.id_ingreso_padre is not null then 1 
                        else 0 
                    end por_pago_de_deuda 
                    from 
                        ingreso i 
                        inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso 
                        inner join empleado e on i.empleado_cedula = e.empleado_cedula 
                        left join cliente c on i.cliente_telf = c.telf 
                        left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso 
                        left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso 
                        left join ingreso_debito id on id.id_ingreso = i.id_ingreso 
                    where 
                        i.empleado_cedula = '".$row_empleado["empleado_cedula"]."' and (i.efectivo != 0 or i.debito != 0 or i.transferencia != 0 or i.deuda != 1) and
                        i.fecha_num <= ".$fecha_num_consulta." order by i.fecha_num asc;";
                    $result_ingresos = $bd->mysql->query($sql);
                    unset($sql);
                    if ($result_ingresos)
                    {
                        if (!empty($result_ingresos->num_rows))
                        {
                            $rows_ingresos = $result_ingresos->fetch_all(MYSQLI_ASSOC);
                            $arreglo_ingresos = $rows_ingresos;
                            $result_ingresos->free();
                            $total_ingreso = 0;
                            $total_ingreso_linea = 0;
                            $total_ingreso_linea_empleado_porcentaje = 0;
                            $total_ingreso_linea_peluqueria_porcentaje = 0;
                            $total_ingreso_linea_dueño_porcentaje = 0;
                            foreach ($rows_ingresos as $row_ingreso)
                            {
                                $fecha_num_ingreso = strtotime($row_ingreso["fecha"][6].$row_ingreso["fecha"][7].$row_ingreso["fecha"][8].$row_ingreso["fecha"][9]."-".$row_ingreso["fecha"][3].$row_ingreso["fecha"][4]."-".$row_ingreso["fecha"][0].$row_ingreso["fecha"][1]);                                
                                $sql = "select
                                    pg.fecha,
                                    pg.porcentaje_empleado,
                                    pg.porcentaje_peluqueria,
                                    pg.porcentaje_dueño
                                from 
                                    porcentaje_ganancia pg 
                                where 
                                    pg.empleado_cedula = '".$row_empleado["empleado_cedula"]."' and
                                    pg.fecha_num <= $fecha_num_ingreso
                                order by pg.fecha_num desc limit 1;";
                                $result_porcentajes = $bd->mysql->query($sql);
                                unset($sql);
                                if ($result_porcentajes)
                                {
                                    if (!empty($result_porcentajes->num_rows))
                                    {
                                        $porcentaje_empleado = 0;
                                        $porcentaje_dueño = 0;
                                        $porcentaje_peluqueria = 0;
                                        
                                        $rows_porcentajes = $result_porcentajes->fetch_all(MYSQLI_ASSOC);
                                        $result_porcentajes->free();
                                        
                                        $porcentaje_empleado = $rows_porcentajes[0]["porcentaje_empleado"];
                                        $porcentaje_peluqueria = $rows_porcentajes[0]["porcentaje_peluqueria"];
                                        $porcentaje_dueño = $rows_porcentajes[0]["porcentaje_dueño"];

                                        if (!empty($porcentaje_empleado))
                                        {
                                            $total_ingreso_linea = 0;
                                            $total_ingreso_linea += $row_ingreso["efectivo_monto"];
                                            $total_ingreso_linea += $row_ingreso["transferencia_monto"];
                                            $total_ingreso_linea += $row_ingreso["debito_monto"];
                                            $total_ingreso += $total_ingreso_linea;
                                            $total_ingreso_linea_empleado_porcentaje = ($porcentaje_empleado * $total_ingreso_linea) / 100;
                                            $total_ingreso_linea_peluqueria_porcentaje = ($porcentaje_peluqueria * $total_ingreso_linea) / 100;
                                            $total_ingreso_linea_dueño_porcentaje = ($porcentaje_dueño * $total_ingreso_linea) / 100;

                                            $total_ingreso_empleado += $total_ingreso_linea_empleado_porcentaje;
                                            $total_ingreso_peluqueria += $total_ingreso_linea_peluqueria_porcentaje;
                                            $total_ingreso_dueño += $total_ingreso_linea_dueño_porcentaje;

                                            //echo "Fecha: ".$row_ingreso["fecha"]." Ingreso linea: ".$total_ingreso_linea." % empleado: ".$porcentaje_empleado." total empleado: ".$total_ingreso_linea_empleado_porcentaje." % peluqueria: ".$porcentaje_peluqueria ." total peluqueria: ".$total_ingreso_linea_peluqueria_porcentaje." % dueño: ".$porcentaje_dueño." total dueño: ".$total_ingreso_linea_dueño_porcentaje."<br>";
                                        }
                                    }
                                }
                                else
                                    unset($result_porcentajes);
                            }
                        }
                    }
                    else
                        unset($result_ingreso);

                    //echo $row_empleado["nombre"]." Total ingreso empleado: ".$total_ingreso_empleado." Total ingreso peluqueria: ".$total_ingreso_peluqueria." Total ingreso dueño: ".$total_ingreso_dueño."<br>";

                    $sql = "select
                        vp.fecha,
                        vp.vale_pago,
                        vp.efectivo,
                        vp.transferencia,
                        case
                            when vp.efectivo = 1 then vpe.monto
                            else 0
                        end efectivo_monto,
                        case
                            when vp.transferencia = 1 then vpt.monto
                            else 0
                        end transferencia_monto
                    from
                        vale_pago vp
                        left join vale_pago_efectivo vpe on vp.id_vale_pago = vpe.id_vale_pago
                        left join vale_pago_transferencia vpt on vp.id_vale_pago = vpt.id_vale_pago
                    where
                        vp.empleado_cedula = '".$row_empleado["empleado_cedula"]."' and vp.fecha_num <= '".$fecha_num_consulta."';";
                    $result_vale_pago = $bd->mysql->query($sql);
                    unset($sql);
                    $total_pagado_a_empleado = 0;
                    if ($result_vale_pago)
                    {
                        if (!empty($result_vale_pago->num_rows))
                        {
                            $rows_vale_pago = $result_vale_pago->fetch_all(MYSQLI_ASSOC);
                            $result_vale_pago->free();
                            $arreglo_vales_pagos = $rows_vale_pago;
                            
                            foreach ($rows_vale_pago as $row_vale_pago)
                            {
                                $total_pagado_a_empleado += $row_vale_pago["efectivo_monto"];
                                $total_pagado_a_empleado += $row_vale_pago["transferencia_monto"];
                            }
                            unset($rows_vale_pago);
                        }
                    }
                    else
                        unset($result_vale_pago);

                    crear_modal_detalle($row_empleado, $arreglo_ingresos, $arreglo_vales_pagos);
                    
                    $total_ingreso_empleado -= $total_pagado_a_empleado;
                    //echo "<br><br>".$row_empleado["nombre"]." Total ingreso empleado: ".$total_ingreso_empleado." Total ingreso peluqueria: ".$total_ingreso_peluqueria." Total ingreso dueño: ".$total_ingreso_dueño."<br>";
                    echo "<tr>";
                    echo "<td><span style='text-decoration: underline; cursor:pointer;' onclick=\"document.getElementById('modal_detalle_empleado_".$row_empleado["empleado_cedula"]."').style.display='block'\">".$row_empleado["nombre"]."</span></td>";
                    echo "<td align='right'>".$total_ingreso_empleado."</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
                </table>
                </div>
                </form>

                <?php
                
                
                unset($rows_empleado);
            }
        }
        else
            unset($result_empleado);
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        mostrar_busqueda($bd);
    }
?>