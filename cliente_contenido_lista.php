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
	require("librerias/basedatos.php");

	function guardar_modificar($bd)
	{
		global $basedatos;
		if($bd->actualizar_datos(1,1,$basedatos,"ingreso","cliente_cedula",$_POST["ocliente_cedula"],"cliente_cedula",$_POST["ocliente_cedula"],$_POST["mcliente_cedula"]))
		{
			if($bd->actualizar_datos(1,5,$basedatos,"cliente","cliente_cedula",$_POST["ocliente_cedula"],"cliente_cedula",$_POST["ocliente_cedula"],$_POST["mcliente_cedula"],"nombre",$_POST["onombre"],$_POST["mnombre"],"apellido",$_POST["oapellido"],$_POST["mapellido"],"alias",$_POST["oalias"],$_POST["malias"],"telf",$_POST["otelf"],$_POST["mtelf"]))
			{
				print_r($_POST);
				foreach($_POST as $index => $value)
				{
					$id_ingreso = "";
					$abono = "";
					$pagado = 0;
					$monto_pagado = 0;
					if (strstr($index, 'id_ingreso_'))
					{
						$id_ingreso = $value;
						$abono = $_POST["abono_".$id_ingreso];
						echo "<br>id_ingreso: ".$id_ingreso."<br>abono: ".$abono;
						$sql = "select id.monto, id.monto_pagado, id.pagada from ingreso i inner join ingreso_deuda id on i.id_ingreso = id.id_ingreso where i.id_ingreso = '".$id_ingreso."';";
						$result = $bd->mysql->query($sql);
						unset($sql);
						if ($result)
						{
							$rows = $result->fetch_all(MYSQLI_ASSOC);
							$result->free();
							foreach ($rows as $row)
							{
								$abono += $row["monto_pagado"];
								if ($abono >= $row["monto"])
									$pagado = 1;
							}
							unset ($rows);
						}
						else
							unset($result);
						$bd->actualizar_datos(1,2,$basedatos,"ingreso_deuda","id_ingreso",$id_ingreso,"monto_pagado","",$abono,"pagada","",$pagado);
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

	function eliminar_cliente($bd)
	{
		global $basedatos;
		$sql = "select id_ingreso from ingreso where cliente_cedula = '".$_POST["accion_eliminar"]."';";
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
		}
		else
			unset($result);
		if($bd->eliminar_datos(1,$basedatos,"cliente","cliente_cedula",$_POST["accion_eliminar"]))
			return true;
		else
			return false;
	}

	function exite($bd)
	{
		if($bd->existe(1,"cliente","cliente_cedula",$_POST["mcliente_cedula"]))
			return true;
		else
			return false;
	}

	function formulario_modificar($bd)
	{
		$sql="SELECT cliente_cedula, nombre, apellido, alias, telf FROM cliente WHERE cliente_cedula='".$_POST["accion_modificar"]."';";
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
					<div class="w3-col" style="width:50px"><label for="mcliente_cedula"><i class="icon-drivers-license-o" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="ocliente_cedula" name="ocliente_cedula" value="<?php echo $row[0]['cliente_cedula']; ?>">
						<input class="w3-input w3-border" id="mcliente_cedula" name="mcliente_cedula" type="text" placeholder="C&eacute;dula" onkeypress="return NumCheck2(event, this)" tabindex="1" value="<?php echo $row[0]['cliente_cedula']; ?>">
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
					<div class="w3-col" style="width:50px"><label for="mtelf"><i class=" icon-phone" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="otelf" name="otelf" value="<?php echo $row[0]['telf']; ?>">
						<input class="w3-input w3-border" id="mtelf" name="mtelf" type="text" placeholder="Tel&eacute;fono" maxlength="11" onkeypress="return NumCheck3(event, this)" tabindex="5" value="<?php echo $row[0]['telf']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:300px"><label for="">
						<?php
							$sql2 = "select (sum(id.monto) - sum(id.monto_pagado)) as total FROM ingreso i inner join cliente c on i.cliente_cedula = c.cliente_cedula inner join ingreso_deuda id on i.id_ingreso = id.id_ingreso where c.cliente_cedula = '".$row[0]['cliente_cedula']."';";
							$result2 = $bd->mysql->query($sql2);
							$tieneDeuda = false;
							unset($sql2);
							if($result2)
							{
								$row2 = $result2->fetch_all(MYSQLI_ASSOC);
								if ($row2[0]["total"] != null)
									$tieneDeuda = true;
								$result2->free();
								if ($tieneDeuda)
									echo "Deuda&nbsp;Total: ".$row2[0]["total"];
								else
									echo "El cliente no posee deudas";
							}
							else
								unset($result2);
						?>
					</label></div>
				</div>
				<?php
					if ($tieneDeuda)
					{
				?>
				<div class="w3-row w3-section">
					<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
						<thead>
							<tr class="w3-blue">
								<th class='tableheadresul' align='center' nowarp>Fecha</th>
								<th class='tableheadresul' align='center' nowarp>Tipo De Trabajo</th>
								<th class='tableheadresul' align='center' nowarp>Realizado Por</th>
								<th class='tableheadresul' align='center' nowarp>Debe</th>
								<th class='tableheadresul' align='center' nowarp>Pagado</th>
								<th class='tableheadresul' align='center' nowarp>Deuda total</th>
								<th class='tableheadresul' align='center' nowarp>Abono</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql3 = "select i.id_ingreso, i.fecha, mi.motivo as 'tipo_de_trabajo', concat(e.nombre,' ',e.apellido) as 'realizado_por', id.monto as 'debe', id.monto_pagado as 'pagado', (id.monto - ifnull(id.monto_pagado,0)) as 'total', id.pagada FROM empleado e inner join ingreso i on e.empleado_cedula = i.empleado_cedula inner join ingreso_deuda id on i.id_ingreso = id.id_ingreso inner join motivo_ingreso mi on i.id_motivo_ingreso = mi.id_motivo_ingreso where i.cliente_cedula = '".$row[0]['cliente_cedula']."';";
								$result3 = $bd->mysql->query($sql3);
								unset($sql3);
								if($result3)
								{
									$row3 = $result3->fetch_all(MYSQLI_ASSOC);
									$result3->free();
									foreach ($row3 as $val)
									{
										echo"<tr>";
										echo"<td align='center'>";
										echo $val['fecha'][8].$val['fecha'][9].'-'.$val['fecha'][5].$val['fecha'][6].'-'.$val['fecha'][0].$val['fecha'][1].$val['fecha'][2].$val['fecha'][3];
										echo"</td>";
										echo"<td align='center'>";
										echo $val['tipo_de_trabajo'];
										echo"</td>";
										echo"<td align='center'>";
										echo $val['realizado_por'];
										echo"</td>";
										echo"<td align='center'>";
										echo $val['debe'];
										echo"</td>";
										echo"<td align='center'>";
										echo $val['pagado'];
										echo"</td>";
										echo"<td align='center'>";
										echo $val['total'];
										echo"</td>";
										echo"<td align='center'>";
											if ($val['pagada'] == 0) 
											{
												echo "<input type='hidden' id='id_ingreso_".$val["id_ingreso"]."' name='id_ingreso_".$val["id_ingreso"]."' value='".$val["id_ingreso"]."' data-id-ingreso='".$val["id_ingreso"]."' data-total='".$val['total']."'>";
												$hoy=date("d-m-Y",time());
												echo "<input class='w3-input w3-border abono-fecha' placeholder='dd-mm-aaaa' type='text' id='abono_fecha_".$val["id_ingreso"]."' name='abono_fecha_".$val["id_ingreso"]."' value='".$hoy."'><br>";
												echo "<input class='w3-input w3-border abono-efectivo' placeholder='Efectivo' type='number' id='abono_efectivo_".$val["id_ingreso"]."' name='abono_efectivo_".$val["id_ingreso"]."' min=1><br>";
												echo "<input class='w3-input w3-border abono-transferencia' placeholder='Transferencia' type='number' id='abono_transferencia_".$val["id_ingreso"]."' name='abono_transferencia_".$val["id_ingreso"]."' min=1>";
												echo "<input class='w3-input w3-border abono-referencia' placeholder='Referencia' type='text' id='abono_referencia_".$val["id_ingreso"]."' name='abono_referencia_".$val["id_ingreso"]."'><br>";
												echo "<input class='w3-input w3-border abono-datafono' placeholder='Datáfono' type='number' id='abono_datafono_".$val["id_ingreso"]."' name='abono_datanofno_".$val["id_ingreso"]."' min=1>";
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
					if(isset($_POST["chbcedula"])) echo"<input type='hidden' id='chbcedula' name='chbcedula' value='".$_POST["chbcedula"]."'>";
					if(isset($_POST["bcedula"])) echo"<input type='hidden' id='bcedula' name='bcedula' value='".$_POST["bcedula"]."'>";
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
						<select class="w3-select w3-border" id="pag" name="pag" onchange="document.getElementById('accion_eliminar').value='';document.getElementById('accion_modificar').value='';return enviardatos_lista();">
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
						<input type="button" class="w3-button w3-block w3-blue" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="document.getElementById('accion_eliminar').value='';document.getElementById('accion_modificar').value='';return enviardatos_lista();">
					</div>
				</div>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<tr class="w3-blue">
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
									echo"&nbsp;&nbsp;";
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
				<?php
					if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
					if(isset($_POST["chbcedula"])) echo"<input type='hidden' id='chbcedula' name='chbcedula' value='".$_POST["chbcedula"]."'>";
					if(isset($_POST["bcedula"])) echo"<input type='hidden' id='bcedula' name='bcedula' value='".$_POST["bcedula"]."'>";
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
			if(isset($_POST["chbcedula"]) and !empty($_POST["bcedula"]))
				$where.="cliente_cedula='".$_POST["bcedula"]."' AND ";
			if(isset($_POST["chbnombre"]) and !empty($_POST["bnombre"]))
				$where.="nombre LIKE '%".$_POST["bnombre"]."%' OR apellido LIKE '%".$_POST["bnombre"]."%' AND ";
			$where[strlen($where)-1]=" ";
			$where[strlen($where)-2]=" ";
			$where[strlen($where)-3]=" ";
			$where[strlen($where)-4]=" ";
			$where=trim($where);
			$sql="SELECT cliente_cedula, cliente_cedula AS cédula, nombre, apellido, alias, telf FROM cliente WHERE ".$where.";";
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="listar")
		{
			$sql="SELECT cliente_cedula, cliente_cedula AS cédula, nombre, apellido, alias, telf FROM cliente;";
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
			if($_POST["ocliente_cedula"]!=$_POST["mcliente_cedula"])
			{
				if(exite($bd))
				{
					$valido=false;
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","EL CLIENTE "+<?php echo $_POST["mcliente_cedula"]; ?>+" YA SE ENCUENTRA REGISTRADO").set('label', 'Aceptar');
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
				$colocultar[0]="cliente_cedula";
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