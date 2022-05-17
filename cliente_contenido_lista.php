<script type="text/javascript">
	
	$(document).ready(function(){
		$(function() {
			$(".abono-fecha").datepicker({
				dateFormat:"dd-mm-yy",
				dayNamesMin:[ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
				monthNames:[ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
			});
		});
	});

</script>
<?php
	session_start();
	require("head.php");
	require("config.php");
	require("funciones_generales.php");
	require("librerias/basedatos.php");

	function guardar_modificar($bd)
	{
		global $basedatos;
		if($bd->actualizar_datos(1,1,$basedatos,"ingreso","cliente_telf",$_POST["otelf"],"cliente_telf",$_POST["otelf"],$_POST["mtelf"]))
		{
			if($bd->actualizar_datos(1,1,$basedatos,"venta","cliente_telf",$_POST["otelf"],"cliente_telf",$_POST["otelf"],$_POST["mtelf"]))
			{
				$oespecial = $_POST["oespecial"];
				$mespecial = isset($_POST["mespecial"]) ? 1 : 0;
				if($bd->actualizar_datos(1,5,$basedatos,"cliente","telf",$_POST["otelf"],"telf",$_POST["otelf"],$_POST["mtelf"],"nombre",$_POST["onombre"],$_POST["mnombre"],"apellido",$_POST["oapellido"],$_POST["mapellido"],"alias",$_POST["oalias"],$_POST["malias"],"especial",$oespecial,$mespecial))
				{
					foreach($_POST as $index => $value)
					{
						$id_ingreso = "";
						$abono_efectivo = "";
						$abono_transferencia = "";
						$abono_referencia = "";
						$abono_datafono = "";
						$pagado = 0;
						$monto_pagado = 0;
						if (strstr($index, 'id_ingreso_'))
						{
							$id_ingreso = $value;
							if (isset($_POST["abono_efectivo_".$id_ingreso]))
							{
								$abono_efectivo = str_replace(",","",$_POST["abono_efectivo_".$id_ingreso]);
								$abono_transferencia = str_replace(",","",$_POST["abono_transferencia_".$id_ingreso]);
								$abono_referencia = $_POST["abono_referencia_".$id_ingreso];
								$abono_datafono = str_replace(",","",$_POST["abono_datafono_".$id_ingreso]);
								$id_motivo_ingreso = $_POST["id_motivo_ingreso_".$id_ingreso];
								$fecha = $_POST["abono_fecha_".$id_ingreso];
								$fecha_num = strtotime($fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1]);
								$efectivo = 0;
								$transferencia = 0;
								$debito = 0;
								$pagada = 0;
								$empleado_telf = "";
								if (!empty($abono_efectivo))
									$efectivo = 1;
								if (!empty($abono_transferencia))
									$transferencia = 1;
								if (!empty($abono_datafono))
									$debito = 1;
								$sql = "select empleado_telf from ingreso where id_ingreso = '".$id_ingreso."';";
								$result = $bd->mysql->query($sql);
								unset($sql);
								if ($result)
								{
									$row = $result->fetch_row();
									$result->free();
									$empleado_telf = $row[0];
								}
								else
									unset($result);
								if ($efectivo == 1 or $transferencia == 1 or $debito == 1)
									$bd->insertar_datos(11,$basedatos,"ingreso","id_ingreso_padre","id_motivo_ingreso","cliente_telf","fecha","fecha_num","efectivo","transferencia","debito","deuda","empleado_telf","login",$id_ingreso,$id_motivo_ingreso,$_POST["mtelf"],$fecha,$fecha_num,$efectivo,$transferencia,$debito,0,$empleado_telf,$_SESSION["login"]);
								$id_ingreso_nuevo = $bd->ultimo_result;
								if ($efectivo == 1)
									$bd->insertar_datos(2,$basedatos,"ingreso_efectivo","id_ingreso","monto",$id_ingreso_nuevo,$abono_efectivo);
								if ($transferencia == 1)
									$bd->insertar_datos(3,$basedatos,"ingreso_transferencia","id_ingreso","monto","referencia",$id_ingreso_nuevo,$abono_transferencia,$abono_referencia);
								if ($debito == 1)
									$bd->insertar_datos(2,$basedatos,"ingreso_debito","id_ingreso","monto",$id_ingreso_nuevo,$abono_datafono);
								$sql = "select id.monto, id.monto_pagado, id.pagada from ingreso i inner join ingreso_deuda id on i.id_ingreso = id.id_ingreso where i.id_ingreso = '".$id_ingreso."';";
								$result = $bd->mysql->query($sql);
								unset($sql);
								if ($result)
								{
									$rows = $result->fetch_all(MYSQLI_ASSOC);
									$result->free();
									$monto_abonado = 0;
									foreach ($rows as $row)
									{
										if (!empty($abono_efectivo))
											$monto_abonado += $abono_efectivo;
										if (!empty($abono_transferencia))
											$monto_abonado += $abono_transferencia;
										if (!empty($abono_datafono))
											$monto_abonado += $abono_datafono;
										$monto_abonado += $row["monto_pagado"];
										if ($monto_abonado >= $row["monto"])
											$pagada = 1;
									}
									unset($rows);
								}
								else
									unset($result);
								$bd->actualizar_datos(1,2,$basedatos,"ingreso_deuda","id_ingreso",$id_ingreso,"monto_pagado","",$monto_abonado,"pagada","",$pagada);
							}
						}
						if (strstr($index, 'id_ingreso_v_'))
						{
							$id_ingreso = $value;
							if (isset($_POST["abono_efectivo_v_".$id_ingreso]))
							{
								$abono_efectivo = str_replace(",","",$_POST["abono_efectivo_v_".$id_ingreso]);
								$abono_transferencia = str_replace(",","",$_POST["abono_transferencia_v_".$id_ingreso]);
								$abono_referencia = $_POST["abono_referencia_v_".$id_ingreso];
								$abono_datafono = str_replace(",","",$_POST["abono_datafono_v_".$id_ingreso]);
								$motivo_ingreso = $_POST["motivo_ingreso_v_".$id_ingreso];
								$fecha = $_POST["abono_fecha_v_".$id_ingreso];
								$fecha_num = time();
								$efectivo = 0;
								$transferencia = 0;
								$debito = 0;
								$pagada = 0;
								$empleado_telf = "";
								if (!empty($abono_efectivo))
									$efectivo = 1;
								if (!empty($abono_transferencia))
									$transferencia = 1;
								if (!empty($abono_datafono))
									$debito = 1;
								if ($efectivo == 1 or $transferencia == 1 or $debito == 1)
									$bd->insertar_datos(10,$basedatos,"venta","id_venta_padre","motivo","cliente_telf","fecha","fecha_num","efectivo","transferencia","debito","deuda","login",$id_ingreso,$motivo_ingreso,$_POST["mtelf"],$fecha,$fecha_num,$efectivo,$transferencia,$debito,0,$_SESSION["login"]);
								$id_ingreso_nuevo = $bd->ultimo_result;
								if ($efectivo == 1)
									$bd->insertar_datos(2,$basedatos,"venta_efectivo","id_venta","monto",$id_ingreso_nuevo,$abono_efectivo);
								if ($transferencia == 1)
									$bd->insertar_datos(3,$basedatos,"venta_transferencia","id_venta","monto","referencia",$id_ingreso_nuevo,$abono_transferencia,$abono_referencia);
								if ($debito == 1)
									$bd->insertar_datos(2,$basedatos,"venta_debito","id_venta","monto",$id_ingreso_nuevo,$abono_datafono);
								$sql = "select id.monto, id.monto_pagado, id.pagada from venta i inner join venta_deuda id on i.id_venta = id.id_venta where i.id_venta = '".$id_ingreso."';";
								$result = $bd->mysql->query($sql);
								unset($sql);
								if ($result)
								{
									$rows = $result->fetch_all(MYSQLI_ASSOC);
									$result->free();
									$monto_abonado = 0;
									foreach ($rows as $row)
									{
										if (!empty($abono_efectivo))
											$monto_abonado += $abono_efectivo;
										if (!empty($abono_transferencia))
											$monto_abonado += $abono_transferencia;
										if (!empty($abono_datafono))
											$monto_abonado += $abono_datafono;
										$monto_abonado += $row["monto_pagado"];
										if ($monto_abonado >= $row["monto"])
											$pagada = 1;
									}
									unset($rows);
								}
								else
									unset($result);
								$bd->actualizar_datos(1,2,$basedatos,"venta_deuda","id_venta",$id_ingreso,"monto_pagado","",$monto_abonado,"pagada","",$pagada);
							}						
						}
					}
					return true;
				}
				else
					return false;
			}
			else
				return false;
		}
		else
			return false;
	}

	function eliminar_cliente($bd)
	{
		global $basedatos;
		$sql = "select id_ingreso from ingreso where cliente_telf = '".$_POST["accion_eliminar"]."';";
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
		$sql = "select id_venta from venta where cliente_telf = '".$_POST["accion_eliminar"]."';";
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
					$bd->eliminar_datos(1,$basedatos,"ingreso_debito","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso_deuda","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso_efectivo","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso_transferencia","id_venta",$val["id_venta"]);
					$bd->eliminar_datos(1,$basedatos,"ingreso","id_venta",$val["id_venta"]);
				}
			}
			unset($row);
		}
		else
			unset($result);
		if($bd->eliminar_datos(1,$basedatos,"cliente","telf",$_POST["accion_eliminar"]))
			return true;
		else
			return false;
	}

	function exite($bd)
	{
		if($bd->existe(1,"cliente","telf",$_POST["mtelf"]))
			return true;
		else
			return false;
	}

	function formulario_modificar($bd)
	{
		$sql="SELECT nombre, apellido, alias, telf, especial FROM cliente WHERE telf='".$_POST["accion_modificar"]."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if($result)
		{
			$row = $result->fetch_all(MYSQLI_ASSOC);
			?>
			<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fmodificar" name="fmodificar" method="post">
				<input type="hidden" id="guardar_modificar" name="guardar_modificar" value="">
				<h2 class="w3-center">Cliente</h2>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="mtelf"><i class=" icon-phone" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="otelf" name="otelf" value="<?php echo $row[0]['telf']; ?>">
						<input class="w3-input w3-border" id="mtelf" name="mtelf" type="text" placeholder="Tel&eacute;fono" maxlength="11" onkeypress="return NumCheck3(event, this)" tabindex="5" value="<?php echo $row[0]['telf']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="mnombre"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="onombre" name="onombre" value="<?php echo $row[0]['nombre']; ?>">
						<input class="w3-input w3-border" id="mnombre" name="mnombre" type="text" placeholder="Nombre" maxlength="30" tabindex="2" value="<?php echo $row[0]['nombre']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="mapellido"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="oapellido" name="oapellido" value="<?php echo $row[0]['apellido']; ?>">
						<input class="w3-input w3-border" id="mapellido" name="mapellido" type="text" placeholder="Apellido" maxlength="30" tabindex="3" value="<?php echo $row[0]['apellido']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="malias"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="oalias" name="oalias" value="<?php echo $row[0]['alias']; ?>">
						<input class="w3-input w3-border" id="malias" name="malias" type="text" placeholder="Alias" maxlength="30" tabindex="4" value="<?php echo $row[0]['alias']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<label>
						<div class="w3-col" style="width:50px">
							<?php
								echo "<input type=\"hidden\" id=\"oespecial\" name=\"oespecial\" value=\"".$row[0]["especial"]."\">";
								echo "<input class=\"w3-check\" type=\"checkbox\" id=\"mespecial\" name=\"mespecial\" value=\"1\" ";
								if ($row[0]["especial"] == 1)
									echo "checked";
								echo ">";
							?>
						</div>
						<div class="w3-rest">
							Especial
						</div>
					</label>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:300px"><label for="">
						<?php
							$totalDeuda = 0;
							$sql2 = "select (sum(id.monto) - sum(id.monto_pagado)) as total FROM ingreso i inner join cliente c on i.cliente_telf = c.telf inner join ingreso_deuda id on i.id_ingreso = id.id_ingreso where c.telf = '".$row[0]['telf']."';";
							$result2 = $bd->mysql->query($sql2);
							$tieneDeuda = false;
							unset($sql2);
							if($result2)
							{
								$row2 = $result2->fetch_all(MYSQLI_ASSOC);
								if ($row2[0]["total"] != null)
									$tieneDeuda = true;
								$result2->free();
								$totalDeuda += $row2[0]["total"];
							}
							else
								unset($result2);
							$sql22 = "select (sum(vd.monto) - sum(vd.monto_pagado)) as total FROM venta v inner join cliente c on v.cliente_telf = c.telf inner join venta_deuda vd on v.id_venta = vd.id_venta where c.telf = '".$row[0]['telf']."';";
							$result22 = $bd->mysql->query($sql22);
							unset($sql22);
							if ($result22)
							{
								$row22 = $result22->fetch_all(MYSQLI_ASSOC);
								if ($row22[0]["total"] != null)
									$tieneDeuda = true;
								$result22->free();
								$totalDeuda += $row22[0]["total"];
							}
							else
								unset($result22);
							if ($tieneDeuda)
								echo "Deuda&nbsp;Total: ".money_format('%.2n', $totalDeuda);
							else
								echo "El cliente no posee deudas";
						?>
					</label></div>
				</div>
				<?php
					if ($tieneDeuda)
					{
				?>
				<div style="overflow-x: scroll; overflow-x: auto;">
				<div class="w3-row w3-section">
					<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
						<thead>
							<tr class="w3-dulcevanidad">
								<th class='tableheadresul' align='center' nowarp>Fecha</th>
								<th class='tableheadresul' align='center' nowarp>Tipo De Trabajo / Venta</th>
								<th class='tableheadresul' align='center' nowarp>Realizado Por</th>
								<th class='tableheadresul' align='center' nowarp>Debe</th>
								<th class='tableheadresul' align='center' nowarp>Pagado</th>
								<th class='tableheadresul' align='center' nowarp>Deuda total</th>
								<th class='tableheadresul' align='center' nowarp>Abono</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql3 = "select i.fecha_num, i.id_ingreso, i.fecha, mi.id_motivo_ingreso, mi.motivo as 'tipo_de_trabajo', concat(e.nombre,' ',e.apellido) as 'realizado_por', id.monto as 'debe', id.monto_pagado as 'pagado', (id.monto - ifnull(id.monto_pagado,0)) as 'total', id.pagada, '0' as por_venta FROM empleado e inner join ingreso i on e.empleado_telf = i.empleado_telf inner join ingreso_deuda id on i.id_ingreso = id.id_ingreso inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso where i.cliente_telf = '".$row[0]["telf"]."' union all select v.fecha_num, v.id_venta, v.fecha, '' as id_motivo_ingreso, v.motivo as 'tipo_de_trabajo', '' as 'realizado_por', vd.monto as 'debe', vd.monto_pagado as 'pagado', (vd.monto - ifnull(vd.monto_pagado,0)) as 'total', vd.pagada, '1' as por_venta FROM venta v inner join venta_deuda vd on v.id_venta = vd.id_venta where v.cliente_telf = '".$row[0]["telf"]."' order by fecha_num asc;";
								$result3 = $bd->mysql->query($sql3);
								unset($sql3);
								if($result3)
								{
									$row3 = $result3->fetch_all(MYSQLI_ASSOC);
									$result3->free();
									foreach ($row3 as $val)
									{
										echo"<tr>";
										echo"<td align='center' nowrap>";
										echo $val['fecha'];
										echo"</td>";
										echo"<td align='center'>";
										echo $val['tipo_de_trabajo'];
										echo"</td>";
										echo"<td align='center'>";
										echo $val['realizado_por'];
										echo"</td>";
										echo"<td align='center' nowrap>";
										echo money_format('%.2n', $val['debe']);
										echo"</td>";
										echo"<td align='center' nowrap>";
										echo money_format('%.2n', $val['pagado']);
										echo"</td>";
										echo"<td align='center' nowrap>";
										echo money_format('%.2n', $val['total']);
										echo"</td>";
										echo"<td align='center'>";
											if ($val['pagada'] == 0) 
											{
												$hoy=date("d-m-Y",time());
												if ($val["por_venta"] == 1)
												{
													echo "<input type='hidden' id='motivo_ingreso_v_".$val["id_ingreso"]."' name='motivo_ingreso_v_".$val["id_ingreso"]."' value='".$val["tipo_de_trabajo"]."'>";
													echo "<input type='hidden' class='id-ingreso-v' id='id_ingreso_v_".$val["id_ingreso"]."' name='id_ingreso_v_".$val["id_ingreso"]."' value='".$val["id_ingreso"]."' data-por-venta='".$val['por_venta']."' data-total='".$val['total']."'>";
													echo "<input class='w3-input w3-border abono-fecha abono-fecha-".$val["id_ingreso"]."' placeholder='dd-mm-aaaa' type='text' id='abono_fecha_v_".$val["id_ingreso"]."' name='abono_fecha_v_".$val["id_ingreso"]."' value='".$hoy."' style='min-width: 20em;'><br>";
													echo "<input class='w3-input w3-border abono-efectivo-".$val["id_ingreso"]."' placeholder='Efectivo' type='text' inputmode='decimal' data-type='currency' id='abono_efectivo_v_".$val["id_ingreso"]."' name='abono_efectivo_v_".$val["id_ingreso"]."' data-id-ingreso='".$val["id_ingreso"]."' min=1 style='min-width: 20em;'><br>";
													echo "<input class='w3-input w3-border abono-transferencia-".$val["id_ingreso"]."' placeholder='Transferencia' type='text' inputmode='decimal' data-type='currency' id='abono_transferencia_v_".$val["id_ingreso"]."' name='abono_transferencia_v_".$val["id_ingreso"]."' data-id-ingreso='".$val["id_ingreso"]."' min=1 style='min-width: 20em;'>";
													echo "<input class='w3-input w3-border abono-referencia-".$val["id_ingreso"]."' placeholder='Referencia' type='text' id='abono_referencia_v_".$val["id_ingreso"]."' name='abono_referencia_v_".$val["id_ingreso"]."' style='min-width: 20em;'><br>";
													echo "<input class='w3-input w3-border abono-datafono-".$val["id_ingreso"]."' placeholder='Datáfono' type='text' inputmode='decimal' data-type='currency' id='abono_datafono_v_".$val["id_ingreso"]."' name='abono_datafono_v_".$val["id_ingreso"]."' data-id-ingreso='".$val["id_ingreso"]."' min=1 style='min-width: 20em;'>";
												}
												else
												{
													echo "<input type='hidden' id='id_motivo_ingreso_".$val["id_ingreso"]."' name='id_motivo_ingreso_".$val["id_ingreso"]."' value='".$val["id_motivo_ingreso"]."'>";
													echo "<input type='hidden' class='id-ingreso' id='id_ingreso_".$val["id_ingreso"]."' name='id_ingreso_".$val["id_ingreso"]."' value='".$val["id_ingreso"]."' data-por-venta='".$val['por_venta']."' data-total='".$val['total']."'>";
													echo "<input class='w3-input w3-border abono-fecha abono-fecha-".$val["id_ingreso"]."' placeholder='dd-mm-aaaa' type='text' id='abono_fecha_".$val["id_ingreso"]."' name='abono_fecha_".$val["id_ingreso"]."' value='".$hoy."' size='100px' style='min-width: 20em;'><br>";
													echo "<input class='w3-input w3-border abono-efectivo-".$val["id_ingreso"]."' placeholder='Efectivo' type='text' inputmode='decimal' data-type='currency' id='abono_efectivo_".$val["id_ingreso"]."' name='abono_efectivo_".$val["id_ingreso"]."' data-id-ingreso='".$val["id_ingreso"]."' min=1 style='min-width: 20em;'><br>";
													echo "<input class='w3-input w3-border abono-transferencia-".$val["id_ingreso"]."' placeholder='Transferencia' type=' text'inputmode='decimal' data-type='currency' id='abono_transferencia_".$val["id_ingreso"]."' name='abono_transferencia_".$val["id_ingreso"]."' data-id-ingreso='".$val["id_ingreso"]."' min=1 style='min-width: 20em;'>";
													echo "<input class='w3-input w3-border abono-referencia-".$val["id_ingreso"]."' placeholder='Referencia' type='text' id='abono_referencia_".$val["id_ingreso"]."' name='abono_referencia_".$val["id_ingreso"]."' style='min-width: 20em;'><br>";
													echo "<input class='w3-input w3-border abono-datafono-".$val["id_ingreso"]."' placeholder='Datáfono' type='text' inputmode='decimal' data-type='currency' id='abono_datafono_".$val["id_ingreso"]."' name='abono_datafono_".$val["id_ingreso"]."' data-id-ingreso='".$val["id_ingreso"]."' min=1 style='min-width: 20em;'>";
												}
											}
											else
												echo "&nbsp;";
										echo"</td>";
										echo"</tr>";
									}
								}
								else
									unset($result3);
							?>
						</tbody>
					</table>
				</div>
				</div>
				<?php
					}
				?>
				<div class="w3-row w3-section">
					<p>
					<div class="w3-half">
						<input type="button" class="w3-button w3-block w3-red" onclick="return enviardatos_modificar();" value="Cancelar">
					</div>
					<div class="w3-half">
						<input type="button" class="w3-button w3-block w3-green" onclick="return confirmar_modificar();" value="Guardar">
					</div>
					</p>
				</div>
				<?php
					if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
					if(isset($_POST["chbtelf"])) echo"<input type='hidden' id='chbtelf' name='chbtelf' value='".$_POST["chbtelf"]."'>";
					if(isset($_POST["btelf"])) echo"<input type='hidden' id='btelf' name='btelf' value='".$_POST["btelf"]."'>";
					if(isset($_POST["chbnombre"])) echo"<input type='hidden' id='chbnombre' name='chbnombre' value='".$_POST["chbnombre"]."'>";
					if(isset($_POST["bnombre"])) echo"<input type='hidden' id='bnombre' name='bnombre' value='".$_POST["bnombre"]."'>";
				?>
			</form>
			<?php
			$result->free();
		}
		else
			unset($result);
		?>
		<?php
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
						<select class="w3-select w3-border" id="pag" name="pag" onchange="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista();">
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
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista();">
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
							echo"<input type='hidden' id='accion_modificar' name='accion_modificar'>";
							$admin = usuario_admin();
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row=$result->fetch_array();
								$num_col=count($row)/2;
								echo"<td align='center' nowrap>";
									if ($admin and false) { //Se quita la opcion de eliminar
										echo"<i class='icon-cross2 icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' ";
										?>
										onclick="document.getElementById('accion_eliminar').value='<?php echo $row[0]; ?>';return confirmar_eliminar('<?php echo $row[0]; ?>');"
										<?php
										echo"'></i>";
										echo"&nbsp;&nbsp;";
									}
									echo"<i class='icon-pencil icon_table' id='editar_<?php echo $i; ?>' name='editar_<?php echo $i; ?>' alt='Modificar' title='Modificar' ";
									?>
									onclick="document.getElementById('accion_modificar').value='<?php echo $row[0]; ?>';return enviardatos_lista();"
									<?php
									echo"'></i>";
								echo"</td>";
								for($j=1;$j<$num_col;$j++)
								{
									echo"<td align='center'>";
									if(!empty($row[$j]))
									{
										$especial=false;
										foreach($colespeciales as $indice=>$valor) 
										{
											if($indice==$j)
											{
												$especial=true;
												if(is_callable($valor))
													echo $valor($row[$j],$bd);
												else
													echo $row[$j];
											}
										}
										if(!$especial)
											echo $row[$j];
									}
									else
										echo"&nbsp;";
									echo"</td>";
								}
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				</div>
				<?php
					if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
					if(isset($_POST["chbtelf"])) echo"<input type='hidden' id='chbtelf' name='chbtelf' value='".$_POST["chbtelf"]."'>";
					if(isset($_POST["btelf"])) echo"<input type='hidden' id='btelf' name='btelf' value='".$_POST["btelf"]."'>";
					if(isset($_POST["chbnombre"])) echo"<input type='hidden' id='chbnombre' name='chbnombre' value='".$_POST["chbnombre"]."'>";
					if(isset($_POST["bnombre"])) echo"<input type='hidden' id='bnombre' name='bnombre' value='".$_POST["bnombre"]."'>";
				?>
			</form>
			</p>
		</div>
		<?php
	}

	function crear_sql_busqueda($bd)
	{
		if(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="especificar")
		{
			$where=" ";
			if(isset($_POST["chbtelf"]) and !empty($_POST["btelf"]))
				$where.="telf LIKE '%".$_POST["btelf"]."%' AND ";
			if(isset($_POST["chbnombre"]) and !empty($_POST["bnombre"]))
				$where.="nombre LIKE '%".$_POST["bnombre"]."%' OR apellido LIKE '%".$_POST["bnombre"]."%' AND ";
			$where[strlen($where)-1]=" ";
			$where[strlen($where)-2]=" ";
			$where[strlen($where)-3]=" ";
			$where[strlen($where)-4]=" ";
			$where=trim($where);
			$sql="SELECT telf, telf AS teléfono, nombre, apellido, alias FROM cliente WHERE ".$where.";";
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="listar")
		{
			$sql="SELECT telf, telf AS teléfono, nombre, apellido, alias FROM cliente;";
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

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(isset($_POST["accion_eliminar"]) and !empty($_POST["accion_eliminar"]))
		{
			if(eliminar_cliente($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","CLIENTE ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIINAR EL CLIENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		if(isset($_POST["guardar_modificar"]) and !empty($_POST["guardar_modificar"]))
		{
			$valido=true;
			if($_POST["otelf"]!=$_POST["mtelf"])
			{
				if(exite($bd))
				{
					$valido=false;
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","EL CLIENTE "+<?php echo $_POST["mtelf"]; ?>+" YA SE ENCUENTRA REGISTRADO").set('label', 'Aceptar');
					</script>
					<?php
				}
			}
			if($valido)
			{
				if(guardar_modificar($bd))
				{
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","LOS DATOS SE GUARDARON SATISFACTORIAMENTE").set('label', 'Aceptar');
					</script>
					<?php
				}
				else
				{
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","ERROR AL MODIFICAR LOS DATOS").set('label', 'Aceptar');
					</script>
					<?php
				}
			}
			unset($valido);
		}
		if(isset($_POST["accion_modificar"]) and !empty($_POST["accion_modificar"]))
		{
			formulario_modificar($bd);
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
				$colocultar[0]="telf";
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