<script type="text/javascript">

	$(document).ready(function(){
		$("#agregar_venta").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
		});

		$("#cliente_cedula").on("change",function(){
			let el = $(this);
			if (el.val().length)
				$("#monto_deuda_div").show();
			else
				$("#monto_deuda_div").hide();
		});

		$(function() {
			$("#fecha").datepicker({
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

	function submit_venta()
	{
		var valido=new Boolean(true);
		if(document.getElementById('fecha').value=='')
		{
			valido=false;
			alertify.alert("","LA FECHA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
		}
		else
		{
			if(!validaFechaDDMMAAAA(document.getElementById('fecha').value))
			{
				valido=false;
				alertify.alert("","LA FECHA NO ES VALIDA").set('label', 'Aceptar');
			}
		}
		if(valido)
		{
			if(document.getElementById('motivo').value=='')
			{
				valido=false;
				alertify.alert("","EL MOTIVO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
		}
		$("#monto_transferencia").val($("#monto_transferencia").val().trim());
		$("#monto_datafono").val($("#monto_datafono").val().trim());
		$("#monto_efectivo").val($("#monto_efectivo").val().trim());
		if (!$("#cliente_cedula").val().length)
			$("#monto_deuda").val("");
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
		if(valido)
		{
			document.getElementById('fagregar').submit();
		}
	}

	function habilitar_fecha()
	{
		document.getElementById('bfecha').disabled=false;
	}

	function deshabilitar_fecha()
	{
		document.getElementById('bfecha').disabled=true;
	}

	function enviardatos_busqueda()
	{
		var valido;
		valido=true;
		if(document.getElementById('rfecha').checked)
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
		if(valido)
		{
			ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","venta_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("venta_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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
		else
		{
			$("#divformulariolista").hide();
		}
	}

	function enviardatos_lista()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","venta_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("venta_contenido_lista.php",$("#form_tabla").serialize(),function(data)
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

	function confirmar_eliminar(id)
	{
		alertify.confirm('','¿Desea Eliminar?', function(){ alertify.success('Sí');enviardatos_lista(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de Ventas</b></h5>
</header>
<?php

	function formulario_busqueda($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
			<h2 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Busqueda</h2>
			<table border="0">
				<tr>
					<td>
						<input class="w3-radio" type="radio" id="rfecha" name="sel_opcion" value="fecha" onclick="habilitar_fecha();">
					</td>
					<td>
						<?php
							$hoy=date("d-m-Y",time());
						?>
						<input type="text" class="w3-input w3-border" id="bfecha" name="bfecha" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>" disabled>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<label>
							<input class="w3-radio" type="radio" id="rtodo" name="sel_opcion" value="todo" onclick="deshabilitar_fecha();" checked>
							Todo
						</label>
					</td>
				</tr>
			</table>
			<div class="w3-row w3-section">
				<input class="w3-button w3-block w3-blue" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda();">
			</div>
		</form>
		<?php
	}

	function guardar($bd)
	{
		global $basedatos;
		$fecha = $_POST["fecha"];
		$fecha_num = time();
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
		if($bd->insertar_datos(9,$basedatos,"venta","fecha","motivo","cliente_cedula","efectivo","debito","transferencia","deuda","fecha_num","login",$fecha,trim($_POST["motivo"]),$_POST["cliente_cedula"],$efectivo,$debito,$transferencia,$deuda,$fecha_num,$_SESSION["login"]))
		{
			$insert_id = $bd->ultimo_result;
			$valido = false;
			if ($transferencia == 1)
			{
				if ($bd->insertar_datos(3,$basedatos,"venta_transferencia","id_venta","monto","referencia",$insert_id,$_POST["monto_transferencia"],$_POST["referencia"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($debito == 1)
			{
				if ($bd->insertar_datos(2,$basedatos,"venta_debito","id_venta","monto",$insert_id,$_POST["monto_datafono"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($efectivo == 1)
			{
				if ($bd->insertar_datos(2,$basedatos,"venta_efectivo","id_venta","monto",$insert_id,$_POST["monto_efectivo"]))
					$valido = true;
				else
					$valido = false;
			}
			if ($deuda == 1)
			{
				if ($bd->insertar_datos(4,$basedatos,"venta_deuda","id_venta","monto","monto_pagado","pagada",$insert_id,$_POST["monto_deuda"],0,0))
					$valido = true;
				else
					$valido = false;
			}
			if (!$valido) //Devolver todos los cambios
			{
				$bd->eliminar_datos(1,$basedatos,"venta_transferencia","id_venta",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"venta_debito","id_venta",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"venta_efectivo","id_venta",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"venta_deuda","id_venta",$insert_id);
				$bd->eliminar_datos(1,$basedatos,"venta","id_venta",$insert_id);
				return false;
			}
			return true;
		}
		else
			return false;
	}

	function formulario_agregar_venta($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
			<h2 class="w3-center">Venta</h2>
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
				<div class="w3-col" style="width:50px"><label for="motivo"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="motivo" name="motivo" type="text" placeholder="Motivo">
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
			<div id="monto_deuda_div" style="display:none;">
				<label for="monto_deuda"><b>Deuda</b></label>
				<div class="w3-row">
					<div class="w3-rest">
						<input type="number" class="w3-input w3-border" id="monto_deuda" name="monto_deuda" placeholder="Monto" min=1>
					</div>
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_venta();" value="Guardar">
			</div>
		</form>
		<?php
	}
	
	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(usuario_admin())
		{
			?>
			<div class="w3-container">
				<button id="agregar_venta" class="w3-button w3-blue"><i class="icon-plus4">&nbsp;</i>Agregar Venta</button>
			</div>
			<?php
			echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
				formulario_agregar_venta($bd);
			echo"</div>";
			formulario_busqueda($bd);
			echo"<div id='loader'></div>";
			echo"<div id='divformulariolista'></div>";
			if(isset($_POST["fecha"]) and isset($_POST["motivo"]))
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
						alertify.alert("","NO SE PUDO GUARDAR LA VENTA").set('label', 'Aceptar');
					</script>
					<?php
				}
			}
		}
		else
		{
			?>
			<div class="w3-panel w3-yellow">
				<h3>Advertencia</h3>
				<p>Acceso Restringido / Solo Administradores</p>
			</div> 
			<?php
		}
	}
?>