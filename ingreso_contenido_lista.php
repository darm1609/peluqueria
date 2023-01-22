<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function eliminar_ingreso($bd)
	{
		global $basedatos;

		//Eliminar pago de deuda
		$sql = "select 
			i.id_ingreso_padre, 
			case 
				when i.efectivo = 1 then ie.monto 
				else '' 
			end efectivo_monto, 
			case 
				when i.transferencia = 1 then it.monto 
				else '' 
			end transferencia_monto, 
			case 
				when i.debito = 1 then id.monto 
				else '' 
			end debito_monto
		from 
			ingreso i 
			left join ingreso_debito id on id.id_ingreso = i.id_ingreso 
			left join ingreso_efectivo ie on ie.id_ingreso = i.id_ingreso
			left join ingreso_transferencia it on it.id_ingreso = i.id_ingreso
		where i.id_ingreso = '".$_POST["accion_eliminar"]."' and i.id_ingreso_padre is not null;";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			if (!empty($result->num_rows))
			{
				$array = $result->fetch_all(MYSQLI_ASSOC);
				$totalARestar = 0;
				$idIngresoPadre = 0;
				foreach ($array as $row)
				{
					$totalARestar += $row["efectivo_monto"] ? $row["efectivo_monto"] : 0;
					$totalARestar += $row["transferencia_monto"] ? $row["transferencia_monto"] : 0;
					$totalARestar += $row["debito_monto"] ? $row["debito_monto"] : 0;
					$idIngresoPadre = $row["id_ingreso_padre"];
				}
				$sql = "update ingreso_deuda set monto_pagado = (monto_pagado - ".$totalARestar."), pagada = (case when monto > (monto_pagado - ".$totalARestar.") then 0 else 1 end) where id_ingreso = ".$idIngresoPadre.";";
				$result = $bd->mysql->query($sql);
			}
		}
		else
			unset($result);
		//Fin eliminar deuda
		$bd->eliminar_datos(1,$basedatos,"ingreso_debito","id_ingreso",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"ingreso_deuda","id_ingreso",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"ingreso_efectivo","id_ingreso",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"ingreso_transferencia","id_ingreso",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"ingreso","id_ingreso",$_POST["accion_eliminar"]);
		$sql = "select id_ingreso from ingreso where id_ingreso_padre = '".$_POST["accion_eliminar"]."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			$row = $result->fetch_all(MYSQLI_ASSOC);
			$result->free();
			if (is_array(($row)))
			{
				foreach ($row as $val)
				{
					$bd->eliminar_datos(1,$basedatos,"ingreso_debito","id_ingreso",$val["id_ingreso"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso_deuda","id_ingreso",$val["id_ingreso"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso_efectivo","id_ingreso",$val["id_ingreso"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso_transferencia","id_ingreso",$val["id_ingreso"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso","id_ingreso",$val["id_ingreso"]);
				}
			}
			unset($row);
		}
		else
			unset($result);
		$sql = "select id_productos_movimientos_relaciones, id_productos_movimientos, id_ingreso from productos_movimientos_relaciones where id_ingreso = '".$_POST["accion_eliminar"]."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			$row = $result->fetch_all(MYSQLI_ASSOC);
			$result->free();
			if (is_array(($row)))
			{
				foreach ($row as $val)
				{
					$bd->eliminar_datos(1,$basedatos,"productos_movimientos_relaciones","id_productos_movimientos_relaciones",$val["id_productos_movimientos_relaciones"]);
					$bd->eliminar_datos(1,$basedatos,"productos_movimientos","id_productos_movimientos",$val["id_productos_movimientos"]);
				}
			}
			unset($row);
		}
		else
			unset($result);
		return true;
	}

    function crear_sql_busqueda($bd)
	{
		if(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="especificar")
		{
			$where=" ";
			if(isset($_POST["chbfecha"]) and !empty($_POST["bfecha"]))
				$where.="i.fecha='".$_POST["bfecha"]."' AND ";
			if(isset($_POST["chbempleado_telf"]) and !empty($_POST["bempleado_telf"]))
				$where.="i.empleado_telf='".$_POST["bempleado_telf"]."' AND ";
			if (strlen($where) > 1) {
				$where[strlen($where)-1]=" ";
				$where[strlen($where)-2]=" ";
				$where[strlen($where)-3]=" ";
				$where[strlen($where)-4]=" ";
			}
			$where=trim($where);
			$sql="select i.id_ingreso, i.fecha, mi.motivo as 'tipo de trabajo', case when i.efectivo = 1 then iff.monto else '' end as 'efectivo', case when i.transferencia = 1 then it.monto else '' end as 'transferencia', case when i.transferencia = 1 then it.referencia else '' end as 'referencia', case when i.debito = 1 then id.monto else '' end as 'datáfono', case when i.deuda = 1 then idd.monto else '' end as 'deuda', case when i.deuda = 1 then idd.monto_pagado else '' end as 'pagado', (ifnull(iff.monto,0) + ifnull(it.monto,0) + ifnull(id.monto,0) + ifnull(idd.monto_pagado,0)) as 'total', concat(e.nombre,' ',e.apellido) as 'empleado', case when c.nombre is null then '' else concat(c.nombre,' ',c.apellido) end as 'cliente', case when i.observacion is not null then i.observacion else '' end as 'observacion' from ingreso i inner join empleado e on e.empleado_telf = i.empleado_telf inner join motivo_ingreso mi on mi.id_motivo_ingreso = i.id_motivo_ingreso left join ingreso_debito id on i.id_ingreso = id.id_ingreso left join ingreso_transferencia it on it.id_ingreso = i.id_ingreso left join ingreso_efectivo iff on iff.id_ingreso = i.id_ingreso left join ingreso_deuda idd on idd.id_ingreso = i.id_ingreso left join cliente c on i.cliente_telf = c.telf WHERE ".$where." ORDER BY i.fecha_num ASC;";
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="listar")
		{
			$sql="select i.id_ingreso, i.fecha, mi.motivo as 'tipo de trabajo', case when i.efectivo = 1 then iff.monto else '' end as 'efectivo', case when i.transferencia = 1 then it.monto else '' end as 'transferencia', case when i.transferencia = 1 then it.referencia else '' end as 'referencia', case when i.debito = 1 then id.monto else '' end as 'datáfono', case when i.deuda = 1 then idd.monto else '' end as 'deuda', case when i.deuda = 1 then idd.monto_pagado else '' end as 'pagado', (ifnull(iff.monto,0) + ifnull(it.monto,0) + ifnull(id.monto,0) + ifnull(idd.monto_pagado,0)) as 'total', concat(e.nombre,' ',e.apellido) as 'empleado', case when c.nombre is null then '' else concat(c.nombre,' ',c.apellido) end as 'cliente', case when i.observacion is not null then i.observacion else '' end as 'observacion' from ingreso i inner join empleado e on e.empleado_telf = i.empleado_telf inner join motivo_ingreso mi on mi.id_motivo_ingreso = i.id_motivo_ingreso left join ingreso_debito id on i.id_ingreso = id.id_ingreso left join ingreso_transferencia it on it.id_ingreso = i.id_ingreso left join ingreso_efectivo iff on iff.id_ingreso = i.id_ingreso left join ingreso_deuda idd on idd.id_ingreso = i.id_ingreso left join cliente c on i.cliente_telf = c.telf ORDER BY i.fecha_num ASC;";
		}
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			$n = $result->num_rows;
			if (!empty($n))
			{
				unset($n);
				return $result;
			}
			else
			{
				unset($n);
				$result->free();
				return false;
			}
			$result->free();
		}
		else
			unset($result);
		return false;
	}

    function mostrar_busqueda($result,$colespeciales,$colocultar,$bd,$pag=1,$cantxpag=20)
	{
		$fil=$result->num_rows;
		$cantpag=$fil/$cantxpag;
		$cantpag=ceil($cantpag);
		if($pag>$cantpag)
			$pag=$cantpag;
		for($i=1,$ini=0,$fin=$cantxpag-1;$i<$pag;$i++,$ini+=$cantxpag,$fin+=$cantxpag);
		?>
		<div class="w3-container">
			<p>
			<form id='form_tabla' name='form_tabla' method='post'>
				<div class="w3-row-padding">
					<div class="w3-third">
						<label for="pag">P&aacute;gina:</label>
						<select class="w3-select w3-border" id="pag" name="pag" onchange="if($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista();">
						<?php
							for($i=1;$i<=$cantpag;$i++)
							{
								if($i==$pag)
									echo"<option value='$i' selected>$i</option>";
								else
									echo"<option value='$i'>$i</option>";
							}
						?>
						</select>
					</div>
					<div class="w3-third">
						<label for="cantxpag">Cantidad de Registros por P&aacute;gina:</label>
						<input type="text" class="w3-input w3-border" id="cantxpag" name="cantxpag" onKeyPress="return NumCheck2(event, this)" value="<?php echo $cantxpag;?>">
					</div>
					<div class="w3-third">
						<br>
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="if($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista();">
					</div>
				</div>
				<div style="overflow-x: scroll; overflow-x: auto;">
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<tr class="w3-dulcevanidad">
							<th class='tableheadresul' align='center'>&nbsp;</th>
							<?php
								$col = $result->fetch_fields();
								foreach($col as $valor)
								{
									if (!in_array($valor->name, $colocultar))
									{
										?>
										<th class='tableheadresul' align='center' nowarp>
											<?php
												echo ucwords($valor->name);
											?>
										</th>
										<?php
									}
								}
								unset($col);
							?>
						</tr>
					</thead>
					<tbody>
						<?php
							if($pag>1) for($i=0;$i<$pag-1;$i++) for($j=0;$j<$cantxpag;$j++) $row=$result->fetch_array();
							echo"<input type='hidden' id='accion_eliminar' name='accion_eliminar'>";
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row=$result->fetch_array();
								$num_col=count($row)/2;
								echo"<td align='center' nowrap>";
									echo"<i class='icon-cross2 icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' ";
									?>
									onclick="document.getElementById('accion_eliminar').value='<?php echo $row[0]; ?>';return confirmar_eliminar('<?php echo $row[0]; ?>');"
									<?php
									echo"'></i>";
								echo"</td>";
								for($j=1;$j<$num_col;$j++)
								{
									if(!empty($row[$j]) or $row[$j] == 0)
									{
										$especial=false;
										foreach($colespeciales as $indice=>$valor) 
										{
											if($indice==$j)
											{
												$especial=true;
												if(is_callable($valor))
												{
													echo"<td align='center' nowrap>";
													echo $valor($row[$j],$bd);
													echo"</td>";
												}
												else
												{
													echo"<td align='center'>";
													echo $row[$j];
													echo"</td>";
												}
											}
										}
										if(!$especial)
										{
											echo"<td align='center'>";
											echo $row[$j];
											echo"</td>";
										}
									}
									else
									{
										echo"<td align='center'>";
										echo"&nbsp;";
										echo"</td>";
									}
										
								}
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				</div>
				<?php
					if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
					if(isset($_POST["chbfecha"])) echo"<input type='hidden' id='chbfecha' name='chbfecha' value='".$_POST["chbfecha"]."'>";
					if(isset($_POST["bfecha"])) echo"<input type='hidden' id='bfecha' name='bfecha' value='".$_POST["bfecha"]."'>";
				?>
			</form>
			</p>
		</div>
		<?php
	}

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        if(isset($_POST["accion_eliminar"]) and !empty($_POST["accion_eliminar"]))
		{
			if(eliminar_ingreso($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","INGRESO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIINAR EL INGRESO").set('label', 'Aceptar');
				</script>
				<?php
			}
        }
        else
        {
            if($result=crear_sql_busqueda($bd))
			{
				$colespeciales=array();
				$colocultar=array();
				if(isset($_POST["pag"]) and !empty($_POST["pag"]))
					$pag=$_POST["pag"];
				if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
					$cantxpag=$_POST["cantxpag"];
				$colocultar[0]="id_ingreso";
				$colespeciales[1] = "Fecha";
				$colespeciales[3] = "Efectivo";
				$colespeciales[4] = "Transferencia";
				$colespeciales[6] = "Datáfono";
				$colespeciales[7] = "Deuda";
				$colespeciales[8] = "Pagado";
				$colespeciales[9] = "Total";
				if(isset($pag) and isset($cantxpag))
					mostrar_busqueda($result,$colespeciales,$colocultar,$bd,$pag,$cantxpag);
				else
					mostrar_busqueda($result,$colespeciales,$colocultar,$bd);
            }
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","LA CONSULTA NO GENERO RESULTADOS").set('label', 'Aceptar');
				</script>
				<?php
			}
        }
    }
?>