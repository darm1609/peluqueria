<script type="text/javascript">
	
	$(document).ready(function(){
		$("#agregar_cliente").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
		});
	});

	function habilitar_fecha()
	{
		document.getElementById('bfecha').disabled=false;
	}

	function deshabilitar_fecha()
	{
		document.getElementById('bfecha').disabled=true;
	}

	function submit_cliente()
	{
		var valido=new Boolean(true);
		if(document.getElementById('telf').value=='')
		{
			valido=false;
			alertify.alert("","LA TEL\u00C9FONO NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
		}
		else
		{
			if (!/^([0-9])*$/.test(document.getElementById('telf').value))
			{
				valido=false;
				alertify.alert("","LA TEL\u00C9FONO NO ES VALIDA").set('label', 'Aceptar');
			}
		}
		if(valido)
		{
			if(document.getElementById('nombre').value=='')
			{
				valido=false;
				alertify.alert("","EL NOMBRE NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
		}
		if(valido)
		{
			if(document.getElementById('apellido').value=='')
			{
				valido=false;
				alertify.alert("","EL APELLIDO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
		}
		if(valido)
			document.getElementById('fagregar').submit();
	}

	function enviardatos_busqueda()
	{
		var valido;
		valido=true;
		if(document.getElementById('especificar').checked)
		{
			if(!document.getElementById('chbtelf').checked && !document.getElementById('chbnombre').checked)
			{
				valido=false;
				alertify.alert("","DEBE ESPECIFICAR UNA OPCIÓN DE BUSQUEDA").set('label', 'Aceptar');
			}
			if(document.getElementById('chbtelf').checked)
			{
				if(document.getElementById('btelf').value=="")
				{
					valido=false;
					alertify.alert("","EL TEL\u00C9FONO A BUSCAR NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
				}
			}
			if(document.getElementById('chbnombre').checked)
			{
				if(document.getElementById('bnombre').value=="")
				{
					valido=false;
					alertify.alert("","EL NOMBRE A BUSCAR NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
				}
			}
		}
		if(valido)
		{
			ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","cliente_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("cliente_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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

	function habilitar_especificar()
	{
		document.getElementById('chbtelf').disabled=false;
		if(document.getElementById('chbtelf').checked)
			document.getElementById('btelf').disabled=false;
		else
			document.getElementById('btelf').disabled=true;
		document.getElementById('chbnombre').disabled=false;
		if(document.getElementById('chbnombre').checked)
			document.getElementById('bnombre').disabled=false;
		else
			document.getElementById('bnombre').disabled=true;
	}

	function deshabilitar_especificar()
	{
		document.getElementById('chbtelf').disabled=true;
		document.getElementById('btelf').disabled=true;
		document.getElementById('chbnombre').disabled=true;
		document.getElementById('bnombre').disabled=true;	
	}

	function enviardatos_lista()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","cliente_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("cliente_contenido_lista.php",$("#form_tabla").serialize(),function(data)
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
		alertify.confirm('','Eliminar el cliente: '+id, function(){ alertify.success('Sí');enviardatos_lista(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}

	function enviardatos_modificar()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","cliente_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("cliente_contenido_lista.php",$("#fmodificar").serialize(),function(data)
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

	function confirmar_modificar()
	{
		var cambio=new Boolean(false);
		cambio=false;
		if(document.getElementById('onombre').value!=document.getElementById('mnombre').value)
			cambio=true;
		if(document.getElementById('oapellido').value!=document.getElementById('mapellido').value)
			cambio=true;
		if(document.getElementById('oalias').value!=document.getElementById('malias').value)
			cambio=true;
		if(document.getElementById('otelf').value!=document.getElementById('mtelf').value)
			cambio=true;
		if(document.getElementById('ocorreo').value!=document.getElementById('mcorreo').value)
			cambio=true;
		
		let oespecial = document.getElementById('oespecial').value;
		let mespecial;

		if ($("#mespecial").is(':checked'))
			mespecial = "1";
		else
			mespecial = "0";

		if (oespecial != mespecial)
			cambio = true;

		let validoAbono = true;
		let validoFecha = true;
		let validoTransferenciaConReferencia = true;

		$(".abono-fecha").each(function(e){
			let el = $(this);
			if(!validaFechaDDMMAAAA(el.val()))
				validoFecha=false;
		});

		if (!validoFecha)
			alertify.alert("","LA FECHA NO ES VALIDA").set('label', 'Aceptar');
		else {
			$(".id-ingreso").each(function(e){

				let id_ingreso = $(this).val();
				let total = Number($(this).data("total"));
				let abono_efectivo = $("#abono_efectivo_" + id_ingreso).val($("#abono_efectivo_" + id_ingreso).val().trim()).val();
				let abono_transferencia = $("#abono_transferencia_" + id_ingreso).val($("#abono_transferencia_" + id_ingreso).val().trim()).val();
				let abono_referencia = $("#abono_referencia_" + id_ingreso).val($("#abono_referencia_" + id_ingreso).val().trim()).val();
				let abono_datafono = $("#abono_datafono_" + id_ingreso).val($("#abono_datafono_" + id_ingreso).val().trim()).val();

				abono_efectivo = $("#abono_efectivo_" + id_ingreso).val().replaceAll(',','');
				abono_transferencia = $("#abono_transferencia_" + id_ingreso).val().replaceAll(',','');
				abono_datafono = $("#abono_datafono_" + id_ingreso).val().replaceAll(',','');

				if (validoAbono && validoTransferenciaConReferencia)
				{
					if (abono_efectivo.length)
						if (isNaN(abono_efectivo))
							validoAbono = false;
						else
							cambio = true;
					if (abono_transferencia.length)
						if (isNaN(abono_transferencia))
							validoAbono = false;
						else
							cambio = true;
						// else
						// 	if (!abono_referencia.length)
						// 		validoTransferenciaConReferencia = false;
						// 	else
						// 		cambio = true;
					if (abono_datafono.length)
						if (isNaN(abono_datafono))
							validoAbono = false;
						else
							cambio = true;
					if (validoAbono)
					{
						let totalAbono = 0;
						if (abono_efectivo.length) totalAbono += Number(abono_efectivo);
						if (abono_transferencia.length) totalAbono += Number(abono_transferencia);
						if (abono_datafono.length) totalAbono += Number(abono_datafono);
						if (totalAbono > total)
							validoAbono = false;
					}
				}
			});
			$(".id-ingreso-v").each(function(e){
				let id_ingreso = $(this).val();
				let total = Number($(this).data("total"));
				let abono_efectivo = $("#abono_efectivo_v_" + id_ingreso).val($("#abono_efectivo_v_" + id_ingreso).val().trim()).val();
				let abono_transferencia = $("#abono_transferencia_v_" + id_ingreso).val($("#abono_transferencia_v_" + id_ingreso).val().trim()).val();
				let abono_referencia = $("#abono_referencia_v_" + id_ingreso).val($("#abono_referencia_v_" + id_ingreso).val().trim()).val();
				let abono_datafono = $("#abono_datafono_v_" + id_ingreso).val($("#abono_datafono_v_" + id_ingreso).val().trim()).val();
				
				abono_efectivo = $("#abono_efectivo_v_" + id_ingreso).val().replaceAll(',','');
				abono_transferencia = $("#abono_transferencia_v_" + id_ingreso).val().replaceAll(',','');
				abono_datafono = $("#abono_datafono_v_" + id_ingreso).val().replaceAll(',','');
				
				if (validoAbono && validoTransferenciaConReferencia)
				{
					if (abono_efectivo.length)
						if (isNaN(abono_efectivo))
							validoAbono = false;
						else
							cambio = true;
					if (abono_transferencia.length)
						if (isNaN(abono_transferencia))
							validoAbono = false;
						else
							cambio = true;
						// else
						// 	if (!abono_referencia.length)
						// 		validoTransferenciaConReferencia = false;
						// 	else
						// 		cambio = true;
					if (abono_datafono.length)
						if (isNaN(abono_datafono))
							validoAbono = false;
						else
							cambio = true;
					if (validoAbono)
					{
						let totalAbono = 0;
						if (abono_efectivo.length) totalAbono += Number(abono_efectivo);
						if (abono_transferencia.length) totalAbono += Number(abono_transferencia);
						if (abono_datafono.length) totalAbono += Number(abono_datafono);
						if (totalAbono > total)
							validoAbono = false;
					}
				}
			});
			if (!validoTransferenciaConReferencia && false)
				alertify.alert("","FALTA UNA REFERENCIA PARA UN ABONO DE TRANSFERENCIA").set('label', 'Aceptar');
			else
			{
				if (validoAbono)
				{
					if(cambio)
					{
						var valido;
						valido=true;
						if(valido)
						{
							if(document.getElementById('onombre').value!=document.getElementById('mnombre').value)
							{
								if(document.getElementById('mnombre').value=='')
								{
									valido=false;
									alertify.alert("","EL NOMBRE NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
								}
							}
						}
						if(valido)
						{
							if(document.getElementById('oapellido').value!=document.getElementById('mapellido').value)
							{
								if(document.getElementById('mapellido').value=='')
								{
									valido=false;
									alertify.alert("","EL APELLIDO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
								}
							}
						}
						if(valido)
						{
							if(document.getElementById('otelf').value!=document.getElementById('mtelf').value)
							{
								if(document.getElementById('mtelf').value=='')
								{
									valido=false;
									alertify.alert("","EL TEL\u00C9FONO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
								}
								else
								{
									if(document.getElementById('mtelf').value!='')
									{
										if (!/^([0-9])*$/.test(document.getElementById('mtelf').value))
										{
											valido=false;
											alertify.alert("","EL TEL\u00C9FONO NO ES VALIDO").set('label', 'Aceptar');
										}
									}
								}
							}
						}
						if(valido)
							alertify.confirm('','¿Desea Guardar los cambios?', function(){ alertify.success('Sí');document.getElementById('guardar_modificar').value="true";enviardatos_modificar(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
					}
					else
						alertify.alert("","NO HUBO CAMBIO EN LOS DATOS").set('label', 'Aceptar');
				}
				else
					alertify.alert("","ABONO NO VALIDO").set('label', 'Aceptar');	
			}
		}
	}

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de Clientes</b></h5>
</header>
<form id="flistac" name="flistac" method="post">
	<input type="hidden" id="telf_eliminar" name="telf_eliminar">
	<input type="hidden" id="telf_editar" name="telf_editar">
</form>
<?php
	function guardar($bd)
	{
		global $basedatos;
		$especial = "0";
		if (isset($_POST["especial"]))
			$especial = $_POST["especial"];
		if($bd->insertar_datos(8,$basedatos,"cliente","nombre","apellido","alias","telf","login","fecha_num","especial","correo",$_POST["nombre"],$_POST["apellido"],$_POST["alias"],$_POST["telf"],$_SESSION["login"],time(),$especial,$_POST["correo"]))
			return true;
		else
			return false;
	}

	function validar_exite($bd)
	{
		if($bd->existe(1,"cliente","telf",$_POST["telf"]))
			return true;
		else
			return false;
	}

	function formulario_agregar_cliente()
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
			<h2 class="w3-center">Nuevo Cliente</h2>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="telf"><i class=" icon-phone" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="telf" name="telf" type="text" placeholder="Tel&eacute;fono" maxlength="11" onkeypress="return NumCheck3(event, this)" tabindex="1">
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="nombre"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="nombre" name="nombre" type="text" placeholder="Nombre" maxlength="30" tabindex="2">
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="apellido"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="apellido" name="apellido" type="text" placeholder="Apellido" maxlength="30" tabindex="3">
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="alias"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="alias" name="alias" type="text" placeholder="Alias" maxlength="30" tabindex="4">
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="correo"><i class="icon-mail2" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="correo" name="correo" type="text" placeholder="Correo" maxlength="255" tabindex="5">
				</div>
			</div>
			<div class="w3-row w3-section">
				<label>
					<div class="w3-col" style="width:50px"><input class="w3-check" type="checkbox" id="especial" name="especial" value="1"></div>
					<div class="w3-rest">	
						Especial
					</div>
				</label>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_cliente();" value="Guardar">
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
							<input class="w3-check" type="checkbox" id="chbtelf" name="chbtelf" disabled onclick="if(document.getElementById('chbtelf').checked){document.getElementById('btelf').disabled=false;}else{document.getElementById('btelf').disabled=true;}">
						</td>
						<td>
							<label>
								Tel&eacute;fono
								<input class="w3-input w3-border" type="text" id="btelf" name="btelf" onkeypress="return NumCheck2(event, this)" disabled>
							</label>
						</td>
					</tr>
					<tr>
						<td align="right">
							<input class="w3-check" type="checkbox" id="chbnombre" name="chbnombre" disabled onclick="if(document.getElementById('chbnombre').checked){document.getElementById('bnombre').disabled=false;}else{document.getElementById('bnombre').disabled=true;}">
						</td>
						<td>
							<label>
								Nombre
								<input class="w3-input w3-border" type="text" id="bnombre" name="bnombre" disabled>
							</label>
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
		if(usuario_admin() or usuario_cajero())
		{
			?>
			<div class="w3-container">
				<button id='agregar_cliente' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Agregar Cliente</button>
			</div>
			<?php
			echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
				formulario_agregar_cliente();
			echo"</div>";
			formulario_busqueda($bd);
			echo"<div id='loader'></div>";
			if(isset($_POST["telf"]))
			{
				if(!validar_exite($bd))
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
							alertify.alert("","NO SE PUDO GUARDAR EL CLIENTE").set('label', 'Aceptar');
						</script>
						<?php
					}
				}
				else
				{
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","CLIENTE YA EXISTE").set('label', 'Aceptar');
					</script>
					<?php
				}
			}
			echo"<div id='divformulariolista'></div>";			
		}
		else
		{
			?>
			<div class="w3-panel w3-yellow">
				<h3>Advertencia</h3>
				<p>Acceso Restringido / Solo Administradores o Cajeros</p>
			</div> 
			<?php
		}
	}
?>