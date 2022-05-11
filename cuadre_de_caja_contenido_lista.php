<script type="text/javascript">

    function show_acumulado()
    {
        $("#ingreso-egreso-no-acumulado").hide();
        $("#ingreso-egreso-acumulado").show();
        $("#ingreso-no-acumulado").hide();
        $("#ingreso-acumulado").show();
        $("#egreso-no-acumulado").hide();
        $("#egreso-acumulado").show();
    }

    function hide_acumulado()
    {
        $("#ingreso-egreso-no-acumulado").show();
        $("#ingreso-egreso-acumulado").hide();
        $("#ingreso-no-acumulado").show();
        $("#ingreso-acumulado").hide();
        $("#egreso-no-acumulado").show();
        $("#egreso-acumulado").hide();
    }

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

    function mostrar_ocultar_div(divId)
    {
        $("#" + divId + "-contenido-1").hide();
        $("#" + divId + "-contenido-2").hide();
        $("#" + divId).toggle("fast", function() {
            if($("#" + divId).is(":visible")) { 
                $("#" + divId + "-icon").addClass("icon-chevron-up");
                $("#" + divId + "-icon").removeClass("icon-chevron-down");
                $("#" + divId + "-contenido-1").show(1000);
                $("#" + divId + "-contenido-2").show(1000);
            }
            else {
                $("#" + divId + "-icon").removeClass("icon-chevron-up");
                $("#" + divId + "-icon").addClass("icon-chevron-down");
            }
        });
    }

    function consultar_fecha_empleado(empleado)
    {
        let fecha = $("#bfecha_" + empleado).val();
        if (fecha.length)
        {
            let dateFecha = new Date(Date.parse(fecha) + 5 * 60 * 60 * 1000);
            let dia  = dateFecha.getDate();
            let mes  = dateFecha.getMonth() + 1;
            if (Number(dia) < 10)
                dia = "0" + dia.toString();
            if (Number(mes) < 10)
                mes = "0" + mes.toString();
            let anio  = dateFecha.getFullYear();
            let fechaFormato = dia.toString() + "-" + mes + "-" + anio.toString();
            let divFecha = $("#empleado_" + empleado + "_fecha_" + fechaFormato);
            if (divFecha.length)
            {
                $(".fecha-detalle-empleado").hide();
                divFecha.show();
            }
            else {
                $(".fecha-detalle-empleado").hide();
                alertify.alert("","EL EMPLEADO NO TIENE TRABAJOS EN ESTA FECHA").set('label', 'Aceptar');
            }
                
        }
        else {
            $(".fecha-detalle-empleado").hide();
            alertify.alert("","DEBE COLOCAR UNA FECHA").set('label', 'Aceptar');
        }
    }

</script>
<style>
    .table-overflow {
        overflow-x: scroll;
        overflow-x: auto;
    }

    .table-celda-texto {
        text-align: left !important;
        border-right: 1px solid #acacac;
    }

    .table-celda-texto-ultima {
        text-align: left !important;
    }
    .table-celda-numerica {
        text-align: right !important;
        border-right: 1px solid #acacac;
    }

    .table-celda-numerica-ultima {
        text-align: right !important;
    }
</style>
<?php
	session_start();
	require("head.php");
	require("config.php");
    require("funciones_generales.php");
	require("librerias/basedatos.php");

    function existe_rango_fecha_en_arreglo($array, $fecha_num_desde, $fecha_num_hasta)
    {
        foreach ($array as $i => $v)
        {
            if ($v["fecha_num"] >= $fecha_num_desde and $v["fecha_num"] <= $fecha_num_hasta)
                return true;
        }
        return false;
    }

    function existe_fecha_en_arreglo($resultado, $fecha)
    {
        foreach ($resultado as $i => $v)
        {
            if ($v["fecha"] == $fecha)
                return true;
        }
        return false;
    }

    function existe_rango_fecha_en_arreglo_con_deuda($array, $fecha_num_desde, $fecha_num_hasta)
    {
        foreach ($array as $i => $v)
        {
            if ($v["fecha_num"] >= $fecha_num_desde and $v["fecha_num"] <= $fecha_num_hasta and $v["deuda"] == 1)
                return true;
        }
        return false;
    }

    function existe_fecha_en_arreglo_con_deuda($array, $fecha)
    {
        foreach ($array as $i => $v)
        {
            if ($v["fecha"] == $fecha and $v["deuda"] == 1)
                return true;
        }
        return false;
    }

    function existe_rango_venta_en_el_arreglo($array, $fecha_num_desde, $fecha_num_hasta)
    {
        foreach ($array as $i => $v)
        {
            if ($v["tipo_ingreso"] == "venta" and $v["fecha_num"] >= $fecha_num_desde and $v["fecha_num"] <= $fecha_num_hasta)
                return true;
        }
        return false;
    }

    function existe_venta_en_el_arreglo($array, $fecha) 
    {
        foreach ($array as $i => $v)
        {
            if ($v["tipo_ingreso"] == "venta" and $v["fecha"] == $fecha)
                return true;
        }
        return false;
    }

    function porcentaje_peluqueria($array_porcentajes, $array_porcentajes_motivo, $fecha_num, $empleado, $id_motivo)
    {
        $id_aux = 0;
        $fecha_num_aux = 0;
        $porcentaje_peluqueria = 0;
        foreach ($array_porcentajes_motivo as $row)
        {
            if ($row["empleado_telf"] == $empleado and $row["id_motivo_ingreso"] == $id_motivo) 
            {
                if ($row["fecha_num"] >= $fecha_num_aux and $row["id_motivo_porcentaje_ganancia"] >= $id_aux and $row["fecha_num"] <= $fecha_num)
                {
                    $id_aux = $row["id_motivo_porcentaje_ganancia"];
                    $fecha_num_aux = $row["fecha_num"];
                    $porcentaje_peluqueria = $row["porcentaje_peluqueria"];
                }
            }
        }
        if ($porcentaje_peluqueria == 0)
        {
            $id_aux = 0;
            $fecha_num_aux = 0;
            $porcentaje_peluqueria = 0;
            foreach ($array_porcentajes as $row)
            {
                if ($row["empleado_telf"] == $empleado) 
                {
                    if ($row["fecha_num"] >= $fecha_num_aux and $row["id_porcentaje_ganancia"] >= $id_aux and $row["fecha_num"] <= $fecha_num)
                    {
                        $id_aux = $row["id_porcentaje_ganancia"];
                        $fecha_num_aux = $row["fecha_num"];
                        $porcentaje_peluqueria = $row["porcentaje_peluqueria"];
                    }
                }
            }
        }
        return $porcentaje_peluqueria;
    }

    function porcentaje_dueño_por_empleado($array_porcentajes, $array_porcentajes_motivo, $fecha_num, $empleado, $id_motivo)
    {
        $id_aux = 0;
        $fecha_num_aux = 0;
        $porcentaje_dueño = 0;
        foreach ($array_porcentajes_motivo as $row)
        {
            if ($row["empleado_telf"] == $empleado and $row["id_motivo_ingreso"] == $id_motivo) 
            {
                if ($row["fecha_num"] >= $fecha_num_aux and $row["id_motivo_porcentaje_ganancia"] >= $id_aux and $row["fecha_num"] <= $fecha_num)
                {
                    $id_aux = $row["id_motivo_porcentaje_ganancia"];
                    $fecha_num_aux = $row["fecha_num"];
                    $porcentaje_dueño = $row["porcentaje_dueño"];
                }
            }
        }
        if ($porcentaje_dueño == 0)
        {
            $id_aux = 0;
            $fecha_num_aux = 0;
            $porcentaje_dueño = 0;
            foreach ($array_porcentajes as $row)
            {
                if ($row["empleado_telf"] == $empleado) 
                {
                    if ($row["fecha_num"] >= $fecha_num_aux and $row["id_porcentaje_ganancia"] >= $id_aux and $row["fecha_num"] <= $fecha_num)
                    {
                        $id_aux = $row["id_porcentaje_ganancia"];
                        $fecha_num_aux = $row["fecha_num"];
                        $porcentaje_dueño = $row["porcentaje_dueño"];
                    }
                }
            }
        }
        return $porcentaje_dueño;
    }

    function porcentaje_empleado($array_porcentajes, $array_porcentajes_motivo, $fecha_num, $empleado, $id_motivo)
    {
        $id_aux = 0;
        $fecha_num_aux = 0;
        $porcentaje_empleado = 0;
        foreach ($array_porcentajes_motivo as $row)
        {
            if ($row["empleado_telf"] == $empleado and $row["id_motivo_ingreso"] == $id_motivo) 
            {
                if ($row["fecha_num"] >= $fecha_num_aux and $row["id_motivo_porcentaje_ganancia"] >= $id_aux and $row["fecha_num"] <= $fecha_num)
                {
                    $id_aux = $row["id_motivo_porcentaje_ganancia"];
                    $fecha_num_aux = $row["fecha_num"];
                    $porcentaje_empleado = $row["porcentaje_empleado"];
                }
            }
        }
        if ($porcentaje_empleado == 0)
        {
            $id_aux = 0;
            $fecha_num_aux = 0;
            $porcentaje_empleado = 0;
            foreach ($array_porcentajes as $row)
            {
                if ($row["empleado_telf"] == $empleado) 
                {
                    if ($row["fecha_num"] >= $fecha_num_aux and $row["id_porcentaje_ganancia"] >= $id_aux and $row["fecha_num"] <= $fecha_num)
                    {
                        $id_aux = $row["id_porcentaje_ganancia"];
                        $fecha_num_aux = $row["fecha_num"];
                        $porcentaje_empleado = $row["porcentaje_empleado"];
                    }
                }
            }
        }
        return $porcentaje_empleado;
    }

    function total_empleado($empleado, $array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha, $fecha_num_consulta, $dueño)
    {
        $total = 0;
        foreach ($array_ingresos as $row)
        {
            if ($row["tipo_ingreso"] == "trabajo" and $row["fecha_num"] <= $fecha_num_consulta and $row["por_pago_de_deuda"] == 0)
            {
                $porcentaje_empleado = porcentaje_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $empleado, $row["id_motivo_ingreso"]);
                $porcentaje_dueño = porcentaje_dueño_por_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $empleado, $row["id_motivo_ingreso"]);
                if ($dueño)
                {
                    $porcentaje_dueño = porcentaje_dueño_por_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $total += (($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_dueño) / 100;
                    $total += (($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_dueño) / 100;
                    $total += (($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_dueño) / 100;
                    if ($row["cliente_especial"] != "1" and $empleado != $row["empleado_telf"]) 
                    {
                        $total += (($row["deuda_monto"] ? $row["deuda_monto"] : 0) * $porcentaje_dueño) / 100;
                    }
                }
                
                if ($empleado == $row["empleado_telf"])
                {
                    $total += (($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_empleado) / 100;
                    $total += (($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_empleado) / 100;
                    $total += (($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_empleado) / 100;
                    $total += (($row["deuda_monto"] ? $row["deuda_monto"] : 0) * $porcentaje_empleado) / 100;
                }
            }
        }

        foreach ($array_egresos as $row)
        {
            if ($row["tipo_egreso"] == "pago_empleado" and $row["fecha_num"] <= $fecha_num_consulta)
            {
                if ($empleado == $row["empleado_telf"])
                {
                    $total -= ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0);
                    $total -= ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
                }
            }
        }

        return $total;
    }

    function total_dueño($empleado, $array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha, $fecha_num_consulta)
    {
        $total = 0;
        foreach ($array_ingresos as $row)
        {
            if ($row["tipo_ingreso"] == "trabajo" and $row["fecha_num"] <= $fecha_num_consulta and $row["por_pago_de_deuda"] == 0)
            {
                if ($empleado != $row["empleado_telf"])
                {
                    $porcentaje_dueño_por_empleado = porcentaje_dueño_por_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $total += (($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_dueño_por_empleado) / 100;
                    $total += (($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_dueño_por_empleado) / 100;
                    $total += (($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_dueño_por_empleado) / 100;
                    if ($row["cliente_especial"] != "1" and $empleado != $row["empleado_telf"]) 
                    {
                        $total += (($row["deuda_monto"] ? $row["deuda_monto"] : 0) * $porcentaje_dueño_por_empleado) / 100;
                    }
                }
                else
                {
                    $porcentaje_dueño = porcentaje_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $total += (($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_dueño) / 100;
                    $total += (($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_dueño) / 100;
                    $total += (($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_dueño) / 100;
                    if ($row["cliente_especial"] != "1" and $empleado != $row["empleado_telf"]) 
                    {
                        $total += (($row["deuda_monto"] ? $row["deuda_monto"] : 0) * $porcentaje_dueño) / 100;
                    }
                }
            }
        }

        foreach ($array_egresos as $row)
        {
            if ($row["tipo_egreso"] == "pago_empleado" and $row["fecha_num"] <= $fecha_num_consulta)
            {
                if ($empleado == $row["empleado_telf"])
                {
                    $total -= ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0);
                    $total -= ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
                }
            }
        }

        return $total;
    }

    function consultar_empleado($bd, &$array_empleados)
    {
        $sql = "select 
            e.empleado_telf,
            e.nombre,
            e.apellido,
            e.genero,
            e.correo,
            e.login,
            e.dueño
        from
            empleado e;";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            if (!empty($result->num_rows))
            {
                $array_empleados = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            }
        }
        else
            unset($result);
    }

    function consultar_ingresos_totales($bd, &$array_ingresos)
    {
        //Ingresos acumulados
        $sql = "select 
        i.fecha_num, 
        i.id_ingreso, 
        i.fecha,
        i.id_motivo_ingreso, 
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
            else 0 
        end deuda_monto,
        e.empleado_telf empleado_telf, 
        concat(e.nombre,' ',e.apellido) empleado,
        e.dueño dueño,
        c.especial cliente_especial, 
        concat(c.nombre,' ',c.apellido) cliente, 
        case 
            when i.id_ingreso_padre is not null then 1 
            else 0 
        end por_pago_de_deuda,
        'trabajo' tipo_ingreso 
        from 
            ingreso i 
            inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso 
            inner join empleado e on i.empleado_telf = e.empleado_telf 
            left join cliente c on i.cliente_telf = c.telf 
            left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso 
            left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso 
            left join ingreso_debito id on id.id_ingreso = i.id_ingreso
            left join ingreso_deuda idd on i.id_ingreso = idd.id_ingreso  
        union all 
        select 
            v.fecha_num, 
            v.id_venta as id_ingreso, 
            v.fecha,
            '' id_motivo_ingreso, 
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
            case 
                when v.deuda = 1 then vdd.monto 
                else 0 
            end deuda_monto,
            '' empleado_telf, 
            'Venta' empleado,
            '' dueño,
            c.especial cliente_especial, 
            concat(c.nombre,' ',c.apellido) cliente, 
            case 
                when v.id_venta_padre is not null then 1 
                else 0 
            end por_pago_de_deuda, 
            'venta' tipo_ingreso
        from 
            venta v 
            left join cliente c on v.cliente_telf = c.telf 
            left join venta_efectivo ve on v.id_venta = ve.id_venta 
            left join venta_transferencia vt on v.id_venta = vt.id_venta 
            left join venta_debito vd on vd.id_venta = v.id_venta 
            left join venta_deuda vdd on v.id_venta = vdd.id_venta 
        union all
        select
            ap.fecha_num,
            ap.id_abono_peluqueria as id_ingreso, 
            ap.fecha,
            '' id_motivo_ingreso,
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
            0 deuda_monto, 
            0 debito_monto,
            '' empleado_telf, 
            '' empleado,
            '' dueño,
            0 cliente_especial, 
            '' cliente,
            '' por_pago_de_deuda,
            'abono_peluqueria' tipo_ingreso
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
            '' empleado_telf, 
            '' empleado,
            'compra_servicio' tipo_egreso
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
            e.empleado_telf empleado_telf, 
            concat(e.nombre,' ',e.apellido) empleado,
            'pago_empleado' tipo_egreso
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
            e.empleado_telf empleado_telf, 
            concat(e.nombre,' ',e.apellido) empleado,
            'abono_empleado' tipo_egreso
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
            pg.id_porcentaje_ganancia,
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

    function consultar_porcentaje_empleados_totales_motivo($bd, &$array_porcentajes_motivo)
    {
        $sql = "select
            pg.id_motivo_porcentaje_ganancia,
            pg.id_motivo_ingreso,
            e.empleado_telf,
            concat(e.nombre,' ',e.apellido) nombre_empleado,
            pg.fecha_num,
            pg.fecha,
            pg.porcentaje_empleado,
            pg.porcentaje_peluqueria,
            pg.porcentaje_dueño
        from 
            motivo_porcentaje_ganancia pg
            inner join empleado e on pg.empleado_telf = e.empleado_telf
        order by pg.fecha_num;";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            if (!empty($result->num_rows))
            {
                $array_porcentajes_motivo = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            }
        }
        else
            unset($result);
    }

    function mostrar_acumulado_empresa($array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta)
    {
        $dia_unix_time = 84600; //Segundo en 24 horas

        $total_ingreso_efectivo = 0;
        $total_ingreso_efectivo_acumulado = 0;
        $total_ingreso_datafono = 0;
        $total_ingreso_datafono_acumulado = 0;
        $total_ingreso_transferencia = 0;
        $total_ingreso_transferencia_acumulado = 0;
        $total_ingreso = 0;
        $total_ingreso_acumulado = 0;

        $total_ingreso_efectivo_por_trabajo = 0;
        $total_ingreso_datafono_por_trabajo = 0;
        $total_ingreso_transferencia_por_trabajo = 0;
        $total_ingreso_por_trabajo = 0;

        $total_ingreso_efectivo_por_venta = 0;
        $total_ingreso_datafono_por_venta = 0;
        $total_ingreso_transferencia_por_venta = 0;
        $total_ingreso_por_venta = 0;

        $total_ingreso_efectivo_por_abono = 0;
        $total_ingreso_datafono_por_abono = 0;
        $total_ingreso_transferencia_por_abono = 0;
        $total_ingreso_por_abono = 0;

        $total_ingreso_efectivo_del_dia = 0;
        $total_ingreso_datafono_del_dia = 0;
        $total_ingreso_transferencia_del_dia = 0;
        $total_ingreso_del_dia = 0;

        $total_ganancia_peluqueria_efectivo = 0;
        $total_ganancia_peluqueria_datafono = 0;
        $total_ganancia_peluqueria_transferencia = 0;
        $total_ganancia_peluqueria = 0;

        $total_ganancia_dueño_efectivo = 0;
        $total_ganancia_dueño_datafono = 0;
        $total_ganancia_dueño_transferencia = 0;
        $total_ganancia_dueño = 0;

        $total_ganancia_dueño_por_trabajo_efectivo = 0;
        $total_ganancia_dueño_por_trabajo_datafono = 0;
        $total_ganancia_dueño_por_trabajo_transferencia = 0;
        $total_ganancia_dueño_por_trabajo = 0;

        foreach ($array_ingresos as $row)
        {
            if ($row["fecha_num"] < $fecha_num_consulta_desde)
            {
                $total_ingreso_efectivo_acumulado += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_ingreso_datafono_acumulado += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_ingreso_transferencia_acumulado += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_ingreso_acumulado += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta)
            {
                $total_ingreso_efectivo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_ingreso_datafono += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_ingreso_transferencia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_ingreso += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["tipo_ingreso"] == "venta")
            {
                $total_ingreso_efectivo_por_venta += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_ingreso_datafono_por_venta += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_ingreso_transferencia_por_venta += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_ingreso_por_venta += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["tipo_ingreso"] == "trabajo")
            {
                $total_ingreso_efectivo_por_trabajo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_ingreso_datafono_por_trabajo += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_ingreso_transferencia_por_trabajo += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_ingreso_por_trabajo += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);

                $porcentaje_peluqueria = porcentaje_peluqueria($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);

                $total_ganancia_peluqueria_efectivo += (($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_peluqueria) / 100;
                $total_ganancia_peluqueria_datafono += (($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_peluqueria) / 100;
                $total_ganancia_peluqueria_transferencia += (($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_peluqueria) / 100;
                $total_ganancia_peluqueria += ((($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_peluqueria) / 100) + 
                                    ((($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_peluqueria) / 100) + 
                                    ((($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_peluqueria) / 100);

                $porcentaje_dueño = porcentaje_dueño_por_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);

                $total_ganancia_dueño_efectivo += (($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_dueño) / 100;
                $total_ganancia_dueño_datafono += (($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_dueño) / 100;
                $total_ganancia_dueño_transferencia += (($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_dueño) / 100;
                $total_ganancia_dueño += ((($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_dueño) / 100) + 
                                    ((($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_dueño) / 100) + 
                                    ((($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_dueño) / 100);

                if ($row["dueño"] == 1 and $row["empleado_telf"] == '3226773809') {
                    $porcentaje_empleado_dueño = porcentaje_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);

                    $total_ganancia_dueño_por_trabajo_efectivo += (($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_empleado_dueño) / 100;
                    $total_ganancia_dueño_por_trabajo_datafono += (($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_empleado_dueño) / 100;
                    $total_ganancia_dueño_por_trabajo_transferencia += (($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_empleado_dueño) / 100;
                    $total_ganancia_dueño_por_trabajo += ((($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) * $porcentaje_empleado_dueño) / 100) + 
                                        ((($row["debito_monto"] ? $row["debito_monto"] : 0) * $porcentaje_empleado_dueño) / 100) + 
                                        ((($row["transferencia_monto"] ? $row["transferencia_monto"] : 0) * $porcentaje_empleado_dueño) / 100);
                }
                
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["tipo_ingreso"] == "abono_peluqueria")
            {
                $total_ingreso_efectivo_por_abono += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_ingreso_datafono_por_abono += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_ingreso_transferencia_por_abono += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_ingreso_por_abono += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            /*if ($row["fecha"] == $fecha)
            {
                $total_ingreso_efectivo_del_dia += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_ingreso_datafono_del_dia += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_ingreso_transferencia_del_dia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_ingreso_del_dia += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }*/
        }

        $total_egreso_efectivo = 0;
        $total_egreso_efectivo_acumulado = 0;
        $total_egreso_datafono = 0;
        $total_egreso_datafono_acumulado = 0;
        $total_egreso_transferencia = 0;
        $total_egreso_transferencia_acumulado = 0;
        $total_egreso = 0;
        $total_egreso_acumulado = 0;

        $total_egreso_efectivo_pago_empleado = 0;
        $total_egreso_transferencia_pago_empleado = 0;
        $total_egreso_pago_empleado = 0;

        $total_egreso_efectivo_compra = 0;
        $total_egreso_datafono_compra = 0;
        $total_egreso_transferencia_compra = 0;
        $total_egreso_compra = 0;

        $total_egreso_efectivo_abono_empleado = 0;
        $total_egreso_transferencia_abono_empleado = 0;
        $total_egreso_abono_empleado = 0;

        $total_egreso_efectivo_del_dia = 0;
        $total_egreso_datafono_del_dia = 0;
        $total_egreso_transferencia_del_dia = 0;
        $total_egreso_del_dia = 0;

        foreach ($array_egresos as $row)
        {
            if ($row["fecha_num"] < $fecha_num_consulta_desde)
            {
                $total_egreso_efectivo_acumulado += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_egreso_datafono_acumulado += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_egreso_transferencia_acumulado += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_egreso_acumulado += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta)
            {
                $total_egreso_efectivo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_egreso_datafono += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_egreso_transferencia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_egreso += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["tipo_egreso"] == "pago_empleado")
            {
                $total_egreso_efectivo_pago_empleado += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_egreso_transferencia_pago_empleado += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_egreso_pago_empleado += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["tipo_egreso"] == "compra_servicio")
            {
                $total_egreso_efectivo_compra += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_egreso_datafono_compra += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_egreso_transferencia_compra += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_egreso_compra += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["tipo_egreso"] == "abono_empleado")
            {
                $total_egreso_efectivo_abono_empleado += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_egreso_transferencia_abono_empleado += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_egreso_abono_empleado += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }

            /*if ($row["fecha"] == $fecha)
            {
                $total_egreso_efectivo_del_dia += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                $total_egreso_datafono_del_dia += $row["debito_monto"] ? $row["debito_monto"] : 0;
                $total_egreso_transferencia_del_dia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                $total_egreso_del_dia += ($row["efectivo_monto"] ? $row["efectivo_monto"] : 0) + 
                                    ($row["debito_monto"] ? $row["debito_monto"] : 0) + 
                                    ($row["transferencia_monto"] ? $row["transferencia_monto"] : 0);
            }*/
        }

        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin table-overflow" method="post">
            <div class="w3-row">
                <div class="w3-row w3-section" style='float: left;'>
                <b>Resumen</b><br>Desde <?php echo $fecha_desde; ?> hasta <?php echo $fecha_hasta; ?>
                </div>
                <div class="w3-row w3-section" style="text-align:center;">
                    <label onclick='show_acumulado();'>
                        <input type="radio" class="w3-radio" id="acumulado" name="acumulado" value="1">
                        Con acumulado
                    </label>
                    <label onclick='hide_acumulado();'>
                        <input type="radio" class="w3-radio" id="acumulado" name="acumulado" value="0" checked>
                        Sin acumulado
                    </label>
                </div>
            </div>
            <div class="w3-row">
                <div class="w3-quarter w3-container">
                    <div id="ingreso-egreso-no-acumulado" style="background-color: #569568; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em; display: block;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ingresos - Egresos
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_efectivo - $total_egreso_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_datafono - $total_egreso_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_transferencia - $total_egreso_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso - $total_egreso); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div id="ingreso-egreso-acumulado" style="background-color: #569568; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em; display: none;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ingresos - Egresos (acumulado)
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_efectivo_acumulado - $total_egreso_efectivo_acumulado) + ($total_ingreso_efectivo - $total_egreso_efectivo)); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_datafono_acumulado - $total_egreso_datafono_acumulado) + ($total_ingreso_datafono - $total_egreso_datafono)); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_transferencia_acumulado - $total_egreso_transferencia_acumulado) + ($total_ingreso_transferencia - $total_egreso_transferencia)); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_acumulado - $total_egreso_acumulado) + ($total_ingreso - $total_egreso)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div id="ingreso-no-acumulado" style="background-color: #a03e61; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ingresos
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div id="ingreso-acumulado" style="background-color: #a03e61; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em; display: none;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ingresos (acumulado)
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_efectivo_acumulado + $total_ingreso_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_datafono_acumulado + $total_ingreso_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_transferencia_acumulado + $total_ingreso_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_acumulado + $total_ingreso); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div id="egreso-no-acumulado" style="background-color: #a03e61; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Egresos
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div id="egreso-acumulado" style="background-color: #a03e61; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em; display: none;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Egresos (acumulado)
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_efectivo_acumulado + $total_egreso_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_datafono_acumulado + $total_egreso_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_transferencia_acumulado + $total_egreso_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_acumulado + $total_egreso); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div style="background-color: #6C6C6C; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ingresos por ventas
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_efectivo_por_venta); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_datafono_por_venta); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_transferencia_por_venta); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_por_venta); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w3-row">
                <div class="w3-quarter w3-container">
                    <div style="background-color: #6C6C6C; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ingresos por trabajos realizados
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_efectivo_por_trabajo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_datafono_por_trabajo); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_transferencia_por_trabajo); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_por_trabajo); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div style="background-color: #6C6C6C; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ingresos por abono a peluquer&iacute;a
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_efectivo_por_abono); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_datafono_por_abono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_transferencia_por_abono); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ingreso_por_abono); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div style="background-color: #6C6C6C; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Egresos por pago a empleados
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_efectivo_pago_empleado); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_transferencia_pago_empleado); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_pago_empleado); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div style="background-color: #6C6C6C; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Egresos por compra o pagos de servicios
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_efectivo_compra); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_datafono_compra); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_transferencia_compra); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_compra); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w3-row">
                <div class="w3-quarter w3-container">
                    <div style="background-color: #6C6C6C; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Egresos por abono a empleados
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_efectivo_abono_empleado); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_transferencia_abono_empleado); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_egreso_abono_empleado); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div style="background-color: #569568; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ganancia de la peluquer&iacute;a por trabajos realizados
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_peluqueria_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_peluqueria_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_peluqueria_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_peluqueria); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div style="background-color: #569568; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ganancia del dueño por empleados
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w3-quarter w3-container">
                    <div style="background-color: #569568; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Ganancia del dueño por trabajos realizados
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño_por_trabajo_efectivo); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño_por_trabajo_datafono); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño_por_trabajo_transferencia); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', $total_ganancia_dueño_por_trabajo); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- <div class="w3-quarter w3-container">
                    <div style="background-color: #567C95; color: #ffffff; margin: 0.5em; padding-left: 0.5em; padding-right: 0.5em; padding-bottom: 0.5em;">
                        <div class="w3-row w3-section" style='font-weight: bolder; text-align: center;'>
                            Cuadre de caja del d&iacute;a
                        </div>
                        <table border="0" width="100%">
                            <tr>
                                <td>Total efectivo:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_efectivo_del_dia - $total_egreso_efectivo_del_dia) >= 0 ? ($total_ingreso_efectivo_del_dia - $total_egreso_efectivo_del_dia) : 0); ?></td>
                            </tr>
                            <tr>
                                <td>Total dat&aacute;fono:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_datafono_del_dia - $total_egreso_datafono_del_dia) >= 0 ? ($total_ingreso_datafono_del_dia - $total_egreso_datafono_del_dia) : 0); ?></td>
                            </tr>
                            <tr>
                                <td>Total transferencia:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_transferencia_del_dia - $total_egreso_transferencia_del_dia) >= 0 ? ($total_ingreso_transferencia_del_dia - $total_egreso_transferencia_del_dia) : 0); ?></td>
                            </tr>
                            <tr>
                                <td>Total:</td>
                                <td align="right" nowrap><?php echo money_format('%.2n', ($total_ingreso_del_dia - $total_egreso_del_dia) >= 0 ? ($total_ingreso_del_dia - $total_egreso_del_dia) : 0); ?></td>
                            </tr>
                        </table>
                    </div>
                </div> -->
            </div>
        </form>
        <?php
    }

    function mostrar_ingresos_netos_del_dia($array_ingresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin table-overflow" method="post">
        <div class="w3-row w3-section" style='float: left;'>
            <b>Ingresos netos</b><br>Desde <?php echo $fecha_desde; ?> hasta <?php echo $fecha_hasta; ?>
        </div>
        <div class="w3-row w3-section" style='font-weight: bolder; float: right;'>
            <span style='cursor:pointer;' class='w3-button' onclick="return mostrar_ocultar_div('id-ingreso-del-dia');">
                <i id="id-ingreso-del-dia-icon" class='icon-chevron-down'></i>
            </span>
        </div>
        <div id="id-ingreso-del-dia" class="w3-row w3-section" style="display:none;">
        <?php
        if (existe_rango_fecha_en_arreglo($array_ingresos, $fecha_num_consulta_desde, $fecha_num_consulta_hasta))
        {
            ?>
            <div id="id-ingreso-del-dia-contenido-1" style="display:none;">
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                    <thead>
                        <tr class="w3-dulcevanidad">
                            <th align="center">Fecha</th>
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
                            foreach($array_ingresos as $row)
                            {
                                if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["deuda"] != 1) 
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
                                    
                                    echo "<tr style='";
                                    if ($por_pago_de_deuda == 1) 
                                    {
                                        $por_pago_de_deuda_encontrado = 1;
                                        echo"background-color: #C8A2C8";
                                    }
                                    echo "'>";
                                    echo"<td class='table-celda-texto'>".$row["fecha"]."</td>";
                                    echo"<td class='table-celda-texto'>".$row["motivo"]."</td>";
                                    echo"<td class='table-celda-texto'>".$row["empleado"]."</td>";
                                    echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["efectivo_monto"])."</td>";
                                    echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["debito_monto"])."</td>";
                                    echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["transferencia_monto"])."</td>";
                                    echo"<td class='table-celda-texto'>".$row["transferencia_referencia"]."</td>";
                                    echo"<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_ingreso_linea)."</td>";
                                    echo "</tr>";
                                    $total_ingreso_linea = 0;
                                }
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                if ($por_pago_de_deuda_encontrado == 1)
                {
                    echo"<table border=0><tr><td style='background-color: #C8A2C8' width='25em'></td><td>Pago por deuda</td></tr></table><br>";
                }
                echo "<table border='0'>";
                echo "<tr><td><b>Total&nbsp;efectivo:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_efectivo)."</td></tr>";
                echo "<tr><td><b>Total&nbsp;dat&aacute;fono:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_datafono)."</td></tr>";
                echo "<tr><td><b>Total&nbsp;transferencia:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_transferencia);"</td></tr>";
                echo "<tr><td><b>Total:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_del_dia);"</td></tr>";
                echo "</table>";
                unset($total_ingreso_del_dia, $total_ingreso_linea, $total_ingreso_efectivo, $total_ingreso_datafono, $total_ingreso_transferencia);
                ?>
            </div>
            <?php
        }
        else 
        {
            ?>
            <div id="id-ingreso-del-dia-contenido-2" class="w3-panel w3-blue-grey" style="display: none">
            <h3>Aviso</h3>
            <p>No hubo ingresos registrados</p>
            </div>
            <?php
        }
        ?>
        </div>
        </form>
        <?php
    }

    function mostrar_deudas_del_dia($array_ingresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin table-overflow" method="post">
        <div class="w3-row w3-section" style='float: left;'>
            <b>Deudas</b><br>Desde<?php echo $fecha_desde; ?> hasta <?php echo $fecha_hasta; ?>
        </div>
        <div class="w3-row w3-section" style='font-weight: bolder; float: right;'>
            <span style='cursor:pointer;' class='w3-button' onclick="return mostrar_ocultar_div('id-deuda-del-dia');">
                <i id="id-deuda-del-dia-icon" class='icon-chevron-down'></i>
            </span>
        </div>
        <div id="id-deuda-del-dia" class="w3-row w3-section" style="display:none;">
        <?php
        if (existe_rango_fecha_en_arreglo_con_deuda($array_ingresos, $fecha_num_consulta_desde, $fecha_num_consulta_hasta))
        {
            ?>
            <div id="id-deuda-del-dia-contenido-1" style="display:none;">
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                    <thead>
                        <tr class="w3-dulcevanidad">
                            <th align="center">Fecha</th>
                            <th align="center">Tipo</th>
                            <th align="center">Empleado</th>
                            <th align="center">Cliente</th>
                            <th align="center">Deuda</th>
                            <th align="center">Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total_deuda = 0;
                            foreach ($array_ingresos as $row)
                            {
                                if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta and $row["deuda"] == 1) 
                                {
                                    $total_deuda += $row["deuda_monto"] ? $row["deuda_monto"] : 0;
                                    echo"<tr>";
                                    echo"<td class='table-celda-texto'>".$row["fecha"]."</td>";
                                    echo"<td class='table-celda-texto'>".$row["motivo"]."</td>";
                                    echo"<td class='table-celda-texto'>".$row["empleado"]."</td>";
                                    echo"<td class='table-celda-texto'>".$row["cliente"]."</td>";
                                    echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["deuda_monto"])."</td>";
                                    echo"<td class='table-celda-texto-ultima'>".$row["observacion"]."</td>";
                                    echo"</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                    echo "<table border='0'>";
                    echo "<tr><td><b>Total:</b></td><td align='right' nowrap>".money_format('%.2n', $total_deuda);"</td></tr>";
                    echo "</table>";
                ?>
            </div>
            <?php
        }
        else
        {
            ?>
            <div id="id-deuda-del-dia-contenido-2" class="w3-panel w3-blue-grey" style="display: none">
            <h3>Aviso</h3>
            <p>No hubo deudas registradas</p>
            </div>  
            <?php
        }
        ?>
        </div>
        </form>
        <?php
    }

    function mostrar_ventas_del_dia($array_ingresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin table-overflow" method="post">
        <div class="w3-row w3-section" style='float: left;'>
            <b>Ventas</b><br>Desde <?php echo $fecha_desde; ?> hasta <?php echo $fecha_hasta; ?>
        </div>
        <div class="w3-row w3-section" style='font-weight: bolder; float: right;'>
            <span style='cursor:pointer;' class='w3-button' onclick="return mostrar_ocultar_div('id-ventas-del-dia');">
                <i id="id-ventas-del-dia-icon" class='icon-chevron-down'></i>
            </span>
        </div>
        <div id="id-ventas-del-dia" class="w3-row w3-section" style="display:none;">
        <?php
        if (existe_rango_fecha_en_arreglo($array_ingresos, $fecha_num_consulta_desde, $fecha_num_consulta_hasta) and existe_rango_venta_en_el_arreglo($array_ingresos, $fecha_num_consulta_desde, $fecha_num_consulta_hasta))
        {
            ?>
            <div id="id-ventas-del-dia-contenido-1" style="display:none;">
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                    <thead>
                        <tr class="w3-dulcevanidad">
                            <th align="center">Fecha</th>
                            <th align="center">Tipo</th>
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
                            foreach ($array_ingresos as $row)
                            {
                                if ($row["tipo_ingreso"] == "venta" and $row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta)
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
                                    echo"<td class='table-celda-texto'>".$row["fecha"]."</td>";
                                    echo"<td class='table-celda-texto'>".$row["motivo"]."</td>";
                                    echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["efectivo_monto"])."</td>";
                                    echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["debito_monto"])."</td>";
                                    echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["transferencia_monto"])."</td>";
                                    echo"<td class='table-celda-texto'>".$row["transferencia_referencia"]."</td>";
                                    echo"<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_ingreso_linea)."</td>";
                                    echo"</tr>";
                                    $total_ingreso_linea = 0;
                                }
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                if ($por_pago_de_deuda_encontrado == 1)
                {
                    echo"<table border=0><tr><td style='background-color: #C8A2C8' width='25em'></td><td>Pago por deuda</td></tr></table><br>";
                }
                echo "<table border='0'>";
                echo "<tr><td><b>Total&nbsp;efectivo:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_efectivo)."</td></tr>";
                echo "<tr><td><b>Total&nbsp;dat&aacute;fono:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_datafono)."</td></tr>";
                echo "<tr><td><b>Total&nbsp;transferencia:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_transferencia)."</td></tr>";
                echo "<tr><td><b>Total:</b></td><td align='right' nowrap>".money_format('%.2n', $total_ingreso_del_dia)."</td></tr>";
                echo "</table>";
                unset($total_ingreso_del_dia, $total_ingreso_linea, $total_ingreso_efectivo, $total_ingreso_datafono, $total_ingreso_transferencia);
                ?>
            </div>
            <?php
        }
        else
        {
            ?>
            <div id="id-ventas-del-dia-contenido-2" class="w3-panel w3-blue-grey">
            <h3>Aviso</h3>
            <p>No hubo ventas registradas</p>
            </div>  
            <?php
        }
        ?>
        </div>
        </form>
        <?php
    }

    function mostrar_egresos_del_dia($array_egresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin table-overflow" method="post">
        <div class="w3-row w3-section" style='float: left;'>
            <b>Egresos</b><br>Desde <?php echo $fecha_desde; ?> hasta <?php echo $fecha_hasta; ?>
        </div>
        <div class="w3-row w3-section" style='font-weight: bolder; float: right;'>
            <span style='cursor:pointer;' class='w3-button' onclick="return mostrar_ocultar_div('id-egreso-del-dia');">
                <i id="id-egreso-del-dia-icon" class='icon-chevron-down'></i>
            </span>
        </div>
        <div id="id-egreso-del-dia" class="w3-row w3-section" style="display:none;">
        <?php
        if (existe_rango_fecha_en_arreglo($array_egresos, $fecha_num_consulta_desde, $fecha_num_consulta_hasta))
        {
            ?>
            <div id="id-egreso-del-dia-contenido-1" style="display:none;">
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                    <thead>
                        <tr class="w3-dulcevanidad">
                            <th align="center">Fecha</th>
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
                        foreach ($array_egresos as $row)
                        {
                            if ($row["fecha_num"] >= $fecha_num_consulta_desde and $row["fecha_num"] <= $fecha_num_consulta_hasta)
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
                                echo"<td class='table-celda-texto'>".$row["fecha"]."</td>";
                                echo"<td class='table-celda-texto'>".$row["motivo"]."</td>";
                                echo"<td class='table-celda-texto'>".$row["empleado"]."</td>";
                                echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["efectivo_monto"])."</td>";
                                echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["debito_monto"])."</td>";
                                echo"<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row["transferencia_monto"])."</td>";
                                echo"<td class='table-celda-texto'>".$row["transferencia_referencia"]."</td>";
                                echo"<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_egreso_linea)."</td>";
                                echo"</tr>";

                                $total_egreso_linea = 0;
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                echo "<table border='0'>";
                echo "<tr><td><b>Total&nbsp;efectivo:</b></td><td align='right' nowrap>".money_format('%.2n', $total_egreso_efectivo)."</td></tr>";
                echo "<tr><td><b>Total&nbsp;dat&aacute;fono:</b></td><td align='right' nowrap>".money_format('%.2n', $total_egreso_datafono)."</td></tr>";
                echo "<tr><td><b>Total&nbsp;transferencia:</b></td><td align='right' nowrap>".money_format('%.2n', $total_egreso_transferencia)."</td></tr>";
                echo "<tr><td><b>Total:</b></td><td align='right' nowrap>".money_format('%.2n', $total_egreso_del_dia)."</td></tr>";
                echo "</table>";
                ?>
            </div>
            <?php
        }
        else 
        {
            ?>
            <div id="id-egreso-del-dia-contenido-2" class="w3-panel w3-blue-grey">
            <h3>Aviso</h3>
            <p>No hubo egresos registrados</p>
            </div>  
            <?php
        }
        ?>
        </div>
        </form>
        <?php
    }

    function crear_modal($empleado_telf, $empleado_nombre, $dueño, $array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha, $fecha_num_consulta)
    {
        //Modal de detalles pagos a empleados
        echo "<div id='modal_detalle_empleado_".$empleado_telf."' class='w3-modal'>";
        echo "<div class='w3-modal-content'>";
        echo "<div onclick=\"document.getElementById('modal_detalle_empleado_".$empleado_telf."').style.display='none'\" class='w3-button w3-display-topright'>&times;</div>";
        echo "<div class='w3-display-topleft' style='padding: 1em;'><b>".$empleado_nombre."</b><br><br>"; 
        echo "</div>";
        echo "<br><br>";
        echo "<div style='padding: 1em;'>";
        echo "<input type='date' class='w3-input w3-border' id='bfecha_".$empleado_telf."' name='bfecha_".$empleado_telf."' placeholder='dd-mm-aaaa'>";
        echo "<input class='w3-button w3-block w3-dulcevanidad' type='button' id='enviar' name='enviar' value='Consultar' onclick=\"consultar_fecha_empleado('".$empleado_telf."');\">";
        echo "</div>";
        echo "<div class='table-overflow' style='padding: 1em;'>";
        $resultado_fecha = array();
        $resultado = array();
        $i = 0;
        if ($dueño)
        {
            foreach ($array_ingresos as $row)
            {
                if (!existe_fecha_en_arreglo($resultado_fecha, $row["fecha"]) and $row["fecha_num"] <=  $fecha_num_consulta)
                {
                    $resultado_fecha[$i]["fecha_num"] = $row["fecha_num"];
                    $resultado_fecha[$i]["fecha"] = $row["fecha"];
                    $resultado_fecha[$i]["empleado_telf"] = $row["empleado_telf"];
                    $i++;
                }
            }

            foreach ($array_egresos as $row)
            {
                if (!existe_fecha_en_arreglo($resultado_fecha, $row["fecha"]) and $row["fecha_num"] <=  $fecha_num_consulta)
                {
                    $resultado_fecha[$i]["fecha_num"] = $row["fecha_num"];
                    $resultado_fecha[$i]["fecha"] = $row["fecha"];
                    $resultado_fecha[$i]["empleado_telf"] = $row["empleado_telf"];
                    $i++;
                }
            }

            foreach ($array_ingresos as $row)
            {
                if ($row["tipo_ingreso"] == "trabajo") 
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
                    $resultado[$i]["empleado"] = $row["empleado"];
                    $resultado[$i]["porcentaje_empleado"] = porcentaje_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $resultado[$i]["porcentaje_peluqueria"] = porcentaje_peluqueria($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $resultado[$i]["porcentaje_dueño"] = porcentaje_dueño_por_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $resultado[$i]["por_pago_de_deuda"] = $row["por_pago_de_deuda"];
                    $resultado[$i]["cliente_especial"] = $row["cliente_especial"];
                    $i++;
                }
            }

            foreach ($array_egresos as $row)
            {
                if ($row["tipo_egreso"] == "pago_empleado") 
                {
                    $resultado[$i]["fecha_num"] = $row["fecha_num"];
                    $resultado[$i]["fecha"] = $row["fecha"];
                    $resultado[$i]["tipo"] = "pago";
                    $resultado[$i]["motivo"] = $row["motivo"];
                    $resultado[$i]["efectivo_monto"] = !empty($row["efectivo_monto"]) ? $row["efectivo_monto"] : 0;
                    $resultado[$i]["debito_monto"] = 0;
                    $resultado[$i]["transferencia_monto"] = !empty($row["transferencia_monto"]) ? $row["transferencia_monto"] : 0;
                    $resultado[$i]["transferencia_referencia"] = !empty($row["transferencia_referencia"]) ? $row["transferencia_referencia"] : 0;
                    $resultado[$i]["empleado_telf"] = $row["empleado_telf"];
                    $resultado[$i]["empleado"] = $row["empleado"];
                    $i++;
                }
            }

            array_multisort($resultado_fecha, SORT_DESC, SORT_REGULAR);

            foreach ($resultado_fecha as $row)
            {
                echo "<div id='empleado_".$empleado_telf."_fecha_".$row["fecha"]."' class='fecha-detalle-empleado' style='display:none;'>";
                    echo "<b>".$row["fecha"][0].$row["fecha"][1]."/".$row["fecha"][3].$row["fecha"][4]."/".$row["fecha"][6].$row["fecha"][7].$row["fecha"][8].$row["fecha"][9].":</b><br><br>";
                    echo "<div id='detalle_fecha_".$row["fecha"]."_".$empleado_telf."' class='table-overflow' style='display: block;border: 0px solid #cccccc;margin-top: -1.5em;'>";
                    echo "<table class=\"w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white\">";
                    echo "<thead>";
                    echo "<tr class=\"w3-dulcevanidad\">";
                    echo "<th align='center'>Empleado</th>";
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
                        if ($row2["fecha"] == $row["fecha"])
                        {
                            echo "<tr>";
                            if ($row2["tipo"] == "ingreso" and $row2["por_pago_de_deuda"] != 1 and $row2["cliente_especial"] != 1 /*and $empleado_telf != $row2["empleado_telf"]*/) 
                            {
                                echo "<td class='table-celda-texto'>".$row2["empleado"]."</td>";
                                echo "<td class='table-celda-texto'>".$row2["motivo"]."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["efectivo_monto"])."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["debito_monto"])."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["transferencia_monto"])."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["deuda_monto"])."</td>";

                                if ($row2["empleado_telf"] != $empleado_telf) 
                                {
                                    $total_por_linea_con_porcentaje += ($row2["efectivo_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_linea_con_porcentaje += ($row2["debito_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_linea_con_porcentaje += ($row2["transferencia_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_linea_con_porcentaje += ($row2["deuda_monto"] * $row2["porcentaje_dueño"] / 100);
                                    echo "<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_por_linea_con_porcentaje)."</td>";

                                    $total_por_dia += ($row2["efectivo_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_dia += ($row2["debito_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_dia += ($row2["transferencia_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_dia += ($row2["deuda_monto"] * $row2["porcentaje_dueño"] / 100);

                                    $total_por_dia_ingreso += ($row2["efectivo_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_dia_ingreso += ($row2["debito_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_dia_ingreso += ($row2["transferencia_monto"] * $row2["porcentaje_dueño"] / 100);
                                    $total_por_dia_ingreso += ($row2["deuda_monto"] * $row2["porcentaje_dueño"] / 100);
                                }
                                else 
                                {
                                    $total_por_linea_con_porcentaje += ($row2["efectivo_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_linea_con_porcentaje += ($row2["debito_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_linea_con_porcentaje += ($row2["transferencia_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_linea_con_porcentaje += ($row2["deuda_monto"] * $row2["porcentaje_empleado"] / 100);
                                    echo "<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_por_linea_con_porcentaje)."</td>";

                                    $total_por_dia += ($row2["efectivo_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_dia += ($row2["debito_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_dia += ($row2["transferencia_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_dia += ($row2["deuda_monto"] * $row2["porcentaje_empleado"] / 100);

                                    $total_por_dia_ingreso += ($row2["efectivo_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_dia_ingreso += ($row2["debito_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_dia_ingreso += ($row2["transferencia_monto"] * $row2["porcentaje_empleado"] / 100);
                                    $total_por_dia_ingreso += ($row2["deuda_monto"] * $row2["porcentaje_empleado"] / 100);
                                }
                            }
                            else 
                            {
                                if ($row2["tipo"] == "pago" and $row2["empleado_telf"] == $empleado_telf and (!isset($row2["por_pago_de_deuda"]) or $row2["por_pago_de_deuda"] != 1)) {
                                    echo "<td class='table-celda-texto'>".$row2["empleado"]."</td>";
                                    echo "<td class='table-celda-texto'>".$row2["motivo"]."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["efectivo_monto"])."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["debito_monto"])."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["transferencia_monto"])."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>0</td>";
                                    $total_por_linea_con_porcentaje += $row2["efectivo_monto"];
                                    $total_por_linea_con_porcentaje += $row2["debito_monto"];
                                    $total_por_linea_con_porcentaje += $row2["transferencia_monto"];
                                    echo "<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_por_linea_con_porcentaje)."</td>";
                                    $total_por_dia -= $row2["efectivo_monto"];
                                    $total_por_dia -= $row2["debito_monto"];
                                    $total_por_dia -= $row2["transferencia_monto"];
                                    $total_por_dia_pago += $row2["efectivo_monto"];
                                    $total_por_dia_pago += $row2["debito_monto"];
                                    $total_por_dia_pago += $row2["transferencia_monto"];
                                }
                            }
                        }
                    }
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td class='table-celda-texto'><b>Totales:</b></td>";
                    echo "<td class='table-celda-texto-ultima' colspan='6' align='center' nowrap><b>Ingreso:&nbsp;".money_format('%.2n', $total_por_dia_ingreso)."&nbsp;&nbsp;Pago:&nbsp;".money_format('%.2n', $total_por_dia_pago)."</b></td>";
                    echo "</tr>";
                    $total_por_dia = 0;
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                echo "</div>";
            }
        }
        else
        {
            foreach ($array_ingresos as $row)
            {
                if (!existe_fecha_en_arreglo($resultado_fecha, $row["fecha"]) and $row["empleado_telf"] == $empleado_telf and $row["fecha_num"] <=  $fecha_num_consulta)
                {
                    $resultado_fecha[$i]["fecha_num"] = $row["fecha_num"];
                    $resultado_fecha[$i]["fecha"] = $row["fecha"];
                    $resultado_fecha[$i]["empleado_telf"] = $row["empleado_telf"];
                    $i++;
                }
            }

            foreach ($array_egresos as $row)
            {
                if (!existe_fecha_en_arreglo($resultado_fecha, $row["fecha"]) and $row["empleado_telf"] == $empleado_telf and $row["fecha_num"] <=  $fecha_num_consulta)
                {
                    $resultado_fecha[$i]["fecha_num"] = $row["fecha_num"];
                    $resultado_fecha[$i]["fecha"] = $row["fecha"];
                    $resultado_fecha[$i]["empleado_telf"] = $row["empleado_telf"];
                    $i++;
                }
            }

            foreach ($array_ingresos as $row)
            {
                if ($row["tipo_ingreso"] == "trabajo") 
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
                    $resultado[$i]["porcentaje_empleado"] = porcentaje_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $resultado[$i]["porcentaje_peluqueria"] = porcentaje_peluqueria($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $resultado[$i]["porcentaje_dueño"] = porcentaje_dueño_por_empleado($array_porcentajes, $array_porcentajes_motivo, $row["fecha_num"], $row["empleado_telf"], $row["id_motivo_ingreso"]);
                    $resultado[$i]["por_pago_de_deuda"] = $row["por_pago_de_deuda"];
                    $i++;
                }
            }

            foreach ($array_egresos as $row)
            {
                if ($row["tipo_egreso"] == "pago_empleado") 
                {
                    $resultado[$i]["fecha_num"] = $row["fecha_num"];
                    $resultado[$i]["fecha"] = $row["fecha"];
                    $resultado[$i]["tipo"] = "pago";
                    $resultado[$i]["motivo"] = $row["motivo"];
                    $resultado[$i]["efectivo_monto"] = !empty($row["efectivo_monto"]) ? $row["efectivo_monto"] : 0;
                    $resultado[$i]["debito_monto"] = 0;
                    $resultado[$i]["transferencia_monto"] = !empty($row["transferencia_monto"]) ? $row["transferencia_monto"] : 0;
                    $resultado[$i]["transferencia_referencia"] = !empty($row["transferencia_referencia"]) ? $row["transferencia_referencia"] : 0;
                    $resultado[$i]["empleado_telf"] = $row["empleado_telf"];
                    $resultado[$i]["por_pago_de_deuda"] = "0";
                    $i++;
                }
            }

            array_multisort($resultado_fecha, SORT_DESC, SORT_REGULAR);

            foreach ($resultado_fecha as $row)
            {
                echo "<div id='empleado_".$empleado_telf."_fecha_".$row["fecha"]."' class='fecha-detalle-empleado' style='display:none;'>";
                    echo "<b>".$row["fecha"][0].$row["fecha"][1]."/".$row["fecha"][3].$row["fecha"][4]."/".$row["fecha"][6].$row["fecha"][7].$row["fecha"][8].$row["fecha"][9].":</b><br><br>";
                    echo "<div id='detalle_fecha_".$row["fecha"]."_".$empleado_telf."' class='table-overflow' style='display: block;border: 0px solid #cccccc;margin-top: -1.5em;'>";
                    echo "<table class=\"w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white\">";
                    echo "<thead>";
                    echo "<tr class=\"w3-dulcevanidad\">";
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
                        if ($row2["fecha"] == $row["fecha"] and $row2["empleado_telf"] == $empleado_telf)
                        {
                            echo "<tr>";
                            if ($row2["tipo"] == "ingreso" and $row2["por_pago_de_deuda"] != 1) {
                                echo "<td class='table-celda-texto'>".$row2["motivo"]."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["efectivo_monto"])."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["debito_monto"])."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["transferencia_monto"])."</td>";
                                echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["deuda_monto"])."</td>";
                                $total_por_linea_con_porcentaje += ($row2["efectivo_monto"] * $row2["porcentaje_empleado"] / 100);
                                $total_por_linea_con_porcentaje += ($row2["debito_monto"] * $row2["porcentaje_empleado"] / 100);
                                $total_por_linea_con_porcentaje += ($row2["transferencia_monto"] * $row2["porcentaje_empleado"] / 100);
                                $total_por_linea_con_porcentaje += ($row2["deuda_monto"] * $row2["porcentaje_empleado"] / 100);
                                echo "<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_por_linea_con_porcentaje)."</td>";
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
                                if ($row2["tipo"] == "pago" and $row2["por_pago_de_deuda"] != 1) {
                                    echo "<td class='table-celda-texto'>".$row2["motivo"]."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["efectivo_monto"])."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["debito_monto"])."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', $row2["transferencia_monto"])."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>0</td>";
                                    $total_por_linea_con_porcentaje += $row2["efectivo_monto"];
                                    $total_por_linea_con_porcentaje += $row2["debito_monto"];
                                    $total_por_linea_con_porcentaje += $row2["transferencia_monto"];
                                    echo "<td class='table-celda-numerica-ultima' nowrap>".money_format('%.2n', $total_por_linea_con_porcentaje)."</td>";
                                    $total_por_dia -= $row2["efectivo_monto"];
                                    $total_por_dia -= $row2["debito_monto"];
                                    $total_por_dia -= $row2["transferencia_monto"];
                                    $total_por_dia_pago += $row2["efectivo_monto"];
                                    $total_por_dia_pago += $row2["debito_monto"];
                                    $total_por_dia_pago += $row2["transferencia_monto"];
                                }
                            }
                        }
                    }
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td class='table-celda-texto'><b>Totales:</b></td>";
                    echo "<td class='table-celda-texto-ultima' colspan='5' align='center' nowrap><b>Ingreso:&nbsp;".money_format('%.2n', $total_por_dia_ingreso)."&nbsp;&nbsp;Pago:&nbsp;".money_format('%.2n', $total_por_dia_pago)."</b></td>";
                    echo "</tr>";
                    $total_por_dia = 0;
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                echo "</div>";
            }
        }    
        echo "</table>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        //Fin de modal de detalles pagos a empleados
    }

    function mostrar_acumulado_empleados($array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $array_empleados, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin table-overflow" method="post">
        <div class="w3-row w3-section" style='float: left;'>
            <b>Empleados</b><br>Hasta <?php echo $fecha_hasta; ?>
        </div>
        <div class="w3-row w3-section">
        <?php
            if (count($array_empleados))
            {
                ?>
                <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
                    <thead>
                        <tr class="w3-dulcevanidad">
                            <th align="center">Empleado</th>
                            <th align="center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($array_empleados as $empleado)
                            {
                                if ($empleado["dueño"] == 1)
                                {
                                    echo "<tr style='cursor:pointer;' onclick=\"document.getElementById('modal_detalle_empleado_".$empleado["empleado_telf"]."').style.display='block'\">";
                                    echo "<td class='table-celda-texto'>".$empleado["nombre"]." ".$empleado["apellido"]."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', total_dueño($empleado["empleado_telf"], $array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha_hasta, $fecha_num_consulta_hasta))."</td>";
                                    echo "</tr>";
                                }
                                else
                                {
                                    echo "<tr style='cursor:pointer;' onclick=\"document.getElementById('modal_detalle_empleado_".$empleado["empleado_telf"]."').style.display='block'\">";
                                    echo "<td class='table-celda-texto'>".$empleado["nombre"]." ".$empleado["apellido"]."</td>";
                                    echo "<td class='table-celda-numerica' nowrap>".money_format('%.2n', total_empleado($empleado["empleado_telf"], $array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha_hasta, $fecha_num_consulta_hasta, $empleado["dueño"]))."</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                foreach ($array_empleados as $empleado)
                {
                    crear_modal($empleado["empleado_telf"], $empleado["nombre"]." ".$empleado["apellido"], $empleado["dueño"], $array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha_hasta, $fecha_num_consulta_hasta);
                }
            }
            else
            {
                ?>
                <div class="w3-panel w3-blue-grey">
                <h3>Aviso</h3>
                <p>No hay empleados registrados</p>
                </div>  
                <?php
            }
        ?>
        </div>
        </form>
        <?php
    }

    function mostrar_busqueda($bd, &$array_ingresos, &$array_egresos, &$array_porcentajes, &$array_porcentajes_motivo, &$array_empleados)
    {
        $admin = usuario_admin();
        $cajero = usuario_cajero();
        $consulta = usuario_consulta();

        consultar_empleado($bd, $array_empleados);

        consultar_ingresos_totales($bd, $array_ingresos);

        consultar_egresos_totales($bd, $array_egresos);

        consultar_porcentaje_empleados_totales($bd, $array_porcentajes);

        consultar_porcentaje_empleados_totales_motivo($bd, $array_porcentajes_motivo);

        $fecha_num_consulta_desde = strtotime($_POST["bfecha_desde"][6].$_POST["bfecha_desde"][7].$_POST["bfecha_desde"][8].$_POST["bfecha_desde"][9]."-".$_POST["bfecha_desde"][3].$_POST["bfecha_desde"][4]."-".$_POST["bfecha_desde"][0].$_POST["bfecha_desde"][1]);
        $fecha_num_consulta_hasta = strtotime($_POST["bfecha_hasta"][6].$_POST["bfecha_hasta"][7].$_POST["bfecha_hasta"][8].$_POST["bfecha_hasta"][9]."-".$_POST["bfecha_hasta"][3].$_POST["bfecha_hasta"][4]."-".$_POST["bfecha_hasta"][0].$_POST["bfecha_hasta"][1]);
        $fecha_desde = $_POST["bfecha_desde"];
        $fecha_hasta = $_POST["bfecha_hasta"];

        mostrar_acumulado_empresa($array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta);

        mostrar_ingresos_netos_del_dia($array_ingresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta);

        mostrar_deudas_del_dia($array_ingresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta);

        mostrar_ventas_del_dia($array_ingresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta);

        mostrar_egresos_del_dia($array_egresos, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta);

        mostrar_acumulado_empleados($array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $array_empleados, $fecha_desde, $fecha_hasta, $fecha_num_consulta_desde, $fecha_num_consulta_hasta);

    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        $array_ingresos = array();
        $array_egresos = array();
        $array_porcentajes = array();
        $array_porcentajes_motivo = array();
        $array_empleados = array();
        mostrar_busqueda($bd, $array_ingresos, $array_egresos, $array_porcentajes, $array_porcentajes_motivo, $array_empleados);
    }
?>