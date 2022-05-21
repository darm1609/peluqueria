<script type="text/javascript">
	
	$(document).ready(function(){
		$("#agregar_empleado").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
		});
	});

	function submit_empleado()
	{
		var valido=new Boolean(true);
		if(document.getElementById('empleado_telf').value == '')
		{
			valido=false;
			alertify.alert("","EL TELÉFONO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
		}
		else 
		{
			if (!/^([0-9])*$/.test(document.getElementById('empleado_telf').value))
			{
				valido=false;
				alertify.alert("","EL TELÉFONO NO ES VALIDO").set('label', 'Aceptar');
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
			if(!document.getElementById('chbempleado_telf').checked /*&& !document.getElementById('chbnombre').checked*/)
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
			// if(document.getElementById('chbnombre').checked)
			// {
			// 	if(document.getElementById('bnombre').value=="")
			// 	{
			// 		valido=false;
			// 		alertify.alert("","EL NOMBRE A BUSCAR NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			// 	}
			// }
		}
		if(valido)
		{
			ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","empleado_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("empleado_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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
		document.getElementById('chbempleado_telf').disabled=false;
		if(document.getElementById('chbempleado_telf').checked)
			document.getElementById('bempleado_telf').disabled=false;
		else
			document.getElementById('bempleado_telf').disabled=true;
		// document.getElementById('chbnombre').disabled=false;
		// if(document.getElementById('chbnombre').checked)
		// 	document.getElementById('bnombre').disabled=false;
		// else
		// 	document.getElementById('bnombre').disabled=true;
	}

	function deshabilitar_especificar()
	{
		document.getElementById('chbempleado_telf').disabled=true;
		document.getElementById('bempleado_telf').disabled=true;
		// document.getElementById('chbnombre').disabled=true;
		// document.getElementById('bnombre').disabled=true;
	}

	function enviardatos_lista()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","empleado_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("empleado_contenido_lista.php",$("#form_tabla").serialize(),function(data)
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
		ajax.open("POST","empleado_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("empleado_contenido_lista.php",$("#fmodificar").serialize(),function(data)
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
		if(document.getElementById('oempleado_telf').value!=document.getElementById('mempleado_telf').value)
			cambio=true;
		if(document.getElementById('onombre').value!=document.getElementById('mnombre').value)
			cambio=true;
		if(document.getElementById('oapellido').value!=document.getElementById('mapellido').value)
			cambio=true;
		if(document.getElementById('ogenero').value!=document.getElementById('mgenero').value)
			cambio=true;
		if(document.getElementById('ocorreo').value!=document.getElementById('mcorreo').value)
			cambio=true;
		if(document.getElementById('ocolor').value!=document.getElementById('mcolor').value)
			cambio=true;
		let mvisible = $("#mvisible").is(':checked') ? "1" : "0";
		let ovisible = $("#ovisible").val();
		if (ovisible != mvisible)
			cambio = true;
		if(cambio)
		{
			var valido = true;
			if(document.getElementById('oempleado_telf').value!=document.getElementById('mempleado_telf').value)
			{
				if(document.getElementById('mempleado_telf').value=='')
				{
					valido=false;
					alertify.alert("","EL TELÉFONO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
				}
				else
				{
					if (!/^([0-9])*$/.test(document.getElementById('mempleado_telf').value))
					{
						valido=false;
						alertify.alert("","EL TELÉFONO NO ES VALIDO").set('label', 'Aceptar');
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
				alertify.confirm('','¿Desea Guardar los cambios?', function(){ alertify.success('Sí');document.getElementById('guardar_modificar').value="true";enviardatos_modificar(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
		}
		else
			alertify.alert("","NO HUBO CAMBIO EN LOS DATOS").set('label', 'Aceptar');
	}

	function enviardatos_porcentaje()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","empleado_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("empleado_contenido_lista.php",$("#fporcentaje").serialize(),function(data)
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

	var nextinput=0;

	function agregar_campos(arreglo,n)
	{
		nextinput++;
		campo="<div id='contenido_"+nextinput+"' class='w3-row w3-section' style='border:1px solid #cccccc;padding:5px;'>";
		campo+="<select class='w3-select w3-border' id='sel_motivo_"+nextinput+"' name='sel_motivo_"+nextinput+"'>";
		campo+="<option value=''>Tipo de Trabajo</option>";
		var i;
		for(i=0;i<n;i++)
		{
			campo+="<option value='"+arreglo[i][0]+"'>"+arreglo[i][1]+"</option>";
		}
		campo+="</select>";
		campo+="<label for='porcentaje_empleado_motivo_"+nextinput+"' class='w3-text-blue'><b>% Empleado</b></label>";
		campo+="<input class='w3-input w3-border' id='porcentaje_empleado_motivo_"+nextinput+"' name='porcentaje_empleado_motivo_"+nextinput+"' type='text' placeholder='%' onkeypress='return NumCheck(event, this)'>";
		campo+="<label for='porcentaje_peluqueria_motivo_"+nextinput+"' class='w3-text-blue'><b>% Empresa</b></label>";
		campo+="<input class='w3-input w3-border' id='porcentaje_peluqueria_motivo_"+nextinput+"' name='porcentaje_peluqueria_motivo_"+nextinput+"' type='text' placeholder='%' onkeypress='return NumCheck(event, this)'>";
		campo+="<label for='porcentaje_dueño_motivo_"+nextinput+"' class='w3-text-blue'><b>% Dueño</b></label>";
		campo+="<input class='w3-input w3-border' id='porcentaje_dueño_motivo_"+nextinput+"' name='porcentaje_dueño_motivo_"+nextinput+"' type='text' placeholder='%' onkeypress='return NumCheck(event, this)'>";
		campo+="</div>";
		$("#div_por_motivo").append(campo);
	}

	function eliminar_campos()
	{
		if(nextinput>=1)
		{
			$("#contenido_"+nextinput).remove();
			nextinput--;
		}
	}

	function submit_porcentaje()
	{
		var valido=new Boolean(true);
		debugger;
		if(document.getElementById('fecha_porcentaje').value=='')
		{
			valido=false;
			alertify.alert("","LA FECHA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
		}
		else
		{
			if(!validaFechaDDMMAAAA(document.getElementById('fecha_porcentaje').value))
			{
				valido=false;
				alertify.alert("","LA FECHA NO ES VALIDA").set('label', 'Aceptar');
			}
		}
		if(document.getElementById('porcentaje_empleado').value=='')
		{
			valido=false;
			alertify.alert("","PORCENTAJE DEL EMPLEADO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
		}
		else
		{
			if(!/^[0-9]+([.][0-9]+)?$/.test(document.getElementById('porcentaje_empleado').value))
			{
				valido=false;
				alertify.alert("","PORCENTAJE DEL EMPLEADO NO VALIDO").set('label', 'Aceptar');
			}
		}
		if(valido)
		{
			if(document.getElementById('porcentaje_peluqueria').value=='')
			{
				valido=false;
				alertify.alert("","PORCENTAJE DE LA EMPRESA NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
			else
			{
				if(!/^[0-9]+([.][0-9]+)?$/.test(document.getElementById('porcentaje_peluqueria').value))
				{
					valido=false;
					alertify.alert("","PORCENTAJE DE LA EMPRESA NO VALIDO").set('label', 'Aceptar');
				}
			}
		}
		if(valido)
		{
			if(document.getElementById('porcentaje_dueño').value=='')
			{
				valido=false;
				alertify.alert("","PORCENTAJE DEL DUEÑO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
			else
			{
				if(!/^[0-9]+([.][0-9]+)?$/.test(document.getElementById('porcentaje_dueño').value))
				{
					valido=false;
					alertify.alert("","PORCENTAJE DEL DUEÑO NO VALIDO").set('label', 'Aceptar');
				}
			}
		}
		if(valido)
		{
			x=document.getElementById('porcentaje_dueño').value;
			y=document.getElementById('porcentaje_peluqueria').value;
			z=document.getElementById('porcentaje_empleado').value;
			suma=Number(x)+Number(y)+Number(z);
			if(suma!=100)
			{
				valido=false;
				alertify.alert("","LOS PORCENTAJES DEBEN SUMAR 100%").set('label', 'Aceptar');
			}
		}
		var i;
		for(i=1;i<=nextinput;i++)
		{
			if(document.getElementById("sel_motivo_"+i).value=="")
			{
				valido=false;
				alertify.alert("","DEBE SELECCIONAR UN TIPO DE TRABAJO").set('label', 'Aceptar');
				break;
			}
			if(valido)
			{
				if(document.getElementById("porcentaje_empleado_motivo_"+i).value=="")
				{
					valido=false;
					alertify.alert("","PORCENTAJE DEL EMPLEADO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
					break;
				}
				else
				{
					if(!/^[0-9]+([.][0-9]+)?$/.test(document.getElementById("porcentaje_empleado_motivo_"+i).value))
					{
						valido=false;
						alertify.alert("","PORCENTAJE DEL EMPLEADO NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
			if(valido)
			{
				if(document.getElementById("porcentaje_peluqueria_motivo_"+i).value=="")
				{
					valido=false;
					alertify.alert("","PORCENTAJE DE LA EMPRESA NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
					break;
				}
				else
				{
					if(!/^[0-9]+([.][0-9]+)?$/.test(document.getElementById("porcentaje_peluqueria_motivo_"+i).value))
					{
						valido=false;
						alertify.alert("","PORCENTAJE DE LA EMPRESA NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
			if(valido)
			{
				if(document.getElementById("porcentaje_dueño_motivo_"+i).value=="")
				{
					valido=false;
					alertify.alert("","PORCENTAJE DEL DUEÑO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
					break;
				}
				else
				{
					if(!/^[0-9]+([.][0-9]+)?$/.test(document.getElementById("porcentaje_dueño_motivo_"+i).value))
					{
						valido=false;
						alertify.alert("","PORCENTAJE DEL DUEÑO NO VALIDO").set('label', 'Aceptar');
					}
				}
			}
			if(valido)
			{
				x=document.getElementById("porcentaje_dueño_motivo_"+i).value;
				y=document.getElementById("porcentaje_peluqueria_motivo_"+i).value;
				z=document.getElementById("porcentaje_empleado_motivo_"+i).value;
				suma=parseInt(x)+parseInt(y)+parseInt(z);
				if(suma!=100)
				{
					valido=false;
					alertify.alert("","LOS PORCENTAJES DEBEN SUMAR 100%").set('label', 'Aceptar');
				}
			}
		}
		if(valido)
		{
			document.getElementById('porcentajes_correctos').value="true";
			document.getElementById('porcentajes_motivos').value=nextinput;
			enviardatos_porcentaje();
		}
	}

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de Empleados</b></h5>
</header>
<form id="flistae" name="flistae" method="post">
	<input type="hidden" id="telf_eliminar" name="telf_eliminar">
	<input type="hidden" id="telf_editar" name="telf_editar">
</form>
<?php
	function guardar($bd)
	{
		global $basedatos;
		if($bd->insertar_datos(9,$basedatos,"empleado","empleado_telf","nombre","apellido","genero","correo","login","dueño","color","visible",$_POST["empleado_telf"],$_POST["nombre"],$_POST["apellido"],$_POST["genero"],$_POST["correo"],$_SESSION["login"],"0",$_POST["color"],"1"))
			return true;
		else
			return false;
	}

	function validar_exite($bd)
	{
		if($bd->existe(1,"empleado","empleado_telf",$_POST["empleado_telf"]))
			return true;
		else
			return false;
	}

	function formulario_agregar_empleado()
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
			<h2 class="w3-center">Nuevo Empleado</h2>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="empleado_telf"><i class="icon-drivers-license-o" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="empleado_telf" name="empleado_telf" type="text" placeholder="Tel&eacute;fono" onkeypress="return NumCheck3(event, this)" maxlength="20" tabindex="1">
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
			<div class="w3-row w3-section" style="text-align:center;">
				<label>
					<i class="icon-mars" style="font-size:37px;"></i>&nbsp;
					<input type="radio" class="w3-radio" id="genero" name="genero" value="m" tabindex="5">
				</label>
				<label>
					<i class="icon-venus" style="font-size:37px;"></i>&nbsp;
					<input type="radio" class="w3-radio" id="genero" name="genero" value="f" tabindex="4" checked>
				</label>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="correo"><i class="icon-mail2" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="correo" name="correo" type="text" placeholder="Correo Electr&oacute;nico" maxlength="255" tabindex="7">
				</div>
			</div>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="color"><i class="icon-color-mode" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input type="color" id="color" name="color" value="#f99fbf">
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_empleado();" value="Guardar">
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
					<!-- <tr>
						<td align="right">
							<input class="w3-check" type="checkbox" id="chbempleado_telf" name="chbempleado_telf" disabled onclick="if(document.getElementById('chbempleado_telf').checked){document.getElementById('bempleado_telf').disabled=false;}else{document.getElementById('bempleado_telf').disabled=true;}">
						</td>
						<td>
							<label>
								Tel&eacute;fono
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
					</tr> -->
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
		if(usuario_admin())
		{
			?>
			<div class="w3-container">
				<button id="agregar_empleado" class="w3-button w3-dulcevanidad"><i class="icon-plus4">&nbsp;</i>Agregar Empleado</button>
			</div>
			<?php
			echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
				formulario_agregar_empleado();
			echo"</div>";
			formulario_busqueda($bd);
			echo"<div id='loader'></div>";
			if(isset($_POST["empleado_telf"]))
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
							alertify.alert("","NO SE PUDO GUARDAR EL EMPLEADO").set('label', 'Aceptar');
						</script>
						<?php
					}
				}
				else
				{
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","EMPLEADO YA EXISTE").set('label', 'Aceptar');
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
				<p>Acceso Restringido / Solo Administradores</p>
			</div> 
			<?php
		}
	}
?>