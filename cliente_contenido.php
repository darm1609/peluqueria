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
		if(document.getElementById('cliente_cedula').value=='')
		{
			valido=false;
			alertify.alert("","LA CÉDULA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
		}
		else
		{
			if (!/^([0-9])*$/.test(document.getElementById('cliente_cedula').value))
			{
				valido=false;
				alertify.alert("","LA CÉDULA NO ES VALIDA").set('label', 'Aceptar');
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
		{
			if(document.getElementById('telf').value!='')
			{
				if (!/^([0-9])*$/.test(document.getElementById('telf').value))
				{
					valido=false;
					alertify.alert("","EL TELÉFONO NO ES VALIDO").set('label', 'Aceptar');
				}
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
			if(!document.getElementById('chbcedula').checked && !document.getElementById('chbnombre').checked)
			{
				valido=false;
				alertify.alert("","DEBE ESPECIFICAR UNA OPCIÓN DE BUSQUEDA").set('label', 'Aceptar');
			}
			if(document.getElementById('chbcedula').checked)
			{
				if(document.getElementById('bcedula').value=="")
				{
					valido=false;
					alertify.alert("","LA CÉDULA A BUSCAR NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
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
		document.getElementById('chbcedula').disabled=false;
		if(document.getElementById('chbcedula').checked)
			document.getElementById('bcedula').disabled=false;
		else
			document.getElementById('bcedula').disabled=true;
		document.getElementById('chbnombre').disabled=false;
		if(document.getElementById('chbnombre').checked)
			document.getElementById('bnombre').disabled=false;
		else
			document.getElementById('bnombre').disabled=true;
	}

	function deshabilitar_especificar()
	{
		document.getElementById('chbcedula').disabled=true;
		document.getElementById('bcedula').disabled=true;
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
		if(document.getElementById('ocliente_cedula').value!=document.getElementById('mcliente_cedula').value)
			cambio=true;
		if(document.getElementById('onombre').value!=document.getElementById('mnombre').value)
			cambio=true;
		if(document.getElementById('oapellido').value!=document.getElementById('mapellido').value)
			cambio=true;
		if(document.getElementById('oalias').value!=document.getElementById('malias').value)
			cambio=true;
		if(document.getElementById('otelf').value!=document.getElementById('mtelf').value)
			cambio=true;
		let validoAbono = true;
		$(".abono").each(function(e){
			let el = $(this);
			let abono = el.val().trim(el.val());
			let total = Number(el.data("total"));
			if (abono.length)
			{
				cambio = true;
				if (isNaN(abono)) {
					validoAbono = false;
					el.css("border-color: #FF0000;");
				}
				else {
					abono = Number(abono);
					if (abono > total) {
						validoAbono = false;
						el.css("border-color: #FF0000 !important;");
					}
				}
			}
		});
		if (validoAbono)
		{
			if(cambio)
			{
				var valido;
				valido=true;
				if(document.getElementById('ocliente_cedula').value!=document.getElementById('mcliente_cedula').value)
				{
					if(document.getElementById('mcliente_cedula').value=='')
					{
						valido=false;
						alertify.alert("","LA CÉDULA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
					}
					else
					{
						if (!/^([0-9])*$/.test(document.getElementById('mcliente_cedula').value))
						{
							valido=false;
							alertify.alert("","LA CÉDULA NO ES VALIDA").set('label', 'Aceptar');
						}
					}
				}
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
						if(document.getElementById('mtelf').value!='')
						{
							if (!/^([0-9])*$/.test(document.getElementById('mtelf').value))
							{
								valido=false;
								alertify.alert("","EL TELÉFONO NO ES VALIDO").set('label', 'Aceptar');
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

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de Clientes</b></h5>
</header>
<form id="flistac" name="flistac" method="post">
	<input type="hidden" id="cedula_eliminar" name="cedula_eliminar">
	<input type="hidden" id="cedula_editar" name="cedula_editar">
</form>
<?php
	function guardar($bd)
	{
		global $basedatos;
		if($bd->insertar_datos(6,$basedatos,"cliente","cliente_cedula","nombre","apellido","alias","telf","login",$_POST["cliente_cedula"],$_POST["nombre"],$_POST["apellido"],$_POST["alias"],$_POST["telf"],$_SESSION["login"]))
			return true;
		else
			return false;
	}

	function validar_exite($bd)
	{
		if($bd->existe(1,"cliente","cliente_cedula",$_POST["cliente_cedula"]))
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
				<div class="w3-col" style="width:50px"><label for="cliente_cedula"><i class="icon-drivers-license-o" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="cliente_cedula" name="cliente_cedula" type="text" placeholder="C&eacute;dula" onkeypress="return NumCheck2(event, this)" tabindex="1">
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
				<div class="w3-col" style="width:50px"><label for="telf"><i class=" icon-phone" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="telf" name="telf" type="text" placeholder="Tel&eacute;fono" maxlength="11" onkeypress="return NumCheck3(event, this)" tabindex="5">
				</div>
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
							<input class="w3-check" type="checkbox" id="chbcedula" name="chbcedula" disabled onclick="if(document.getElementById('chbcedula').checked){document.getElementById('bcedula').disabled=false;}else{document.getElementById('bcedula').disabled=true;}">
						</td>
						<td>
							<label>
								C&eacute;dula
								<input class="w3-input w3-border" type="text" id="bcedula" name="bcedula" onkeypress="return NumCheck2(event, this)" disabled>
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
				<input class="w3-button w3-block w3-blue" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda();">
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
				<button id='agregar_cliente' class="w3-button w3-blue"><i class='icon-plus4'>&nbsp;</i>Agregar Cliente</button>
			</div>
			<?php
			echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
				formulario_agregar_cliente();
			echo"</div>";
			formulario_busqueda($bd);
			echo"<div id='loader'></div>";
			if(isset($_POST["cliente_cedula"]))
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