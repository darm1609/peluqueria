<script type="text/javascript">
	$(document).ready(function(){
		$(function() {
			$("#fecha").datepicker({
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
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function eliminar_vale_pago($bd)
	{
		global $basedatos;
		$valida = true;
		if (!$bd->eliminar_datos(1,$basedatos,"vale_pago_efectivo","id_vale_pago",$_POST["accion_eliminar"]))
			$valida = false;
		if ($valida)
			if(!$bd->eliminar_datos(1,$basedatos,"vale_pago_transferencia","id_vale_pago",$_POST["accion_eliminar"]))
				$valida = false;
		if ($valida)
			if(!$bd->eliminar_datos(1,$basedatos,"vale_pago","id_vale_pago",$_POST["accion_eliminar"]))
				$valida = false;
		return $valida;
	}

	function mostrar_busqueda2($result,$colespeciales,$colocultar,$bd,$pag=1,$cantxpag=20)
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
			<form id='form_tabla2' name='form_tabla2' method='post'>
				<div class="w3-row-padding">
					<div class="w3-third">
						<label for="pag2">P&aacute;gina:</label>
						<select class="w3-select w3-border" id="pag2" name="pag2" onchange="document.getElementById('accion_eliminar').value='';return enviardatos_mostrar();">
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
						<label for="cantxpag2">Cantidad de Registros por P&aacute;gina:</label>
						<input type="text" class="w3-input w3-border" id="cantxpag2" name="cantxpag2" onKeyPress="return NumCheck2(event, this)" value="<?php echo $cantxpag;?>">
					</div>
					<div class="w3-third">
						<br>
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="document.getElementById('accion_eliminar').value='';return enviardatos_mostrar();">
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
					if(isset($_POST["chbempleado_telf"])) echo"<input type='hidden' id='chbempleado_telf' name='chbempleado_telf' value='".$_POST["chbempleado_telf"]."'>";
					if(isset($_POST["bempleado_telf"])) echo"<input type='hidden' id='bempleado_telf' name='bempleado_telf' value='".$_POST["bempleado_telf"]."'>";
					if(isset($_POST["chbnombre"])) echo"<input type='hidden' id='chbnombre' name='chbnombre' value='".$_POST["chbnombre"]."'>";
					if(isset($_POST["bnombre"])) echo"<input type='hidden' id='bnombre' name='bnombre' value='".$_POST["bnombre"]."'>";
					if(isset($_POST["accion_mostrar"])) echo"<input type='hidden' id='accion_mostrar' name='accion_mostrar' value='".$_POST["accion_mostrar"]."'>";
				?>
			</form>
			</p>
		</div>
		<?php
	}

	function crear_sql_busqueda2($bd)
	{
		$sql = "select vp.id_vale_pago, vp.fecha, vp.vale_pago, case when vp.efectivo = 1 then vpe.monto else '' end as 'efectivo', case when vp.transferencia = 1 then vpt.monto else '' end as 'transferencia', case when vp.transferencia = 1 then vpt.referencia else '' end as 'referencia', ifnull(vpe.monto,0) + ifnull(vpt.monto,0) total, vp.comentario from empleado e inner join vale_pago vp on e.empleado_telf = vp.empleado_telf left join vale_pago_efectivo vpe on vp.id_vale_pago = vpe.id_vale_pago left join vale_pago_transferencia vpt on vp.id_vale_pago = vpt.id_vale_pago where e.empleado_telf = '".$_POST["accion_mostrar"]."' order by vp.fecha_num asc;";
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
			$result->free();
		}
		else
		{
			unset($result);
			return false;
		}
	}

	function mostrar_vale_pagos($bd)
	{
		if($result=crear_sql_busqueda2($bd))
		{
			//$colespeciales=array(1=>"fecha_dd_mm_yy");
			$colespeciales=array();
			$colocultar=array();
			if(isset($_POST["pag2"]) and !empty($_POST["pag2"]))
				$pag=$_POST["pag2"];
			if(isset($_POST["cantxpag2"]) and !empty($_POST["cantxpag2"]))
				$cantxpag=$_POST["cantxpag2"];
			$colocultar[0] = "id_vale_pago";
			$colespeciales[3] = "Efectivo";
			$colespeciales[4] = "Transferencia";
			$colespeciales[6] = "Total";
			if(isset($pag) and isset($cantxpag))
				mostrar_busqueda2($result,$colespeciales,$colocultar,$bd,$pag,$cantxpag);
			else
				mostrar_busqueda2($result,$colespeciales,$colocultar,$bd);
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

	function guardar_vale_pago($bd)
	{
		global $basedatos;
		$fecha = $_POST["fecha"];
		$fecha_num=strtotime($_POST["fecha"][6].$_POST["fecha"][7].$_POST["fecha"][8].$_POST["fecha"][9]."-".$_POST["fecha"][3].$_POST["fecha"][4]."-".$_POST["fecha"][0].$_POST["fecha"][1]);
		$efectivo = 0;
		$transferencia = 0;
		if (!empty($_POST["monto_transferencia"]))
			$transferencia = 1;
		if (!empty($_POST["monto_efectivo"]))
			$efectivo = 1;
		$monto_transferencia = str_replace(",","",$_POST["monto_transferencia"]);
		$monto_efectivo = str_replace(",","",$_POST["monto_efectivo"]);
		if($bd->insertar_datos(8,$basedatos,"vale_pago","empleado_telf","fecha","comentario","vale_pago","fecha_num","login","efectivo","transferencia",$_POST["empleado_telf"],$fecha,$_POST["comentario"],$_POST["vale_pago"],$fecha_num,$_SESSION["login"],$efectivo,$transferencia))
		{
			$insert_id = $bd->ultimo_result;
			$valido = false;
			if ($transferencia == 1)
			{
				if ($bd->insertar_datos(3,$basedatos,"vale_pago_transferencia","id_vale_pago","monto","referencia",$insert_id,$monto_transferencia,$_POST["referencia"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($efectivo == 1)
			{
				if ($bd->insertar_datos(2,$basedatos,"vale_pago_efectivo","id_vale_pago","monto",$insert_id,$monto_efectivo))
					$valido = true;
				else
					$valido = false;
			}
			if (!$valido) //Devolver todos los cambios
			{
				$bd->eliminar_datos(1,$basedatos,"vale_pago_transferencia","id_ingreso",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"vale_pago_efectivo","id_ingreso",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"vale_pago","id_ingreso",$insert_id);
				return false;
			}
			return true;
		}
		else
			return false;
	}

	function formulario_vale_pago($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fmodificar" name="fmodificar" method="post">
			<h2 class="w3-center">Vale&nbsp;/&nbsp;Pago</h2>
			<h4 class="w3-left"><?php echo nombre_empleado($_POST["accion_vale_pago"],$bd); ?></h4>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label><i class="icon-calendar" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<?php
						$hoy=date("d-m-Y",time());
					?>
					<input type="text" class="w3-input w3-border" id="fecha" name="fecha" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>">
				</div>
			</div>
			<div class="w3-row w3-section">
				<label>
					<input class="w3-radio" type="radio" id="vale_pago" name="vale_pago" value="vale" checked>
					Vale
				</label>
				<label>
					<input class="w3-radio" type="radio" id="vale_pago" name="vale_pago" value="pago">
					Pago
				</label>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="comentario"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="comentario" name="comentario" type="text" placeholder="Comentario">
				</div>
			</div>
			<label for="monto_transferencia"><b>Transferencia</b></label>
			<div class="w3-row">
				<div class="w3-col s6">
					<input type="text" class="w3-input w3-border" inputmode="decimal" data-type="currency" id="monto_transferencia" name="monto_transferencia" placeholder="Monto" min=1>
				</div>
				<div class="w3-col s6">
					<input type="text" class="w3-input w3-border" id="referencia" name="referencia" placeholder="Referencia">
				</div>
			</div>
			<label for="monto_efectivo"><b>Efectivo</b></label>
			<div class="w3-row">
				<div class="w3-rest">
					<input type="text" class="w3-input w3-border" inputmode="decimal" data-type="currency" id="monto_efectivo" name="monto_efectivo" placeholder="Monto" min=1>
				</div>
			</div>
			<div class="w3-row w3-section">
				<p>
				<div class="w3-half">
					<input type="button" class="w3-button w3-block w3-red" onclick="return enviardatos_vale_pago();" value="Cancelar">
				</div>
				<div class="w3-half">
					<input type="button" class="w3-button w3-block w3-green" onclick="submit_vale_pago();" value="Guardar">
				</div>
				</p>
			</div>
			<input type="hidden" id="vale_pago_correcto" name="vale_pago_correcto">
			<?php
				if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
				if(isset($_POST["chbempleado_telf"])) echo"<input type='hidden' id='chbempleado_telf' name='chbempleado_telf' value='".$_POST["chbempleado_telf"]."'>";
				if(isset($_POST["bempleado_telf"])) echo"<input type='hidden' id='bempleado_telf' name='bempleado_telf' value='".$_POST["bempleado_telf"]."'>";
				if(isset($_POST["chbnombre"])) echo"<input type='hidden' id='chbnombre' name='chbnombre' value='".$_POST["chbnombre"]."'>";
				if(isset($_POST["bnombre"])) echo"<input type='hidden' id='bnombre' name='bnombre' value='".$_POST["bnombre"]."'>";
				if(isset($_POST["accion_vale_pago"])) echo"<input type='hidden' id='empleado_telf' name='empleado_telf' value='".$_POST["accion_vale_pago"]."'>";
			?>
		</form>
		<?php
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
						<select class="w3-select w3-border" id="pag" name="pag" onchange="document.getElementById('accion_mostrar').value='';document.getElementById('accion_vale_pago').value='';return enviardatos_lista();">
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
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="document.getElementById('accion_mostrar').value='';document.getElementById('accion_vale_pago').value='';return enviardatos_lista();">
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
							echo"<input type='hidden' id='accion_vale_pago' name='accion_vale_pago'>";
							echo"<input type='hidden' id='accion_mostrar' name='accion_mostrar'>";
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row = $result->fetch_array();
								$num_col=count($row)/2;
								echo"<td align='center' nowrap>";
									echo"<i class='icon-calculator3 icon_table' id='vale_pago_<?php echo $i; ?>' name='vale_pago_<?php echo $i; ?>' alt='Asignar Vale o Pago' title='Asignar Vale o Pago' ";
									?>
									onclick="document.getElementById('accion_vale_pago').value='<?php echo $row[0]; ?>';return enviardatos_lista();"
									<?php
									echo"'></i>";
									echo"&nbsp;&nbsp;";
									echo"<i class='icon-eye4 icon_table' id='mostrar_<?php echo $i; ?>' name='mostrar_<?php echo $i; ?>' alt='Mostrar Vales o Pagos' title='Mostrar Vales o Pagos'";
									?>
									onclick="document.getElementById('accion_mostrar').value='<?php echo $row[0]; ?>';return enviardatos_lista();"
									<?php
									echo"></i>";
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
					if(isset($_POST["chbempleado_telf"])) echo"<input type='hidden' id='chbempleado_telf' name='chbempleado_telf' value='".$_POST["chbempleado_telf"]."'>";
					if(isset($_POST["bempleado_telf"])) echo"<input type='hidden' id='bempleado_telf' name='bempleado_telf' value='".$_POST["bempleado_telf"]."'>";
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
			if(isset($_POST["chbempleado_telf"]) and !empty($_POST["bempleado_telf"]))
				$where.="empleado_telf = '".$_POST["bempleado_telf"]."' AND ";
			if(isset($_POST["chbnombre"]) and !empty($_POST["bnombre"]))
				$where.="nombre LIKE '%".$_POST["bnombre"]."%' OR apellido LIKE '%".$_POST["bnombre"]."%' AND ";
			$where[strlen($where)-1]=" ";
			$where[strlen($where)-2]=" ";
			$where[strlen($where)-3]=" ";
			$where[strlen($where)-4]=" ";
			$where=trim($where);
			$sql="SELECT empleado_telf, empleado_telf AS cédula, nombre, apellido, correo FROM empleado WHERE ".$where.";";
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="listar")
		{
			$sql="SELECT empleado_telf, empleado_telf AS cédula, nombre, apellido, correo FROM empleado;";
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
			if(eliminar_vale_pago($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","VALE-PAGO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIMINAR EL VALE-PAGO").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		if(isset($_POST["vale_pago_correcto"]) and !empty($_POST["vale_pago_correcto"]))
		{
			if(guardar_vale_pago($bd))
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
					alertify.alert("","ERROR AL AGREGAR EL VALE O PAGO").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		if(isset($_POST["accion_vale_pago"]) and !empty($_POST["accion_vale_pago"]))
		{
			formulario_vale_pago($bd);
		}
		elseif(isset($_POST["accion_mostrar"]) and !empty($_POST["accion_mostrar"]))
		{
			mostrar_vale_pagos($bd);
		}
		else
		{
			if($result=crear_sql_busqueda($bd))
			{
				$colespeciales=array();
				$colocultar = array();
				if(isset($_POST["pag"]) and !empty($_POST["pag"]))
					$pag=$_POST["pag"];
				if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
					$cantxpag=$_POST["cantxpag"];
				$colocultar[0] = "empleado_telf";
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