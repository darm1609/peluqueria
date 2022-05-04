<script type="text/javascript">

	function habilitar_especificar()
	{
		document.getElementById('chbempleado_telf').disabled=false;
		if(document.getElementById('chbempleado_telf').checked)
			document.getElementById('bempleado_telf').disabled=false;
		else
			document.getElementById('bempleado_telf').disabled=true;
		document.getElementById('chbnombre').disabled=false;
		if(document.getElementById('chbnombre').checked)
			document.getElementById('bnombre').disabled=false;
		else
			document.getElementById('bnombre').disabled=true;
	}

	function deshabilitar_especificar()
	{
		document.getElementById('chbempleado_telf').disabled=true;
		document.getElementById('bempleado_telf').disabled=true;
		document.getElementById('chbnombre').disabled=true;
		document.getElementById('bnombre').disabled=true;	
	}

	function enviardatos_busqueda()
	{
		var valido;
		valido=true;
		if(document.getElementById('especificar').checked)
		{
			if(!document.getElementById('chbempleado_telf').checked && !document.getElementById('chbnombre').checked)
			{
				valido=false;
				alertify.alert("","DEBE ESPECIFICAR UNA OPCIÓN DE BUSQUEDA").set('label', 'Aceptar');
			}
			if(document.getElementById('chbempleado_telf').checked)
			{
				if(document.getElementById('bempleado_telf').value=="")
				{
					valido=false;
					alertify.alert("","EL TELÉFONO A BUSCAR NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
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
			ajax.open("POST","vale_pago_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("vale_pago_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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
		ajax.open("POST","vale_pago_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("vale_pago_contenido_lista.php",$("#form_tabla").serialize(),function(data)
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

	function enviardatos_vale_pago()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","vale_pago_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("vale_pago_contenido_lista.php",$("#fmodificar").serialize(),function(data)
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

	function submit_vale_pago()
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
		$("#monto_transferencia").val($("#monto_transferencia").val().trim());
		$("#monto_efectivo").val($("#monto_efectivo").val().trim());
		$("#referencia").val($("#referencia").val().trim());
		if (valido)
		{
			if (!$("#monto_transferencia").val().length && !$("#monto_efectivo").val().length)
			{
				valido=false;
				alertify.alert("","DEBE COLOCAR UN MONTO").set('label', 'Aceptar');
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
		if(valido)
		{
			document.getElementById('vale_pago_correcto').value="true";
			enviardatos_vale_pago();
		}
	}

	function enviardatos_mostrar()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","vale_pago_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("vale_pago_contenido_lista.php",$("#form_tabla2").serialize(),function(data)
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
		alertify.confirm('','¿Desea Eliminar?', function(){ alertify.success('Sí');enviardatos_mostrar(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Vales y Pagos</b></h5>
</header>
<?php
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
							<input class="w3-check" type="checkbox" id="chbempleado_telf" name="chbempleado_telf" disabled onclick="if(document.getElementById('chbempleado_telf').checked){document.getElementById('bempleado_telf').disabled=false;}else{document.getElementById('bempleado_telf').disabled=true;}">
						</td>
						<td>
							<label>
								Teléfono
								<input class="w3-input w3-border" type="text" id="bempleado_telf" name="bempleado_telf" onkeypress="return NumCheck3(event, this)" disabled>
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
			formulario_busqueda($bd);
			echo"<div id='loader'></div>";
			echo"<div id='divformulariolista'></div>";
		}
	}
?>