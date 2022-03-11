<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");

    function mostrar_busqueda($bd)
    {
        $sql = "select i.id_ingreso, i.fecha, mi.motivo, i.efectivo, i.transferencia, i.debito, i.deuda, i.observacion, case when i.efectivo = 1 then ie.monto else '' end efectivo_monto, case when i.transferencia = 1 then it.monto else '' end transferencia_monto, case when i.transferencia = 1 then it.referencia else '' end transferencia_referencia, case when i.debito = 1 then id.monto else '' end debito_monto from ingreso i inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso left join ingreso_efectivo ie on i.id_ingreso = ie.id_ingreso left join ingreso_transferencia it on i.id_ingreso = it.id_ingreso left join ingreso_debito id on id.id_ingreso = i.id_ingreso where i.fecha = '".$_POST["bfecha"]."' and i.deuda = 0 order by i.fecha_num asc;";
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
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Ingresos netos</div>
                    <div class="w3-row w3-section">    
                        <table border='1' cellpadding='5' cellspacing='0'>
                            <thead>
                                <tr>
                                    <th align="center">Tipo De Trabajo</th>
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
                                    foreach ($rows as $row)
                                    {
                                        $total_ingreso_del_dia += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_ingreso_del_dia += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $total_ingreso_del_dia += $row["debito_monto"] ? $row["debito_monto"] : 0;
                                        $total_ingreso_trabajo += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
                                        $total_ingreso_trabajo += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
                                        $total_ingreso_trabajo += $row["debito_monto"] ? $row["debito_monto"] : 0;
                                        echo"<tr>";
                                        echo"<td>".$row["motivo"]."</td>";
                                        echo"<td align='right'>".$row["efectivo_monto"]."</td>";
                                        echo"<td align='right'>".$row["debito_monto"]."</td>";
                                        echo"<td align='right'>".$row["transferencia_monto"]."</td>";
                                        echo"<td align='left'>".$row["transferencia_referencia"]."</td>";
                                        echo"<td align='right'>".$total_ingreso_trabajo."</td>";
                                        echo"</tr>";
                                        $total_ingreso_trabajo = 0;
                                    }
                                    echo "<tr><td colspan='5'>&nbsp;</td>";
                                    echo "<td align='right'>".$total_ingreso_del_dia."</td></tr>";
                                    unset($total_ingreso_del_dia,$total_ingreso_trabajo);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php
            }
            else {
                ?>
                <form class="w3-container w3-card-4 w3-light-grey w3-margin" method="post">
                    <div class="w3-row  w3-section" style='font-weight: bolder;'>Ingresos netos: <i style='color:crimson;'>No hubo ingresos</i></div>
                </form>
                <?php
            }
            unset($rows);
        }
        else
            unset ($result);
        
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        mostrar_busqueda($bd);
    }
?>