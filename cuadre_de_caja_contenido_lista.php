<script type="text/javascript">

    function mostrar_detalle_empleado(fecha, empleado_telf)
    {
        $("#detalle_fecha_"+fecha+"_"+empleado_telf).toggle("slow", function() {
            if($("#detalle_fecha_"+fecha+"_"+empleado_telf).is(":visible")) {
                $("#icon_detalle_fecha_"+fecha+"_"+empleado_telf).addClass("icon-chevron-up");
                $("#icon_detalle_fecha_"+fecha+"_"+empleado_telf).removeClass("icon-chevron-down");
            }
            else {
                $("#icon_detalle_fecha_"+fecha+"_"+empleado_telf).removeClass("icon-chevron-up");
                $("#icon_detalle_fecha_"+fecha+"_"+empleado_telf).addClass("icon-chevron-down");
            }
        });
    }

</script>
<style>
    .table-overflow {
        overflow-x: scroll;
        overflow-x: auto;
    }
</style>
<?php
	session_start();
	require("head.php");
	require("config.php");
    require("funciones_generales.php");
	require("librerias/basedatos.php");

    function existe_fecha_en_arreglo($resultado, $fecha)
    {
        foreach ($resultado as $i => $v)
        {
            if ($v["fecha"] == $fecha)
                return true;
        }
        return false;
    }

    function crear_modal_detalle($empleado, $ingresos, $pagos)
    {
        //Modal de detalles pagos a empleados
        echo "<div id='modal_detalle_empleado_".$empleado["empleado_telf"]."' class='w3-modal'>";
        echo "<div class='w3-modal-content'>";
        echo "<span onclick=\"document.getElementById('modal_detalle_empleado_".$empleado["empleado_telf"]."').style.display='none'\" class='w3-button w3-display-topright'>&times;</span>";
        echo "<span class='w3-display-topleft' style='padding: 1em;'><b>".$empleado["nombre"]."</b></span><br>";
        echo "<br>";
        echo "<div style='padding: 1em;'>";
        // print_r($ingresos);
        // echo "<br><br>";
        // print_r($pagos);
        // echo "<br><br>";
        $resultado_fecha = array();
        $resultado = array();
        $i = 0;

        foreach ($ingresos as $row)
        {
            if (!existe_fecha_en_arreglo($resultado_fecha, $row["fecha"]) and $row["empleado_telf"] == $empleado["empleado_telf"])
            {
                $resultado_fecha[$i]["fecha_num"] = $row["fecha_num"];
                $resultado_fecha[$i]["fecha"] = $row["fecha"];
                $resultado_fecha[$i]["empleado_telf"] = $row["empleado_telf"];
                $i++;
            }
        }

        foreach ($ingresos as $row)
        {
            $resultado[$i]["fecha_num"] = $row["fecha_num"];
            $resultado[$i]["fecha"] = $row["fecha"];
            $resultado[$i]["tipo"] = "ingreso";
            $resultado[$i]["motivo"] = $row["motivo"];
            $resultado[$i]["efectivo_monto"] = !empty($row["efectivo_monto"]) ? $row["efectivo_monto"] : 0;
            $resultado[$i]["debito_monto"] = !empty($row["debito_monto"]) ? $row["debito_monto"] : 0;
            $resultado[$i]["deuda_monto"] = !empty($row["deuda_monto"]) ? $row["deuda_monto"] : 0;
            $resultado[$i]["transferencia_monto"] = !empty($row["transferencia_monto"]) ? $row["transferencia_monto"] : 0;
            $resultado[$i]["transferencia_referencia"] = !empty($row["transferencia_referencia"]) ? $row["transferencia_referencia"] : 0;
            $resultado[$i]["empleado_telf"] = $row["empleado_telf"];
            $resultado[$i]["porcentaje_empleado"] = $row["porcentaje_empleado"];
            $resultado[$i]["porcentaje_peluqueria"] = $row["porcentaje_peluqueria"];
            $resultado[$i]["porcentaje_dueño"] = $row["porcentaje_dueño"];
            $i++;
        }

        foreach ($pagos as $row)
        {
            if (!existe_fecha_en_arreglo($resultado_fecha, $row["fecha"]) and $row["empleado_telf"] == $empleado["empleado_telf"])
            {
                $resultado_fecha[$i]["fecha_num"] = $row["fecha_num"];
                $resultado_fecha[$i]["fecha"] = $row["fecha"];
                $resultado_fecha[$i]["empleado_telf"] = $row["empleado_telf"];
                $i++;
            }
        }

        foreach ($pagos as $row)
        {
            $resultado[$i]["fecha_num"] = $row["fecha_num"];
            $resultado[$i]["fecha"] = $row["fecha"];
            $resultado[$i]["tipo"] = "pago";
            $resultado[$i]["motivo"] = $row["vale_pago"];
            $resultado[$i]["efectivo_monto"] = !empty($row["efectivo_monto"]) ? $row["efectivo_monto"] : 0;
            $resultado[$i]["debito_monto"] = 0;
            $resultado[$i]["transferencia_monto"] = !empty($row["transferencia_monto"]) ? $row["transferencia_monto"] : 0;
            $resultado[$i]["transferencia_referencia"] = !empty($row["transferencia_referencia"]) ? $row["transferencia_referencia"] : 0;
            $resultado[$i]["empleado_telf"] = $row["empleado_telf"];
            $i++;
        }

        array_multisort($resultado_fecha, SORT_DESC, SORT_REGULAR);

        foreach ($resultado_fecha as $row)
        {
            echo "<span style='cursor:pointer;' onclick='mostrar_detalle_empleado(\"".$row["fecha"]."\",\"".$empleado["empleado_telf"]."\");'>".$row["fecha"]."&nbsp;<i id='icon_detalle_fecha_".$row["fecha"]."_".$empleado["empleado_telf"]."' class='icon-chevron-down'></i></span><br><br>";
            echo "<div id='detalle_fecha_".$row["fecha"]."_".$empleado["empleado_telf"]."' style='display: none;border: 1px solid #cccccc;margin-top: -1.5em;padding: 1em;'>";
            echo "<table border='1' cellpadding='5' cellspacing='0' style='border-color: floralwhite;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th align='center'>Motivo</th>";
            echo "<th align='center'>Efectivo</th>";
            echo "<th align='center'>Debito</th>";
            echo "<th align='center'>Transferencia</th>";
            echo "<th align='center'>Deuda</th>";
            echo "<th align='center'>%</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $total_por_dia = 0;
            $total_por_dia_ingreso = 0;
            $total_por_dia_pago = 0;
            foreach ($resultado as $row2)
            {
                $total_por_linea_con_porcentaje = 0;
                if ($row2["fecha"] == $row["fecha"] and $row2["empleado_telf"] == $empleado["empleado_telf"])
                {
                    echo "<tr>";
                    echo "<td>".$row2["motivo"]."</td>";
                    if ($row2["tipo"] == "ingreso") {
                        echo "<td align='right'>".$row2["efectivo_monto"]."</td>";
                        echo "<td align='right'>".$row2["debito_monto"]."</td>";
                        echo "<td align='right'>".$row2["transferencia_monto"]."</td>";
                        echo "<td align='right'>".$row2["deuda_monto"]."</td>";
                        $total_por_linea_con_porcentaje += ($row2["efectivo_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_linea_con_porcentaje += ($row2["debito_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_linea_con_porcentaje += ($row2["transferencia_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_linea_con_porcentaje += ($row2["deuda_monto"] * $row2["porcentaje_empleado"] / 100);
                        echo "<td align='right'>".$total_por_linea_con_porcentaje."</td>";
                        $total_por_dia += ($row2["efectivo_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_dia += ($row2["debito_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_dia += ($row2["transferencia_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_dia += ($row2["deuda_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_dia_ingreso += ($row2["efectivo_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_dia_ingreso += ($row2["debito_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_dia_ingreso += ($row2["transferencia_monto"] * $row2["porcentaje_empleado"] / 100);
                        $total_por_dia_ingreso += ($row2["deuda_monto"] * $row2["porcentaje_empleado"] / 100);
                    }
                    else {
                        echo "<td align='right'>".$row2["efectivo_monto"]."</td>";
                        echo "<td align='right'>".$row2["debito_monto"]."</td>";
                        echo "<td align='right'>".$row2["transferencia_monto"]."</td>";
                        echo "<td align='right'>0</td>";
                        $total_por_linea_con_porcentaje += $row2["efectivo_monto"];
                        $total_por_linea_con_porcentaje += $row2["debito_monto"];
                        $total_por_linea_con_porcentaje += $row2["debito_monto"];
                        echo "<td align='right'>".$total_por_linea_con_porcentaje."</td>";
                        $total_por_dia -= $row2["efectivo_monto"];
                        $total_por_dia -= $row2["debito_monto"];
                        $total_por_dia -= $row2["transferencia_monto"];
                        $total_por_dia_pago += $row2["efectivo_monto"];
                        $total_por_dia_pago += $row2["debito_monto"];
                        $total_por_dia_pago += $row2["transferencia_monto"];
                    }
                }
            }
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Totales:</b></td>";
            echo "<td colspan='5' align='center'><b>Ingreso: ".$total_por_dia_ingreso."&nbsp;&nbsp;Pago:".$total_por_dia_pago."</b></td>";
            echo "</tr>";
            $total_por_dia = 0;
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        //Fin de modal de detalles pagos a empleados
    }

    function acumulado_por_empleado($bd)
    {
        //Acumulados por empleado
        $sql = "select concat(e.nombre,' ',e.apellido) nombre, e.empleado_telf from empleado e";
        $result_empleado = $bd->mysql->query($sql);
        unset($sql);
        $fecha_num_consulta = strtotime($_POST["bfecha"][6].$_POST["bfecha"][7].$_POST["bfecha"][8].$_POST["bfecha"][9]."-".$_POST["bfecha"][3].$_POST["bfecha"][4]."-".$_POST["bfecha"][0].$_POST["bfecha"][1]);
        if ($result_empleado)
        {
            if (!empty($result_empleado->num_rows))
            {
                $rows_empleado = $result_empleado->fetch_all(MYSQLI_ASSOC);
                $result_empleado->free();
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
                    $total_ingreso_empleado = 0;
                    $total_ingreso_peluqueria = 0;
                    $total_ingreso_dueño = 0;
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
                    case 
                        when i.deuda = 1 then idd.monto 
                        else 0 
                    end deuda_monto,
                    e.empleado_telf, 
                    concat(e.nombre,' ',e.apellido) empleado, 
                    concat(c.nombre,' ',c.apellido) cliente, 
                    case 
                        when i.id_ingreso_padre is not null then 1 
                        else 0 
                    end por_pago_de_deuda 
                    from 
                        ingreso i 
                        inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso 
                        inner join empleado e on i.empleado_telf = e.empleado_telf 
                        left join cliente c on i.cliente_telf = c.telf 
                        left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso 
                        left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso 
                        left join ingreso_debito id on i.id_ingreso = id.id_ingreso
                        left join ingreso_deuda idd on i.id_ingreso = idd.id_ingreso 
                    where 
                        i.empleado_telf = '".$row_empleado["empleado_telf"]."' and 
                        i.fecha_num <= ".$fecha_num_consulta." order by i.fecha_num desc;";
                    $result_ingresos = $bd->mysql->query($sql);
                    unset($sql);
                    if ($result_ingresos)
                    {
                        if (!empty($result_ingresos->num_rows))
                        {
                            $rows_ingresos = $result_ingresos->fetch_all(MYSQLI_ASSOC);
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
                                    pg.empleado_telf = '".$row_empleado["empleado_telf"]."' and
                                    pg.fecha_num <= $fecha_num_ingreso
                                order by pg.fecha_num, pg.id_porcentaje_ganancia desc limit 1;";
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

                                        $row_ingreso["porcentaje_empleado"] = $porcentaje_empleado;
                                        $row_ingreso["porcentaje_peluqueria"] = $porcentaje_peluqueria;
                                        $row_ingreso["porcentaje_dueño"] = $porcentaje_dueño;
                                        
                                        if ($row_ingreso["por_pago_de_deuda"] == 0)
                                            array_push($arreglo_ingresos, $row_ingreso);

                                        if (!empty($porcentaje_empleado) and $row_ingreso["por_pago_de_deuda"] == 0)
                                        {
                                            $total_ingreso_linea = 0;
                                            $total_ingreso_linea += $row_ingreso["efectivo_monto"];
                                            $total_ingreso_linea += $row_ingreso["transferencia_monto"];
                                            $total_ingreso_linea += $row_ingreso["debito_monto"];
                                            $total_ingreso_linea += $row_ingreso["deuda_monto"];
                                            $total_ingreso += $total_ingreso_linea;
                                            $total_ingreso_linea_empleado_porcentaje = ($porcentaje_empleado * $total_ingreso_linea) / 100;
                                            $total_ingreso_linea_peluqueria_porcentaje = ($porcentaje_peluqueria * $total_ingreso_linea) / 100;
                                            $total_ingreso_linea_dueño_porcentaje = ($porcentaje_dueño * $total_ingreso_linea) / 100;

                                            $total_ingreso_empleado += $total_ingreso_linea_empleado_porcentaje;
                                            $total_ingreso_peluqueria += $total_ingreso_linea_peluqueria_porcentaje;
                                            $total_ingreso_dueño += $total_ingreso_linea_dueño_porcentaje;
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

                    $sql = "select
                        vp.empleado_telf,
                        vp.fecha_num,
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
                        vp.empleado_telf = '".$row_empleado["empleado_telf"]."' and vp.fecha_num <= '".$fecha_num_consulta."';";
                    $result_vale_pago = $bd->mysql->query($sql);
                    unset($sql);
                    $total_pagado_a_empleado = 0;
                    if ($result_vale_pago)
                    {
                        if (!empty($result_vale_pago->num_rows))
                        {
                            $rows_vale_pago = $result_vale_pago->fetch_all(MYSQLI_ASSOC);
                            $result_vale_pago->free();
                            foreach ($rows_vale_pago as $row_vale_pago)
                            {
                                array_push($arreglo_vales_pagos, $row_vale_pago);
                                $total_pagado_a_empleado += $row_vale_pago["efectivo_monto"];
                                $total_pagado_a_empleado += $row_vale_pago["transferencia_monto"];
                            }
                            unset($rows_vale_pago);
                        }
                    }
                    else
                        unset($result_vale_pago);
                    
                    $total_ingreso_empleado -= $total_pagado_a_empleado;

                    echo "<tr>";
                    echo "<td><span style='text-decoration: underline; cursor:pointer;' onclick=\"document.getElementById('modal_detalle_empleado_".$row_empleado["empleado_telf"]."').style.display='block'\">".$row_empleado["nombre"]."</span></td>";
                    echo "<td align='right'>".$total_ingreso_empleado."</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
                </table>
                </div>
                </form>

                <?php

                foreach ($rows_empleado as $row_empleado)
                {
                    crear_modal_detalle($row_empleado, $arreglo_ingresos, $arreglo_vales_pagos);
                }

                unset($rows_empleado);
            }
        }
        else
            unset($result_empleado);
    }

    function egresos_pagos_vales($bd)
    {
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
            inner join empleado e on vp.empleado_telf = e.empleado_telf
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
            inner join empleado e on ae.empleado_telf = e.empleado_telf
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
    }

    function ingresos_del_dia($bd)
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
            inner join empleado e on i.empleado_telf = e.empleado_telf 
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
    }

    function deudas_del_dia($bd)
    {
        //Deudas del dia
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
        case 
            when i.deuda = 1 then idd.monto 
            else '' 
        end deuda_monto,
        i.observacion, 
        concat(e.nombre,' ',e.apellido) empleado, 
        concat(c.nombre,' ',c.apellido) cliente, 
        case 
            when i.id_ingreso_padre is not null then 1 
            else 0 
        end por_pago_de_deuda 
        from 
            ingreso i 
            inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso 
            inner join empleado e on i.empleado_telf = e.empleado_telf 
            left join cliente c on i.cliente_telf = c.telf 
            left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso 
            left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso 
            left join ingreso_debito id on i.id_ingreso = id.id_ingreso
            left join ingreso_deuda idd on i.id_ingreso = idd.id_ingreso
        where 
            i.fecha = '".$_POST["bfecha"]."' and i.deuda = 1";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            if (!empty($result->num_rows))
            {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
                ?>
                <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Deudas del d&iacute;a</div>
                    <div class="w3-row w3-section">
                        <table border='1' cellpadding='5' cellspacing='0' style='border-color: floralwhite;'>
                            <thead>
                                <tr>
                                    <th align="center">Tipo</th>
                                    <th align="center">Empleado</th>
                                    <th align="center">Deuda</th>
                                    <th align="center">Cliente</th>
                                    <th align="center">Comentario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total_ingreso_del_dia = 0;
                                    foreach ($rows as $row)
                                    {
                                        $total_ingreso_del_dia += $row["deuda_monto"] ? $row["deuda_monto"] : 0;
                                        echo "<tr>";
                                        echo "<td>".$row["motivo"]."</td>";
                                        echo "<td>".$row["empleado"]."</td>";
                                        echo "<td>".$row["deuda_monto"]."</td>";
                                        echo "<td>".$row["cliente"]."</td>";
                                        echo "<td>".$row["observacion"]."</td>";
                                        echo "</tr>";
                                    }
                                    echo "<tr>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo"<td align='right' style='font-weight: bolder;'>".$total_ingreso_del_dia."</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo "</tr>";
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php
            }
        }
    }

    function ventas_de_productos_del_dia($bd)
    {
        //Ingresos netos del dia
        $sql = "select 
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
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Ingresos por ventas del d&iacute;a</div>
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
        }
        else
            unset($result);
    }

    function resumen_del_dia($bd)
    {
        $total_ingreso_efectivo = 0;
        $total_ingreso_datafono = 0;
        $total_ingreso_transferencia = 0;
        $total_egreso_efectivo= 0;
        $total_egreso_datafono = 0;
        $total_egreso_transferencia = 0;

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
            inner join empleado e on i.empleado_telf = e.empleado_telf 
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
                $total_ingreso_del_dia = 0;
                $total_ingreso_linea = 0;
                $total_ingreso_efectivo = 0;
                $total_ingreso_datafono = 0;
                $total_ingreso_transferencia = 0;
                $por_pago_de_deuda = 0;
                $por_pago_de_deuda_encontrado = 0;
                foreach ($rows as $row)
                {
                    $total_ingreso_efectivo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                    $total_ingreso_datafono += $row["debito_monto"] ? $row["debito_monto"] : 0;
                    $total_ingreso_transferencia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                }
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
            inner join empleado e on vp.empleado_telf = e.empleado_telf
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
            inner join empleado e on ae.empleado_telf = e.empleado_telf
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
                $total_egreso_del_dia = 0;
                $total_egreso_linea = 0;
                $total_egreso_efectivo = 0;
                $total_egreso_datafono = 0;
                $total_egreso_transferencia = 0;
                foreach ($rows as $row)
                {
                    $total_egreso_efectivo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                    $total_egreso_datafono += $row["debito_monto"] ? $row["debito_monto"] : 0;
                    $total_egreso_transferencia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                }           
            }
            unset($rows);
        }
        else
            unset($result);

        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
            <div class="w3-row  w3-section" style='font-weight: bolder;'>Resumen del d&iacute;a</div>
            <div class="w3-row w3-section">
                <b>Efectivo:</b> <?php echo $total_ingreso_efectivo - $total_egreso_efectivo; ?><br>
                <b>Dat&aacute;fono:</b> <?php echo $total_ingreso_datafono - $total_egreso_datafono; ?><br>
                <b>Transferencia:</b> <?php echo $total_ingreso_transferencia - $total_egreso_transferencia; ?><br><br>
                <?php
                    $total = ($total_ingreso_efectivo - $total_egreso_efectivo) + ($total_ingreso_datafono - $total_egreso_datafono) + ($total_ingreso_transferencia - $total_egreso_transferencia);
                ?>
                <b>Total: <?php echo $total; ?></b> 
            </div>
        </form>
        <?php
    }

    function acumulado_peluqueria_dueño($bd)
    {
        //Acumulados por empleado
        $sql = "select e.empleado_telf, concat(e.nombre,' ',e.apellido) nombre, e.dueño from empleado e;";
        $result_empleado = $bd->mysql->query($sql);
        unset($sql);
        $fecha_num_consulta = strtotime($_POST["bfecha"][6].$_POST["bfecha"][7].$_POST["bfecha"][8].$_POST["bfecha"][9]."-".$_POST["bfecha"][3].$_POST["bfecha"][4]."-".$_POST["bfecha"][0].$_POST["bfecha"][1]);
        if ($result_empleado)
        {
            if (!empty($result_empleado->num_rows))
            {
                $rows_empleado = $result_empleado->fetch_all(MYSQLI_ASSOC);
                $result_empleado->free();
                $arreglo_ingresos = array();
                $arreglo_vales_pagos = array();
                $total_ingreso_empleado = 0;
                $total_ingreso_peluqueria = 0;
                $total_ingreso_dueño = 0;
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
                    case 
                        when i.deuda = 1 then idd.monto 
                        else 0 
                    end deuda_monto,
                    e.empleado_telf, 
                    concat(e.nombre,' ',e.apellido) empleado, 
                    concat(c.nombre,' ',c.apellido) cliente, 
                    case 
                        when i.id_ingreso_padre is not null then 1 
                        else 0 
                    end por_pago_de_deuda 
                    from 
                        ingreso i 
                        inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso 
                        inner join empleado e on i.empleado_telf = e.empleado_telf 
                        left join cliente c on i.cliente_telf = c.telf 
                        left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso 
                        left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso 
                        left join ingreso_debito id on i.id_ingreso = id.id_ingreso
                        left join ingreso_deuda idd on i.id_ingreso = idd.id_ingreso 
                    where 
                        i.empleado_telf = '".$row_empleado["empleado_telf"]."' and 
                        i.fecha_num <= ".$fecha_num_consulta." order by i.fecha_num desc;";
                    $result_ingresos = $bd->mysql->query($sql);
                    unset($sql);
                    if ($result_ingresos)
                    {
                        if (!empty($result_ingresos->num_rows))
                        {
                            $rows_ingresos = $result_ingresos->fetch_all(MYSQLI_ASSOC);
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
                                    pg.empleado_telf = '".$row_empleado["empleado_telf"]."' and
                                    pg.fecha_num <= $fecha_num_ingreso
                                order by pg.fecha_num, pg.id_porcentaje_ganancia desc limit 1;";
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

                                        $row_ingreso["porcentaje_empleado"] = $porcentaje_empleado;
                                        $row_ingreso["porcentaje_peluqueria"] = $porcentaje_peluqueria;
                                        $row_ingreso["porcentaje_dueño"] = $porcentaje_dueño;

                                        if ($row_ingreso["por_pago_de_deuda"] == 0)
                                            array_push($arreglo_ingresos, $row_ingreso);

                                        if (!empty($porcentaje_empleado) and $row_ingreso["por_pago_de_deuda"] == 0)
                                        {
                                            $total_ingreso_linea = 0;
                                            $total_ingreso_linea += $row_ingreso["efectivo_monto"];
                                            $total_ingreso_linea += $row_ingreso["transferencia_monto"];
                                            $total_ingreso_linea += $row_ingreso["debito_monto"];
                                            $total_ingreso_linea += $row_ingreso["deuda_monto"];
                                            $total_ingreso += $total_ingreso_linea;
                                            $total_ingreso_linea_empleado_porcentaje = ($porcentaje_empleado * $total_ingreso_linea) / 100;
                                            $total_ingreso_linea_peluqueria_porcentaje = ($porcentaje_peluqueria * $total_ingreso_linea) / 100;
                                            $total_ingreso_linea_dueño_porcentaje = ($porcentaje_dueño * $total_ingreso_linea) / 100;
                                            
                                            if ($row_empleado["dueño"] == 1)
                                                $total_ingreso_empleado += $total_ingreso_linea_empleado_porcentaje;
                                            $total_ingreso_peluqueria += $total_ingreso_linea_peluqueria_porcentaje;
                                            $total_ingreso_dueño += $total_ingreso_linea_dueño_porcentaje;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Vale-pagos
                    $sql = "select
                        vp.empleado_telf,
                        vp.fecha_num,
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
                        vp.empleado_telf = '".$row_empleado["empleado_telf"]."' and vp.fecha_num <= '".$fecha_num_consulta."';";
                    $result_vale_pago = $bd->mysql->query($sql);
                    unset($sql);
                    $total_pagado_a_empleado = 0;
                    if ($result_vale_pago and $row_empleado["dueño"] == 1)
                    {
                        if (!empty($result_vale_pago->num_rows))
                        {
                            $rows_vale_pago = $result_vale_pago->fetch_all(MYSQLI_ASSOC);
                            $result_vale_pago->free();
                            foreach ($rows_vale_pago as $row_vale_pago)
                            {
                                array_push($arreglo_vales_pagos, $row_vale_pago);
                                $total_pagado_a_empleado += $row_vale_pago["efectivo_monto"];
                                $total_pagado_a_empleado += $row_vale_pago["transferencia_monto"];
                            }
                            unset($rows_vale_pago);
                        }
                    }
                }
            }
        }
        else
            unset($result_empleado);

        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
        <div class="w3-row  w3-section" style='font-weight: bolder;'>Acumulados dueño y peluqueria</div>
        <div class="w3-row w3-section">
            <?php 
                echo "<b>Por trabajos realizados:&nbsp;</b>";
                echo $total_ingreso_empleado;
                echo "<br>";
                echo "<b>Por empleados:&nbsp;</b>";
                echo $total_ingreso_dueño;
                echo "<br>";
                echo "<b>Total pagado:&nbsp;</b>";
                echo $total_pagado_a_empleado;
                echo "<br><br>";
                echo "<b>Total dueño:&nbsp;";
                echo ($total_ingreso_empleado + $total_ingreso_dueño) - $total_pagado_a_empleado;
                echo "</b>";
                echo "<br><br>";
            ?>
        </div>
        </form>
        <?php
    }

    function consultar_ingresos_totales($bd, &$array_ingresos)
    {
        //Ingresos acumulados
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
            inner join empleado e on i.empleado_telf = e.empleado_telf 
            left join cliente c on i.cliente_telf = c.telf 
            left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso 
            left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso 
            left join ingreso_debito id on id.id_ingreso = i.id_ingreso 
        where 
            (i.efectivo != 0 or i.debito != 0 or i.transferencia != 0 or i.deuda != 1) 
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
        where 
            (v.efectivo != 0 or v.debito != 0 or v.transferencia != 0 or v.deuda != 1)
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
        where 
            (ap.efectivo != 0 or ap.transferencia != 0)
        order by fecha_num asc;";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            if (!empty($result->num_rows))
            {
                $array_ingresos = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            }
        }
        else
            unset($result);
    }

    function consultar_egresos_totales($bd, &$array_egresos)
    {
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
            inner join empleado e on vp.empleado_telf = e.empleado_telf
            left join vale_pago_efectivo vpe on vp.id_vale_pago = vpe.id_vale_pago
            left join vale_pago_transferencia vpt on vp.id_vale_pago = vpt.id_vale_pago
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
            inner join empleado e on ae.empleado_telf = e.empleado_telf
            left join abono_empleado_efectivo aee on ae.id_abono_empleado = aee.id_abono_empleado
            left join abono_empleado_transferencia aet on ae.id_abono_empleado = aet.id_abono_empleado
        order by fecha_num asc;";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            if (!empty($result->num_rows))
            {
                $array_egresos = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            }
        }
        else
            unset($result);
    }

    function consultar_porcentaje_empleados_totales($bd, &$array_porcentajes)
    {
        $sql = "select
            e.empleado_telf,
            concat(e.nombre,' ',e.apellido) nombre_empleado,
            pg.id_porcentaje_ganancia,
            pg.fecha_num,
            pg.fecha,
            pg.porcentaje_empleado,
            pg.porcentaje_peluqueria,
            pg.porcentaje_dueño
        from 
            porcentaje_ganancia pg
            inner join empleado e on pg.empleado_telf = e.empleado_telf
        order by pg.fecha_num;";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            if (!empty($result->num_rows))
            {
                $array_porcentajes = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            }
        }
        else
            unset($result);
    }

    function mostrar_ingresos_netos_del_dia($array_ingresos)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin table-overflow" method="post">
            <div class="w3-row  w3-section" style='font-weight: bolder;'>Ingresos netos del d&iacute;a</div>
            <div class="w3-row w3-section">
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                    <thead>
                        <tr class="w3-dulcevanidad">
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

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    function mostrar_busqueda($bd, &$array_ingresos, &$array_egresos, &$array_porcentajes)
    {
        $admin = usuario_admin();
        $cajero = usuario_cajero();
        $consulta = usuario_consulta();

        consultar_ingresos_totales($bd, $array_ingresos);

        consultar_egresos_totales($bd, $array_egresos);

        consultar_porcentaje_empleados_totales($bd, $array_porcentajes);

        $fecha_num_consulta = strtotime($_POST["bfecha"][6].$_POST["bfecha"][7].$_POST["bfecha"][8].$_POST["bfecha"][9]."-".$_POST["bfecha"][3].$_POST["bfecha"][4]."-".$_POST["bfecha"][0].$_POST["bfecha"][1]);
        $fecha = $_POST["bfecha"];

        mostrar_ingresos_netos_del_dia($array_ingresos);
        

        // if ($admin) {
        //     acumulado_peluqueria_dueño($bd);
        // }

        // resumen_del_dia($bd);

        // ingresos_del_dia($bd);

        // ventas_de_productos_del_dia($bd);

        // deudas_del_dia($bd);

        // egresos_pagos_vales($bd);

        // acumulado_por_empleado($bd);
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        $array_ingresos = array();
        $array_egresos = array();
        $array_porcentajes = array();
        mostrar_busqueda($bd, $array_ingresos, $array_egresos, $array_porcentajes);
    }
?>