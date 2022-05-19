<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function eliminar_venta($bd)
	{
		global $basedatos;
		$bd->eliminar_datos(1,$basedatos,"venta_debito","id_venta",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"venta_deuda","id_venta",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"venta_efectivo","id_venta",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"venta_transferencia","id_venta",$_POST["accion_eliminar"]);
		$bd->eliminar_datos(1,$basedatos,"venta","id_venta",$_POST["accion_eliminar"]);
		$sql = "select id_venta from venta where id_venta_padre = '".$_POST["accion_eliminar"]."';";
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
					$bd->eliminar_datos(1,$basedatos,"venta_debito","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"venta_deuda","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"venta_efectivo","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"venta_transferencia","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"venta","id_venta",$val["id_venta"]);
				}
			}
			unset($row);
		}
		else
			unset($result);
		return true;
	}

	function mostrar_busqueda($result,$colespeciales,$colocultar,$bd,$pag=1,$cantxpag=20)
	{
		$fil = $result->num_rows;
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
						<select class="w3-select w3-border" id="pag" name="pag" onchange="document.getElementById('accion_eliminar').value='';return enviardatos_lista();">
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
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="document.getElementById('accion_eliminar').value='';return enviardatos_lista();">
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
							if($pag>1) for($i=0;$i<$pag-1;$i++) for($j=0;$j<$cantxpag;$j++) $row = $result->fetch_array();
							echo"<input type='hidden' id='accion_eliminar' name='accion_eliminar'>";
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row = $result->fetch_array();
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
									if(!empty($row[$j]))
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
					if(isset($_POST["bfecha"])) echo"<input type='hidden' id='bfecha' name='bfecha' value='".$_POST["bfecha"]."'>";
				?>
			</form>
			</p>
		</div>
		<?php
	}

	function crear_sql_busqueda($bd)
	{
		if(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="fecha")
		{
			$where=" ";
			if(isset($_POST["bfecha"]) and !empty($_POST["bfecha"]))
				$where="fecha='".$_POST["bfecha"]."' ";
			$sql="select v.id_venta, v.fecha, v.motivo as 'venta', case when v.efectivo = 1 then vff.monto else '' end as 'efectivo', case when v.transferencia = 1 then vt.monto else '' end as 'transferencia', case when v.transferencia = 1 then vt.referencia else '' end as 'referencia', case when v.debito = 1 then vd.monto else '' end as 'datáfono', case when v.deuda = 1 then vdd.monto else '' end as 'deuda', case when v.deuda = 1 then vdd.monto_pagado else '' end as 'pagado', (ifnull(vff.monto,0) + ifnull(vt.monto,0) + ifnull(vd.monto,0) + ifnull(vdd.monto_pagado,0)) as 'total', case when c.nombre is null then '' else concat(c.nombre,' ',c.apellido) end as 'cliente' from venta v left join venta_debito vd on v.id_venta = vd.id_venta left join venta_transferencia vt on vt.id_venta = v.id_venta left join venta_efectivo vff on vff.id_venta = v.id_venta left join venta_deuda vdd on vdd.id_venta = v.id_venta left join cliente c on v.cliente_telf = c.telf where v.fecha = '".$_POST["bfecha"]."' and v.id_venta_padre is null ORDER BY v.fecha_num ASC;";
			unset($where);
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="todo")
		{
			$sql="select v.id_venta, v.fecha, v.motivo as 'venta', case when v.efectivo = 1 then vff.monto else '' end as 'efectivo', case when v.transferencia = 1 then vt.monto else '' end as 'transferencia', case when v.transferencia = 1 then vt.referencia else '' end as 'referencia', case when v.debito = 1 then vd.monto else '' end as 'datáfono', case when v.deuda = 1 then vdd.monto else '' end as 'deuda', case when v.deuda = 1 then vdd.monto_pagado else '' end as 'pagado', (ifnull(vff.monto,0) + ifnull(vt.monto,0) + ifnull(vd.monto,0) + ifnull(vdd.monto_pagado,0)) as 'total', case when c.nombre is null then '' else concat(c.nombre,' ',c.apellido) end as 'cliente' from venta v left join venta_debito vd on v.id_venta = vd.id_venta left join venta_transferencia vt on vt.id_venta = v.id_venta left join venta_efectivo vff on vff.id_venta = v.id_venta left join venta_deuda vdd on vdd.id_venta = v.id_venta left join cliente c on v.cliente_telf = c.telf where v.id_venta_padre is null ORDER BY v.fecha_num ASC;";
		}
		$result = $bd->mysql->query($sql);
		unset($sql);
		if($result)
		{
			$n = $result->num_rows;
			if(!empty($n))
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
		}
		else
		{
			unset($result);
			return false;
		}
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(isset($_POST["accion_eliminar"]) and !empty($_POST["accion_eliminar"]))
		{
			if(eliminar_venta($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","VENTA ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIMINAR LA VENTA").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		if($result=crear_sql_busqueda($bd))
		{
			//$colespeciales=array(1=>"fecha_dd_mm_yy");
			$colespeciales=array();
			$colocultar = array();
			if(isset($_POST["pag"]) and !empty($_POST["pag"]))
				$pag=$_POST["pag"];
			if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
				$cantxpag=$_POST["cantxpag"];
			$colocultar[0] = "id_venta";
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
?>