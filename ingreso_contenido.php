<script type="text/javascript">
	
	$(document).ready(function(){
		$("#agregar_ingreso").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
		});
		
		$("#cliente_telf").on("change",function(){
			let el = $(this);
			if (el.val().length)
				$("#monto_deuda_div").show();
			else
				$("#monto_deuda_div").hide();
		});
		
		$(function() {
			$("#fecha_ingreso").datepicker({
				dateFormat:"dd-mm-yy",
				dayNamesMin:[ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
				monthNames:[ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
			});
			$("#bfecha").datepicker({
				dateFormat:"dd-mm-yy",
				dayNamesMin:[ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
				monthNames:[ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
			});
		});
	});

	function habilitar_especificar()
	{
		document.getElementById('chbfecha').disabled=false;
		if(document.getElementById('chbfecha').checked)
			document.getElementById('bfecha').disabled=false;
		else
			document.getElementById('bfecha').disabled=true;;
		document.getElementById('chbempleado_telf').disabled=false;
		if(document.getElementById('chbempleado_telf').checked)
			document.getElementById('bempleado_telf').disabled=false;
		else
			document.getElementById('bempleado_telf').disabled=true;;
	}

	function deshabilitar_especificar()
	{
		document.getElementById('chbfecha').disabled=true;
		document.getElementById('bfecha').disabled=true;
		document.getElementById('chbempleado_telf').disabled=true;
		document.getElementById('bempleado_telf').disabled=true;
	}

	function confirmar_eliminar(id)
	{
		alertify.confirm('','¿Desea eliminar el ingreso?', function(){ alertify.success('Sí');enviardatos_lista(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}

	function enviardatos_lista()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","ingreso_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("ingreso_contenido_lista.php",$("#form_tabla").serialize(),function(data)
				{
					$("#divformulariolista").show();
					$("#divformulariolista").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function enviardatos_busqueda()
	{
		let valido=true;
		if(document.getElementById('especificar').checked)
		{
			if(document.getElementById('bfecha').value=='')
			{
				valido=false;
				alertify.alert("","LA FECHA DE BUSQUEDA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
			}
			else
			{
				if(!validaFechaDDMMAAAA(document.getElementById('bfecha').value))
				{
					valido=false;
					alertify.alert("","LA FECHA DE BUSQUEDA NO ES VALIDA").set('label', 'Aceptar');
				}
			}
		}
		if (valido)
		{
			ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","ingreso_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("ingreso_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
				    {
				    	$("#divformulariolista").show();
						$("#divformulariolista").html(data);
				    	$("#loader").hide();
				    });
				}
			} 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
			ajax.send();
		}
	}

	function submit_ingreso() 
	{
		var valido = new Boolean(true);
		$('#fecha_ingreso').val($('#fecha_ingreso').val().trim());
		if(!$('#fecha_ingreso').val().length)
		{
			valido=false;
			alertify.alert("","LA FECHA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
		}
		else
		{
			if(!validaFechaDDMMAAAA($('#fecha_ingreso').val()))
			{
				valido=false;
				alertify.alert("","LA FECHA NO ES VALIDA").set('label', 'Aceptar');
			}
		}
		if (valido)
		{
			if (!$("#empleado_telf").val().length)
			{
				valido=false;
				alertify.alert("","DEBE SELECCIONAR UN EMPLEADO").set('label', 'Aceptar');
			}
		}
		if (valido)
		{
			if (!$("#id_motivo_ingreso").val().length)
			{
				valido=false;
				alertify.alert("","DEBE SELECCIONAR UN TIPO DE TRABAJO").set('label', 'Aceptar');
			}
		}
		$("#monto_transferencia").val($("#monto_transferencia").val().trim());
		$("#monto_datafono").val($("#monto_datafono").val().trim());
		$("#monto_efectivo").val($("#monto_efectivo").val().trim());
		if (!$("#cliente_telf").val().length)
			$("#monto_deuda").val("");
		$("#monto_deuda").val($("#monto_deuda").val().trim());
		$("#referencia").val($("#referencia").val().trim());

		let monto_transferencia = $("#monto_transferencia").val().replaceAll(',','');
		let monto_datafono = $("#monto_datafono").val().replaceAll(',','');
		let monto_efectivo = $("#monto_efectivo").val().replaceAll(',','');
		let monto_deuda = $("#monto_deuda").val().replaceAll(',','');

		if (valido)
		{
			if (!monto_transferencia.length && !monto_datafono.length && !monto_efectivo.length && !monto_deuda.length)
			{
				valido=false;
				alertify.alert("","DEBE COLOCAR EL MONTO DEL INGRESO").set('label', 'Aceptar');
			}
		}
		// if (valido)
		// {
		// 	if ($("#monto_transferencia").val().length && !$("#referencia").val().length)
		// 	{
		// 		valido=false;
		// 		alertify.alert("","DEBE COLOCAR EL N\u00DAMERO DE REFERENCIA DE LA TRANSFERENCIA").set('label', 'Aceptar');
		// 	}
		// }
		if (valido)
		{
			if (monto_transferencia.length)
			{
				if (isNaN(monto_transferencia))
				{
					valido=false;
					alertify.alert("","MONTO DE TRANSFERENCIA NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number(monto_transferencia) < 1)
					{
						valido=false;
						alertify.alert("","MONTO DE TRANSFERENCIA NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
		}
		if (valido)
		{
			if (monto_datafono.length)
			{
				if (isNaN(monto_datafono))
				{
					valido=false;
					alertify.alert("","MONTO DE DEBITO/DAT\u00C1FONO NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number(monto_datafono) < 1)
					{
						valido=false;
						alertify.alert("","MONTO DE DEBITO/DAT\u00C1FONO NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
		}
		if (valido)
		{
			if (monto_efectivo.length)
			{
				if (isNaN(monto_efectivo))
				{
					valido=false;
					alertify.alert("","MONTO DE EFECTIVO NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number(monto_efectivo) < 1)
					{
						valido=false;
						alertify.alert("","MONTO DE EFECTIVO NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
		}
		if (valido)
		{
			if (monto_deuda.length)
			{
				if (isNaN(monto_deuda))
				{
					valido=false;
					alertify.alert("","MONTO DE DEUDA NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number(monto_deuda) < 1)
					{
						valido=false;
						alertify.alert("","MONTO DE DEUDA NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
		}
		if (valido)
		{
			document.getElementById('fagregar').submit();
		}
	}

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de Ingresos</b></h5>
</header>
<?php

	function guardar($bd)
	{
		global $basedatos;
		$fecha = $_POST["fecha_ingreso"];
		//$fecha_num=time();
		$fecha_num = strtotime($_POST["fecha_ingreso"][6].$_POST["fecha_ingreso"][7].$_POST["fecha_ingreso"][8].$_POST["fecha_ingreso"][9]."-".$_POST["fecha_ingreso"][3].$_POST["fecha_ingreso"][4]."-".$_POST["fecha_ingreso"][0].$_POST["fecha_ingreso"][1]);
		$efectivo = 0;
		$transferencia = 0;
		$debito = 0;
		$deuda = 0;
		if (!empty($_POST["monto_transferencia"]))
			$transferencia = 1;
		if (!empty($_POST["monto_datafono"]))
			$debito = 1;
		if (!empty($_POST["monto_efectivo"]))
			$efectivo = 1;
		if (!empty($_POST["monto_deuda"]))
			$deuda = 1;
		$monto_transferencia = str_replace(",","",$_POST["monto_transferencia"]);
		$monto_efectivo = str_replace(",","",$_POST["monto_efectivo"]);
		$monto_datafono = str_replace(",","",$_POST["monto_datafono"]);
		$monto_deuda = str_replace(",","",$_POST["monto_deuda"]);
		if($bd->insertar_datos(11,$basedatos,"ingreso","id_motivo_ingreso","cliente_telf","fecha","fecha_num","efectivo","transferencia","debito","deuda","empleado_telf","login","observacion",$_POST["id_motivo_ingreso"],$_POST["cliente_telf"],$fecha,$fecha_num,$efectivo,$transferencia,$debito,$deuda,$_POST["empleado_telf"],$_SESSION["login"],$_POST["observacion"]))
		{
			$insert_id = $bd->ultimo_result;
			$valido = false;
			if ($transferencia == 1)
			{
				if ($bd->insertar_datos(3,$basedatos,"ingreso_transferencia","id_ingreso","monto","referencia",$insert_id,$monto_transferencia,$_POST["referencia"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($debito == 1)
			{
				if ($bd->insertar_datos(2,$basedatos,"ingreso_debito","id_ingreso","monto",$insert_id,$monto_datafono))
					$valido = true;
				else
					$valido = false;
			}
			if ($efectivo == 1)
			{
				if ($bd->insertar_datos(2,$basedatos,"ingreso_efectivo","id_ingreso","monto",$insert_id,$monto_efectivo))
					$valido = true;
				else
					$valido = false;
			}
			if ($deuda == 1)
			{
				if ($bd->insertar_datos(4,$basedatos,"ingreso_deuda","id_ingreso","monto","monto_pagado","pagada",$insert_id,$monto_deuda,0,0))
					$valido = true;
				else
					$valido = false;
			}
			if (!$valido) //Devolver todos los cambios
			{
				$bd->eliminar_datos(1,$basedatos,"ingreso_transferencia","id_ingreso",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"ingreso_debito","id_ingreso",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"ingreso_efectivo","id_ingreso",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"ingreso_deuda","id_ingreso",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"ingreso","id_ingreso",$insert_id);
				return false;
			}
			return true;
		}
		else
			return false;
	}
	
	function formulario_agregar_ingreso($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
			<h2 class="w3-center">Ingreso</h2>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="fecha_ingreso"><i class="icon-calendar" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<?php
						$hoy=date("d-m-Y",time());
					?>
					<input type="text" class="w3-input w3-border" id="fecha_ingreso" name="fecha_ingreso" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>">
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="empleado_telf"><i class="icon-menu" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<select class="w3-select" id="empleado_telf" name="empleado_telf">
						<option value="">Empleado</option>
						<?php
							$sql="SELECT empleado_telf, nombre, apellido FROM empleado;";
							$result = $bd->mysql->query($sql);
							unset($sql);
							if($result)
							{
								while($row = $result->fetch_array())
								{
									echo"<option value='".$row["empleado_telf"]."'>".$row["nombre"]." ".$row["apellido"]."</option>";
								}
								unset($row);
								$result->free();
							}
							else
								unset($result);
						?>
					</select>
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="id_motivo_ingreso"><i class="icon-menu" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<select class="w3-select" id="id_motivo_ingreso" name="id_motivo_ingreso">
						<option value="">Tipo de Trabajo</option>
						<?php
							$sql="SELECT id_motivo_ingreso, motivo FROM motivo_ingreso order by motivo asc;";
							$result = $bd->mysql->query($sql);
							unset($sql);
							if($result)
							{
								while($row = $result->fetch_array())
								{
									echo"<option value='".$row["id_motivo_ingreso"]."'>".$row["motivo"]."</option>";
								}
								unset($row);
								$result->free();
							}
							else
								unset($result);
						?>
					</select>
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="cliente_telf"><i class="icon-menu" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<select class="w3-select" id="cliente_telf" name="cliente_telf">
						<option value="">Cliente</option>
						<?php
							$sql="SELECT telf, nombre, apellido, alias FROM cliente;";
							$result = $bd->mysql->query($sql);
							unset($sql);
							if($result)
							{
								while($row = $result->fetch_array())
								{
									if(empty($row["alias"]))
										echo"<option value='".$row["telf"]."'>".$row["nombre"]." ".$row["apellido"]."</option>";
									else
										echo"<option value='".$row["telf"]."'>".$row["alias"]." - ".$row["nombre"]." ".$row["apellido"]."</option>";
								}
								unset($row);
								$result->free();
							}
							else
								unset($result);
						?>
					</select>
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
			<label for="monto_datafono"><b>Debito/Dat&aacute;fono</b></label>
			<div class="w3-row">
				<div class="w3-rest">
					<input type="text" class="w3-input w3-border" inputmode="decimal" data-type="currency" id="monto_datafono" name="monto_datafono" placeholder="Monto" min=1>
				</div>
			</div>
			<label for="monto_efectivo"><b>Efectivo</b></label>
			<div class="w3-row">
				<div class="w3-rest">
					<input type="text" class="w3-input w3-border" inputmode="decimal" data-type="currency" id="monto_efectivo" name="monto_efectivo" placeholder="Monto" min=1>
				</div>
			</div>
			<div id="monto_deuda_div" style="display:none;">
				<label for="monto_deuda"><b>Deuda</b></label>
				<div class="w3-row">
					<div class="w3-rest">
						<input type="text" class="w3-input w3-border" inputmode="decimal" data-type="currency" id="monto_deuda" name="monto_deuda" placeholder="Monto" min=1>
					</div>
				</div>
			</div>
			<label for="observacion"><b>Comentario</b></label>
			<div class="w3-row">
				<div class="w3-rest">
					<textarea style="float: left;width: 100%;height: auto;" id="observacion" name="observacion"></textarea>
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_ingreso();" value="Guardar">
			</div>
		</form>
		<?php
	}

	function formulario_busqueda($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
			<h2 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Busqueda</h2>
			<p>
				<label>
				<input class="w3-radio" type="radio" id="especificar" name="sel_opcion" value="especificar" onclick="habilitar_especificar();">
				Especificar
				</label>
			</p>
			<div class="w3-row w3-section">
				<table border="0" style="width: 100%;">
					<tr>
						<td align="right">
							<input class="w3-check" type="checkbox" id="chbfecha" name="chbfecha" disabled onclick="if(document.getElementById('chbfecha').checked){document.getElementById('bfecha').disabled=false;}else{document.getElementById('bfecha').disabled=true;}">
						</td>
						<td>
							<label>
								Fecha&nbsp;del&nbsp;ingreso	
							</label>
							<?php
								$hoy=date("d-m-Y",time());
							?>
							<input type="text" class="w3-input w3-border" id="bfecha" name="bfecha" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>" disabled>
						</td>
					</tr>
					<tr>
						<td align="right">
							<input class="w3-check" type="checkbox" id="chbempleado_telf" name="chbempleado_telf" disabled onclick="if(document.getElementById('chbempleado_telf').checked){document.getElementById('bempleado_telf').disabled=false;}else{document.getElementById('bempleado_telf').disabled=true;}">
						</td>
						<td>
							<label>
								Empleado
							</label>
							<select class="w3-select" id="bempleado_telf" name="bempleado_telf" disabled>
								<option value="">Empleado</option>
								<?php
									$sql="SELECT empleado_telf, nombre, apellido FROM empleado;";
									$result = $bd->mysql->query($sql);
									unset($sql);
									if($result)
									{
										while($row = $result->fetch_array())
										{
											echo"<option value='".$row["empleado_telf"]."'>".$row["nombre"]." ".$row["apellido"]."</option>";
										}
										unset($row);
										$result->free();
									}
									else
										unset($result);
								?>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<p>
				<label>
				<input class="w3-radio" type="radio" id="listar" name="sel_opcion" value="listar" onclick="deshabilitar_especificar();" checked>
				Listar
				</label>
			</p>
			<div class="w3-row w3-section">
				<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda();">
			</div>
		</form>
		<?php
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		?>
		<div class="w3-container">
			<button id="agregar_ingreso" class="w3-button w3-dulcevanidad"><i class="icon-plus4">&nbsp;</i>Agregar Ingreso</button>
		</div>
		<?php
		echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
		formulario_agregar_ingreso($bd);
		echo"</div>";
		formulario_busqueda($bd);
		if(isset($_POST["fecha_ingreso"]))
		{
			if(guardar($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","GUARDADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO GUARDAR EL INGRESO").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		echo"<div id='divformulariolista'></div>";
	}
?>