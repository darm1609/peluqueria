<script type="text/javascript">

	$(document).ready(function(){
		$("#agregar_abono_empleado").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
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

	function submit_abono_empleado()
	{
		var valido=new Boolean(true);
		valido=true;
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
			if(document.getElementById('empleado_cedula').value=='')
			{
				valido=false;
				alertify.alert("","DEBE SELECCIONAR UN EMPLEADO").set('label', 'Aceptar');
			}
		}
		if(valido)
		{
			if(document.getElementById('monto').value=='')
			{
				valido=false;
				alertify.alert("","EL MONTO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
			else
			{
				if(!/^[0-9]+([.][0-9]+)?$/.test(document.getElementById('monto').value))
				{
					valido=false;
					alertify.alert("","MONTO NO VALIDO").set('label', 'Aceptar');
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
			ajax.open("POST","abono_empleado_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("abono_empleado_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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
		ajax.open("POST","abono_empleado_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("abono_empleado_contenido_lista.php",$("#form_tabla").serialize(),function(data)
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
	<h5><b>Administraci&oacute;n de Abono a Empleado</b></h5>
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
		//$fecha=$_POST["fecha"][6].$_POST["fecha"][7].$_POST["fecha"][8].$_POST["fecha"][9]."-".$_POST["fecha"][3].$_POST["fecha"][4]."-".$_POST["fecha"][0].$_POST["fecha"][1];
		$fecha=$_POST["fecha"];
		$fecha_num=time();
		if($bd->insertar_datos(5,$basedatos,"abono_empleado","empleado_cedula","fecha","monto","fecha_num","login",$_POST["empleado_cedula"],$fecha,$_POST["monto"],$fecha_num,$_SESSION["login"]))
			return true;
		else
			return false;
	}

	function formulario_agregar_abono_empleado($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
			<h2 class="w3-center">Abono</h2>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="fecha"><i class="icon-calendar" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<?php
						$hoy=date("d-m-Y",time());
					?>
					<input type="text" class="w3-input w3-border" id="fecha" name="fecha" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>">
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
				<div class="w3-col" style="width:50px"><label for="monto"><i class="icon-sort-numerically-outline" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="monto" name="monto" type="text" placeholder="Monto" onkeypress="return NumCheck(event, this)">
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_abono_empleado();" value="Guardar">
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
				<button id="agregar_abono_empleado" class="w3-button w3-blue"><i class="icon-plus4">&nbsp;</i>Agregar Abono a Empleado</button>
			</div>
			<?php
			echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
				formulario_agregar_abono_empleado($bd);
			echo"</div>";
			formulario_busqueda($bd);
			echo"<div id='loader'></div>";
			echo"<div id='divformulariolista'></div>";
			if(isset($_POST["fecha"]) and isset($_POST["monto"]))
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
						alertify.alert("","NO SE PUDO GUARDAR EL ABONO").set('label', 'Aceptar');
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