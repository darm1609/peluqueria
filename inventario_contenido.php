<script type="text/javascript">
	
	$(document).ready(function(){
		$("#fabricantes").click(function(){
			if($("#div_buscar_productos").is(':visible')){
				$("#div_buscar_productos").hide("linear");
				$("#divformulariolistaproductos").hide("linear");
			}
			if($("#div_buscar_movimientos").is(':visible')){
				$("#div_buscar_movimientos").hide("linear");
				$("#divformulariolistamovimientos").hide("linear");
			}
			if($("#div_buscar_reporte").is(':visible')){
				$("#div_buscar_reporte").hide("linear");
				$("#divformulariolistareporte").hide("linear");
			}
			$("#div_buscar_fabricantes").show("swing");
			$("#divformulariolistafabicantes").show("swing");
		});

		$("#productos").click(function(){
			if($("#div_buscar_fabricantes").is(':visible')){
				$("#div_buscar_fabricantes").hide("linear");
				$("#divformulariolistafabicantes").hide("linear");
			}
			if($("#div_buscar_movimientos").is(':visible')){
				$("#div_buscar_movimientos").hide("linear");
				$("#divformulariolistamovimientos").hide("linear");
			}
			if($("#div_buscar_reporte").is(':visible')){
				$("#div_buscar_reporte").hide("linear");
				$("#divformulariolistareporte").hide("linear");
			}
			$("#div_buscar_productos").show("swing");
			$("#divformulariolistaproductos").show("swing");
		});

		$("#movimientos").click(function(){
			if($("#div_buscar_fabricantes").is(':visible')){
				$("#div_buscar_fabricantes").hide("linear");
				$("#divformulariolistafabicantes").hide("linear");
			}
			if($("#div_buscar_productos").is(':visible')){
				$("#div_buscar_productos").hide("linear");
				$("#divformulariolistaproductos").hide("linear");
			}
			if($("#div_buscar_reporte").is(':visible')){
				$("#div_buscar_reporte").hide("linear");
				$("#divformulariolistareporte").hide("linear");
			}
			$("#div_buscar_movimientos").show("swing");
			$("#divformulariolistamovimientos").show("swing");
		});

		$("#reporte").click(function(){
			if($("#div_buscar_fabricantes").is(':visible')){
				$("#div_buscar_fabricantes").hide("linear");
				$("#divformulariolistafabicantes").hide("linear");
			}
			if($("#div_buscar_productos").is(':visible')){
				$("#div_buscar_productos").hide("linear");
				$("#divformulariolistaproductos").hide("linear");
			}
			if($("#div_buscar_movimientos").is(':visible')){
				$("#div_buscar_movimientos").hide("linear");
				$("#divformulariolistamovimientos").hide("linear");
			}
			$("#div_buscar_reporte").show("swing");
			$("#divformulariolistareporte").show("swing");
		});

		$("#agregar_fabricante").click(function(){
			if($("#fagregar_fabricante").is(':visible'))
				$("#fagregar_fabricante").hide("linear");
			else
				$("#fagregar_fabricante").show("swing");
		});

		$("#agregar_productos").click(function(){
			if($("#fagregar_productos").is(':visible'))
				$("#fagregar_productos").hide("linear");
			else
				$("#fagregar_productos").show("swing");
		});

		$("#agregar_movimientos").click(function(){
			if($("#fagregar_movimientos").is(':visible'))
				$("#fagregar_movimientos").hide("linear");
			else
				$("#fagregar_movimientos").show("swing");
		});
	});

	function submit_nuevo_fabricante()
	{
		let valido = true;
		$('#fabricante_nombre').val($.trim($('#fabricante_nombre').val()));
		if($("#fabricante_nombre").val() === '')
		{
			valido=false;
			alertify.alert("","EL FABRICANTE NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
		}
		if(valido)
			$("#fagregar_fabricante").submit();
	}

	function submit_nuevo_producto()
	{
		let valido = true;
		$('#productos_nombre').val($.trim($('#productos_nombre').val()));
		if($("#fabricante_id").val() === '')
		{
			valido=false;
			alertify.alert("","DEBE SELECCIONAR UN FABRICANTE").set('label', 'Aceptar');
		}
		if($("#productos_nombre").val() === '')
		{
			valido=false;
			alertify.alert("","EL PRODUCTO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
		}
		if($("#medida").val() === '')
		{
			valido=false;
			alertify.alert("","DEBE SELECCIONAR UNA MEDIDA").set('label', 'Aceptar');
		}
		if(valido)
			$("#fagregar_productos").submit();
	}

	function submit_nuevo_movimiento()
	{
		let valido = true;
		$('#cantidad').val($.trim($('#cantidad').val()));
		let fecha = $('#fecha').val();
		let fechaFormato = "";
        if (fecha.length)
        {
            let dateFecha = new Date(Date.parse(fecha) + 5 * 60 * 60 * 1000);
            let dia  = dateFecha.getDate();
            let mes  = dateFecha.getMonth() + 1;
            if (Number(dia) < 10)
                dia = "0" + dia.toString();
            if (Number(mes) < 10)
                mes = "0" + mes.toString();
            let anio  = dateFecha.getFullYear();
            let fechaFormato = dia.toString() + "-" + mes + "-" + anio.toString();
			$('#fecha_str').val(fechaFormato);
		}
		else
		{
			valido = false;
			alertify.alert("","DEBE ESTABLECER UNA FECHA").set('label', 'Aceptar');
		}
		if (!$('#producto_id').val().length)
		{
			valido = false;
			alertify.alert("","DEBE SELECCIONAR UN PRODUCTO").set('label', 'Aceptar');
		}
		if (!$('#cantidad').val().length)
		{
			valido = false;
			alertify.alert("","DEBE ESTABLECER UNA CANTIDAD").set('label', 'Aceptar');
		}
		if (valido)
		{
			$("#fagregar_movimientos").submit();
		}
	}

	function enviardatos_busqueda_fabricante()
	{
		ajax=objetoAjax();
		$("#loader").show();
		$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_fabricantes.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_fabricantes.php",$("#fbusqueda_fabricantes").serialize(),function(data)
				{
					$("#divformulariolistafabicantes").show();
					$("#divformulariolistafabicantes").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function enviardatos_busqueda_productos()
	{
		ajax=objetoAjax();
		$("#loader").show();
		$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_productos.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_productos.php",$("#fbusqueda_productos").serialize(),function(data)
				{
					$("#divformulariolistaproductos").show();
					$("#divformulariolistaproductos").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function enviardatos_busqueda_movimientos()
	{
		ajax=objetoAjax();
		$("#loader").show();
		$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_movimientos.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_movimientos.php",$("#fbusqueda_movimientos").serialize(),function(data)
				{
					$("#divformulariolistamovimientos").show();
					$("#divformulariolistamovimientos").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function enviardatos_lista_fabricantes()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_fabricantes.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_fabricantes.php",$("#form_tabla_fabricantes").serialize(),function(data)
				{
					$("#divformulariolistafabicantes").show();
					$("#divformulariolistafabicantes").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function enviardatos_lista_productos()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_productos.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_productos.php",$("#form_tabla_productos").serialize(),function(data)
				{
					$("#divformulariolistaproductos").show();
					$("#divformulariolistaproductos").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function enviardatos_lista_movimientos()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_movimientos.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_movimientos.php",$("#form_tabla_movimientos").serialize(),function(data)
				{
					$("#divformulariolistamovimientos").show();
					$("#divformulariolistamovimientos").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function enviardatos_modificar_fabricantes()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_fabricantes.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_fabricantes.php",$("#fmodificar_fabricante").serialize(),function(data)
				{
					$("#divformulariolistafabicantes").show();
					$("#divformulariolistafabicantes").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function confirmar_modificar_fabricante()
	{
		let valido = true;
		let cambio = false;
		$('#mfabricante_nombre').val($.trim($('#mfabricante_nombre').val()));
		if ($("#ofabricante_nombre").val() != $("#mfabricante_nombre").val())
		{
			if ($("#mfabricante_nombre").val() === '')
			{
				valido=false;
				alertify.alert("","EL FABRICANTE NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
			if(valido)
				alertify.confirm('','¿Desea Guardar los cambios?', function(){ alertify.success('Sí');document.getElementById('guardar_modificar_fabricante').value="true";enviardatos_modificar_fabricantes(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
		}
		else
			alertify.alert("","NO HUBO CAMBIO EN LOS DATOS").set('label', 'Aceptar');
	}

	function enviardatos_modificar_productos()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_productos.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_productos.php",$("#fmodificar_producto").serialize(),function(data)
				{
					$("#divformulariolistaproductos").show();
					$("#divformulariolistaproductos").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

	function confirmar_modificar_productos()
	{
		let valido = true;
		let cambio = false;
		
		$('#mproductos_nombre').val($.trim($('#mproductos_nombre').val()));
		
		if ($("#ofabricante_id").val() != $("#mfabricante_id").val())
		{
			cambio = true;
			if($("#mfabricante_id").val() === '')
			{
				valido=false;
				alertify.alert("","DEBE SELECCIONAR UN FABRICANTE").set('label', 'Aceptar');
			}
		}
		if ($("#oproductos_nombre").val() != $("#mproductos_nombre").val())
		{
			cambio = true;
			if ($("#mproductos_nombre").val() === '')
			{
				valido=false;
				alertify.alert("","EL PRODUCTO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
			}
		}
		if ($("#omedida").val() != $("#mmedida").val())
		{
			cambio = true;
			if ($("#mmedida").val() === '')
			{
				valido=false;
				alertify.alert("","DEBE SELECCIONAR UNA MEDIDA").set('label', 'Aceptar');
			}
		}

		if (!cambio)
		{
			alertify.alert("","NO HUBO CAMBIO EN LOS DATOS").set('label', 'Aceptar');
			return;
		}
			
		if(valido)
			alertify.confirm('','¿Desea Guardar los cambios?', function(){ alertify.success('Sí');document.getElementById('guardar_modificar_producto').value="true";enviardatos_modificar_productos(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}

	function confirmar_eliminar_movimiento(id)
	{
		alertify.confirm('','¿Desea eliminar el ingreso?', function(){ alertify.success('Sí');enviardatos_lista_movimientos(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}

	function enviardatos_busqueda_reporte()
	{
		let valido = true;
        let fecha = $("#bfecha").val();

        if (!fecha.length)
        {
            valido = false;
            alertify.alert("","DEBE SELECCIONAR UNA FECHA").set('label', 'Aceptar');
        }
        else 
        {
            let dateFecha = new Date(Date.parse(fecha) + 5 * 60 * 60 * 1000);
            let dia  = dateFecha.getDate();
            let mes  = dateFecha.getMonth() + 1;
            if (Number(dia) < 10)
                dia = "0" + dia.toString();
            if (Number(mes) < 10)
                mes = "0" + mes.toString();
            let anio  = dateFecha.getFullYear();
            let fechaFormato = mes + "/" + dia + "/" + anio.toString();
			let fechaFormato2 = dia + "-" + mes + "-" + anio.toString();
            let fechaUnix = Number(Date.parse(fechaFormato)) / 1000;
			$("#bfecha2").val(fechaFormato2);
            $("#bfecha_unix").val(fechaUnix.toString());
        }

		if (valido)
        {
            ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","inventario_contenido_lista_reporte.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("inventario_contenido_lista_reporte.php",$("#fbusqueda_reporte").serialize(),function(data)
				    {
				    	$("#divformulariolistareporte").show();
						$("#divformulariolistareporte").html(data);
				    	$("#loader").hide();
				    });
				}
			} 
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
			ajax.send();
        }
	}

	function enviardatos_lista_reporte()
	{
		ajax=objetoAjax();
		$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","inventario_contenido_lista_reporte.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("inventario_contenido_lista_reporte.php",$("#form_tabla_reporte").serialize(),function(data)
				{
					$("#divformulariolistareporte").show();
					$("#divformulariolistareporte").html(data);
					$("#loader").hide();
				});
			}
		} 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send();
	}

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de inventario</b></h5>
</header>
<?php

	function guardar_productos($bd)
	{
		global $basedatos;
		if ($bd->insertar_datos(3,$basedatos,"productos","id_fabricante","nombre","medida",$_POST["fabricante_id"],$_POST["productos_nombre"],$_POST["medida"]))
			return true;
		else
			return false;
	}

	function guardar_fabricante($bd)
	{
		global $basedatos;
		if ($bd->insertar_datos(1,$basedatos,"fabricantes","nombre",$_POST["fabricante_nombre"]))
			return true;
		else
			return false;
	}

	function guardar_movimientos($bd)
	{
		global $basedatos;
		$fecha_num = strtotime($_POST["fecha_str"][6].$_POST["fecha_str"][7].$_POST["fecha_str"][8].$_POST["fecha_str"][9]."-".$_POST["fecha_str"][3].$_POST["fecha_str"][4]."-".$_POST["fecha_str"][0].$_POST["fecha_str"][1]);
		if ($bd->insertar_datos(5,$basedatos,"productos_movimientos","id_producto","fecha_num","fecha","entrada_salida","cantidad",$_POST["producto_id"],$fecha_num,$_POST["fecha_str"],$_POST["entrada_salida"],$_POST["cantidad"]))
			return true;
		else
			return false;
	}

	function formulario_busqueda_fabricantes($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda_fabricantes" name="fbusqueda_fabricantes" method="post">
			<h4 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Buscar fabricantes</h4>
			<div class="w3-container">
				<div class="w3-row w3-section">
					<div class="w3-cell w3-rest">
						<input class="w3-input w3-border" id="buscar_fabricantes" name="buscar_fabricantes" type="text" placeholder="Buscar">
					</div>
					<div class="w3-cell">
						<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda_fabricante();">
					</div>
				</div>
			</div>
		</form>
		<div class="w3-container">
			<div class="w3-row w3-section">
				<div class="w3-cell">
					<button id='agregar_fabricante' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Agregar</button>
				</div>
			</div>
		</div>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fagregar_fabricante" name="fagregar_fabricante" method="post" style="display:none;">
			<div class="w3-row w3-section">
				<label for="trabajo" class='w3-text-blue'><b>Nombre&nbsp;del&nbsp;fabricante</b></label>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="fabricante_nombre" name="fabricante_nombre" type="text" placeholder="Fabricante">
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_nuevo_fabricante();" value="Guardar">
			</div>
		</form>
		<?php
	}

	function formulario_busqueda_productos($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda_productos" name="fbusqueda_productos" method="post">
			<h4 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Buscar productos</h4>
			<div class="w3-container">
				<div class="w3-row w3-section">
					<div class="w3-cell w3-rest">
						<input class="w3-input w3-border" id="buscar_productos" name="buscar_productos" type="text" placeholder="Buscar">
					</div>
					<div class="w3-cell">
						<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar_buscar_productos" name="enviar" value="Buscar" onclick="return enviardatos_busqueda_productos();">
					</div>
				</div>
			</div>
		</form>
		<div class="w3-container">
			<div class="w3-row w3-section">
				<div class="w3-cell">
					<button id='agregar_productos' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Agregar</button>
				</div>
			</div>
		</div>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fagregar_productos" name="fagregar_productos" method="post" style="display:none;">
			<div class="w3-row w3-section">
				<label for="trabajo" class='w3-text-blue'><b>Fabricantes</b></label>
				<div class="w3-rest" id="select_fabricantes_reload">
					<select class="w3-input w3-border" id="fabricante_id" name="fabricante_id">
						<option value=''></option>
						<?php
							$sql = "SELECT * FROM fabricantes;";
							$result = $bd->mysql->query($sql);
							unset($sql);
							if ($result)
							{
								while($row = $result->fetch_array())
								{
									echo"<option value='".$row["id_fabricante"]."'>".$row["nombre"]."</option>";
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
				<label for="trabajo" class='w3-text-blue'><b>Nombre&nbsp;del&nbsp;producto</b></label>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="productos_nombre" name="productos_nombre" type="text" placeholder="Producto">
				</div>
			</div>
			<div class="w3-row w3-section">
				<label for="medida" class='w3-text-blue'><b>Medida</b></label>
				<div class="w3-rest">
					<select class="w3-input w3-border" id="medida" name="medida">
						<option value=""></option>
						<option value="unidad">Unidad</option>
						<option value="miligramos">Miligamos</option>
						<option value="gramos">Gramos</option>
						<option value="Kilogramos">Kilogramos</option>
						<option value="litros">Litros</option>
						<option value="mililitros">Mililitros</option>
						<option value="onzas">Onzas</option>
					</select>
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_nuevo_producto();" value="Guardar">
			</div>
		</form>
		<?php
	}

	function formulario_busqueda_movimientos($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda_movimientos" name="fbusqueda_movimientos" method="post">
			<h4 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Buscar movimientos</h4>
			<div class="w3-container">
				<div class="w3-row w3-section">
					<div class="w3-cell w3-rest">
						<input class="w3-input w3-border" id="buscar_movimientos" name="buscar_movimientos" type="text" placeholder="Buscar">
					</div>
					<div class="w3-cell">
						<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar_buscar_movimientos" name="enviar" value="Buscar" onclick="return enviardatos_busqueda_movimientos();">
					</div>
				</div>
			</div>
		</form>
		<div class="w3-container">
			<div class="w3-row w3-section">
				<div class="w3-cell">
					<button id='agregar_movimientos' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Agregar</button>
				</div>
			</div>
		</div>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fagregar_movimientos" name="fagregar_movimientos" method="post" style="display:none;">
			<input type="hidden" id="fecha_str" name="fecha_str">
			<div class="w3-row w3-section">
				<label for="fecha" class='w3-text-blue'><b>Fecha</b></label>
				<div class="w3-rest">
					<input type='date' class='w3-input w3-border' id='fecha' name='fecha'>
				</div>
			</div>
			<div class="w3-row w3-section">
				<label for="producto_id" class='w3-text-blue'><b>Producto</b></label>
				<div class="w3-rest">
					<select class="w3-input w3-border" id="producto_id" name="producto_id">
						<option value=''></option>
						<?php
							$sql = "SELECT p.id_producto id, p.nombre producto, f.nombre fabricante, p.medida FROM productos p INNER JOIN fabricantes f on p.id_fabricante = f.id_fabricante;";
							$result = $bd->mysql->query($sql);
							unset($sql);
							if ($result)
							{
								while($row = $result->fetch_array())
								{
									echo"<option value='".$row["id"]."'>".$row["producto"]." (".$row["fabricante"].") (".$row["medida"].")</option>";
								}
								unset($row);
								$result->free();
							}
							unset($result);
						?>
					</select>
				</div>
			</div>
			<div class="w3-row w3-section">
				<label>
					<input class="w3-radio" type="radio" id="entrada_salida" name="entrada_salida" value="Entrada" checked>
					Entrada
				</label>
				<label>
					<input class="w3-radio" type="radio" id="entrada_salida" name="entrada_salida" value="Salida">
					Salida
				</label>
			</div>
			<div class="w3-row w3-section">
				<label for="medida" class='w3-text-blue'><b>Cantidad</b></label>
				<div class="w3-rest">
					<input type="text" class="w3-input w3-border" inputmode="decimal" data-type="currency" id="cantidad" name="cantidad" placeholder="Cantidad" min=0>
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_nuevo_movimiento();" value="Guardar">
			</div>
		</form>
		<?php
	}

	function formulario_busqueda_reporte($bd)
	{
		?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda_reporte" name="fbusqueda_reporte" method="post">
            <div class="w3-row w3-section">
                <div class="w3-col" style="width:50px"><label for="bfecha"><i class="icon-calendar2" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input type="hidden" id="bfecha2" name="bfecha2">
                    <input type="hidden" id="bfecha_unix" name="bfecha_unix">
                    <input type='date' class='w3-input w3-border' id='bfecha' name='bfecha'>
				</div>
            </div>
            <div class="w3-row w3-section">
                <div class="w3-row w3-section">
                    <input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar" name="enviar" value="Consultar" onclick="return enviardatos_busqueda_reporte();">
                </div>
            </div>
        </form>
        <?php
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if ($bd->conectado)
	{
		if (usuario_admin() or usuario_cajero())
		{
			?>
			<div class="w3-container">
				<div class="w3-row w3-section">
					<div class="w3-cell">
						<button id='fabricantes' class="w3-button w3-dulcevanidad">Fabricantes</button>
					</div>
					<div class="w3-cell">
						<button id='productos' class="w3-button w3-dulcevanidad" onclick="return select_fabricantes_reload();">Productos</button>
					</div>
					<div class="w3-cell">
						<button id='movimientos' class="w3-button w3-dulcevanidad">Movimientos</button>
					</div>
					<div class="w3-cell">
						<button id='reporte' class="w3-button w3-dulcevanidad">Reporte</button>
					</div>
				</div>
			</div>
			<?php

			echo"<div id='div_buscar_fabricantes' class='w3-container' style='display:none;'>";
				formulario_busqueda_fabricantes($bd);
			echo"</div>";
			echo"<div id='div_buscar_productos' class='w3-container' style='display:none;'>";
				formulario_busqueda_productos($bd);
			echo"</div>";
			echo"<div id='div_buscar_movimientos' class='w3-container' style='display:none;'>";
				formulario_busqueda_movimientos($bd);
			echo"</div>";
			echo"<div id='div_buscar_reporte' class='w3-container' style='display:none;'>";
				formulario_busqueda_reporte($bd);
			echo"</div>";
			echo"<div id='loader'></div>";
			echo"<div id='divformulariolistafabicantes' style='display:none;'></div>";
			echo"<div id='divformulariolistaproductos' style='display:none;'></div>";
			echo"<div id='divformulariolistamovimientos' style='display:none;'></div>";
			echo"<div id='divformulariolistareporte' style='display:none;'></div>";
			
			if (isset($_POST["fabricante_nombre"]))//Agregar fabricante
			{
				if (guardar_fabricante($bd))
				{
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","GUARDADO SATISFACTORIAMENTE").set('label', 'Aceptar');
						select_fabricantes_reload();
					</script>
					<?php
				}
				else
				{
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","NO SE PUDO GUARDAR EL FABRICANTE").set('label', 'Aceptar');
					</script>
					<?php
				}
			}

			if (isset($_POST["fabricante_id"]) && isset($_POST["productos_nombre"]))//Agregar productos
			{
				if (guardar_productos($bd))
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
						alertify.alert("","NO SE PUDO GUARDAR EL PRODUCTO").set('label', 'Aceptar');
					</script>
					<?php
				}
			}

			if (isset($_POST["fecha"]) && isset($_POST["producto_id"]))
			{
				if (guardar_movimientos($bd))
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
						alertify.alert("","NO SE PUDO GUARDAR EL MOVIMIENTO").set('label', 'Aceptar');
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
				<p>Acceso Restringido / Solo Administradores o Cajeros</p>
			</div> 
			<?php
		}
	}
?>