<script type="text/javascript">
	
	$(document).ready(function(){
		$("#buscar_cliente").click(function(){
			if($("#fbusqueda").is(':visible'))
			{
				$("#fbusqueda").hide("linear");
				$("#divformulariolista").hide("linear");
			}
			else
			{
				$("#fbusqueda").show("swing");
				$("#divformulariolista").show("swing");
				$("#enviar_todos").prop("checked", false);
			}
		});

		$("#enviar_todos").on("click", function() {
			let elem = $(this);
			if (elem.prop("checked"))
			{
				$("#fbusqueda").hide("linear");
				$("#divformulariolista").hide("linear");
			}
		});

		$("#enviar_sms").on("click", function() {

			alertify.confirm('','¿Desea enviar el mensaje?', function(){ alertify.success('Sí');enviardatos_mensaje(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
			
		});
	});

	function enviardatos_mensaje()
	{
		if($("#fbusqueda").is(':visible')) {
			$("#clientes").empty();
			$("input[name^='sel_cliente_']").each(function (i, obj) {
				if ($(this).prop("checked"))
				{
					let id = $(this).val();
					$("#clientes").append("<input type='hidden' id='cliente_" + id + "' name='cliente_" + id + "' value='" + id +"'>");
				}
			});
		}

		let valido = true;
		let mensaje = $("#mensaje").val($("#mensaje").val().trim()).val();
		if (!mensaje.length)
		{
			valido=false;
			alertify.alert("","EL MENSAJE ESTA VACIO").set('label', 'Aceptar');
		}
		if (valido)
		{
			if (!$("#enviar_todos").prop("checked")) {
				if (!$("input[name^='cliente_']").length)
				{
					valido=false;
					alertify.alert("","NO EXISTEN CLIENTES SELECCIONADOS").set('label', 'Aceptar');
				}
			}
		}
		if (valido)
		{
			ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","campaña_contenido_enviar_sms.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("campaña_contenido_enviar_sms.php",$("#fsms").serialize(),function(data)
					{
						$("#divformularioSMSEnvio").show();
						$("#divformularioSMSEnvio").html(data);
						$("#loader").hide();
					});
				}
			} 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
			ajax.send();
		}
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
			ajax.open("POST","campaña_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("campaña_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Campañas</b></h5>
</header>
<?php
	function formulario($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fsms" name="fsms" method="post">
			<div class="w3-row w3-section">
				<label for="observacion"><b>Mensaje a enviar</b></label>
				<div class="w3-row">
					<div class="w3-rest">
						<textarea style="float: left;width: 100%;height: auto;" id="mensaje" name="mensaje" maxlength="160"></textarea>
					</div>
				</div>
			</div>
			<div class="w3-container">
				<input class="w3-check" type="checkbox" id="enviar_todos" name="enviar_todos" value="1">
				<label for="enviar_todos">&nbsp;&nbsp;&nbsp;Enviar a todos</label>
			</div>
			<div id="clientes"></div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" id="enviar_sms" name="enviar_sms" value="Enviar">
			</div>
		</form>
		<?php
	}

	function formulario_busqueda($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post" style="display: none;">
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
		if(usuario_admin())
		{
            formulario($bd);	
			?>
			<div class="w3-container">
				<button id='buscar_cliente' class="w3-button w3-dulcevanidad"><i class='icon-search3'>&nbsp;</i>Buscar Cliente</button>
			</div>
			<?php		
			echo"<div id='divformularioSMSEnvio'></div>";
			formulario_busqueda($bd);
			echo"<div id='divformulariolista'></div>";
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