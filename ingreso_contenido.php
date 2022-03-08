<script type="text/javascript">
	
	$(document).ready(function(){
		$("#agregar_ingreso").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
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
	}

	function deshabilitar_especificar()
	{
		document.getElementById('chbfecha').disabled=true;
		document.getElementById('bfecha').disabled=true;
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
			if (!$("#empleado_cedula").val().length)
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
		$("#monto_deuda").val($("#monto_deuda").val().trim());
		$("#referencia").val($("#referencia").val().trim());
		if (valido)
		{
			if (!$("#monto_transferencia").val().length && !$("#monto_datafono").val().length && !$("#monto_efectivo").val().length && !$("#monto_deuda").val().length)
			{
				valido=false;
				alertify.alert("","DEBE COLOCAR EL MONTO DEL INGRESO").set('label', 'Aceptar');
			}
		}
		if (valido)
		{
			if ($("#monto_transferencia").val().length && !$("#referencia").val().length)
			{
				valido=false;
				alertify.alert("","DEBE COLOCAR EL N\u00DAMERO DE REFERENCIA DE LA TRANSFERENCIA").set('label', 'Aceptar');
			}
		}
		if (valido)
		{
			if ($("#monto_transferencia").val().length)
			{
				if (isNaN($("#monto_transferencia").val()))
				{
					valido=false;
					alertify.alert("","MONTO DE TRANSFERENCIA NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number($("#monto_transferencia").val()) < 1)
					{
						valido=false;
						alertify.alert("","MONTO DE TRANSFERENCIA NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
		}
		if (valido)
		{
			if ($("#monto_datafono").val().length)
			{
				if (isNaN($("#monto_datafono").val()))
				{
					valido=false;
					alertify.alert("","MONTO DE DEBITO/DAT\u00C1FONO NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number($("#monto_datafono").val()) < 1)
					{
						valido=false;
						alertify.alert("","MONTO DE DEBITO/DAT\u00C1FONO NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
		}
		if (valido)
		{
			if ($("#monto_efectivo").val().length)
			{
				if (isNaN($("#monto_efectivo").val()))
				{
					valido=false;
					alertify.alert("","MONTO DE EFECTIVO NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number($("#monto_efectivo").val()) < 1)
					{
						valido=false;
						alertify.alert("","MONTO DE EFECTIVO NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
		}
		if (valido)
		{
			if ($("#monto_deuda").val().length)
			{
				if (isNaN($("#monto_deuda").val()))
				{
					valido=false;
					alertify.alert("","MONTO DE DEUDA NO VALIDO").set('label', 'Aceptar');
				}
				else
				{
					if (Number($("#monto_deuda").val()) < 1)
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
		//$fecha=$_POST["fecha_ingreso"][6].$_POST["fecha_ingreso"][7].$_POST["fecha_ingreso"][8].$_POST["fecha_ingreso"][9]."-".$_POST["fecha_ingreso"][3].$_POST["fecha_ingreso"][4]."-".$_POST["fecha_ingreso"][0].$_POST["fecha_ingreso"][1];
		$fecha = $_POST["fecha_ingreso"];
		$fecha_num=time();
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
		if($bd->insertar_datos(11,$basedatos,"ingreso","id_motivo_ingreso","cliente_cedula","fecha","fecha_num","efectivo","transferencia","debito","deuda","empleado_cedula","login","observacion",$_POST["id_motivo_ingreso"],$_POST["cliente_cedula"],$fecha,$fecha_num,$efectivo,$transferencia,$debito,$deuda,$_POST["empleado_cedula"],$_SESSION["login"],$_POST["observacion"]))
		{
			$insert_id = $bd->ultimo_result;
			$valido = false;
			if ($transferencia == 1)
			{
				if ($bd->insertar_datos(3,$basedatos,"ingreso_transferencia","id_ingreso","monto","referencia",$insert_id,$_POST["monto_transferencia"],$_POST["referencia"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($debito == 1)
			{
				if ($bd->insertar_datos(2,$basedatos,"ingreso_debito","id_ingreso","monto",$insert_id,$_POST["monto_datafono"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($efectivo == 1)
			{
				if ($bd->insertar_datos(2,$basedatos,"ingreso_efectivo","id_ingreso","monto",$insert_id,$_POST["monto_efectivo"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($deuda == 1)
			{
				if ($bd->insertar_datos(4,$basedatos,"ingreso_deuda","id_ingreso","monto","monto_pagado","pagada",$insert_id,$_POST["monto_deuda"],0,0))
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
				<div class="w3-col" style="width:50px"><label for="empleado_cedula"><i class="icon-menu" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<select class="w3-select" id="empleado_cedula" name="empleado_cedula">
						<option value="">Empleado</option>
						<?php
							$sql="SELECT empleado_cedula, nombre, apellido FROM empleado;";
							$result = $bd->mysql->query($sql);
							unset($sql);
							if($result)
							{
								while($row = $result->fetch_array())
								{
									echo"<option value='".$row["empleado_cedula"]."'>".$row["nombre"]." ".$row["apellido"]."</option>";
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
							$sql="SELECT id_motivo_ingreso, motivo FROM motivo_ingreso;";
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
				<div class="w3-col" style="width:50px"><label for="cliente_cedula"><i class="icon-menu" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<select class="w3-select" id="cliente_cedula" name="cliente_cedula">
						<option value="">Cliente</option>
						<?php
							$sql="SELECT cliente_cedula, nombre, apellido, alias FROM cliente;";
							$result = $bd->mysql->query($sql);
							unset($sql);
							if($result)
							{
								while($row = $result->fetch_array())
								{
									if(empty($row["alias"]))
										echo"<option value='".$row["cliente_cedula"]."'>".$row["nombre"]." ".$row["apellido"]."</option>";
									else
										echo"<option value='".$row["cliente_cedula"]."'>".$row["alias"]." - ".$row["nombre"]." ".$row["apellido"]."</option>";
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
					<input type="number" class="w3-input w3-border" id="monto_transferencia" name="monto_transferencia" placeholder="Monto" min=1>
				</div>
				<div class="w3-col s6">
					<input type="text" class="w3-input w3-border" id="referencia" name="referencia" placeholder="Referencia">
				</div>
			</div>
			<label for="monto_datafono"><b>Debito/Dat&aacute;fono</b></label>
			<div class="w3-row">
				<div class="w3-rest">
					<input type="number" class="w3-input w3-border" id="monto_datafono" name="monto_datafono" placeholder="Monto" min=1>
				</div>
			</div>
			<label for="monto_efectivo"><b>Efectivo</b></label>
			<div class="w3-row">
				<div class="w3-rest">
					<input type="number" class="w3-input w3-border" id="monto_efectivo" name="monto_efectivo" placeholder="Monto" min=1>
				</div>
			</div>
			<label for="monto_deuda"><b>Deuda</b></label>
			<div class="w3-row">
				<div class="w3-rest">
					<input type="number" class="w3-input w3-border" id="monto_deuda" name="monto_deuda" placeholder="Monto" min=1>
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
				</table>
			</div>
			<p>
				<label>
				<input class="w3-radio" type="radio" id="listar" name="sel_opcion" value="listar" onclick="deshabilitar_especificar();" checked>
				Listar
				</label>
			</p>
			<div class="w3-row w3-section">
				<input class="w3-button w3-block w3-blue" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda();">
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
			<button id="agregar_ingreso" class="w3-button w3-blue"><i class="icon-plus4">&nbsp;</i>Agregar Ingreso</button>
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