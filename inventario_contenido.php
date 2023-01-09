<script type="text/javascript">
	
	$(document).ready(function(){
		$("#fabricantes").click(function(){
			if($("#div_buscar_productos").is(':visible'))
				$("#div_buscar_productos").hide("linear");
			if($("#div_buscar_movimientos").is(':visible'))
				$("#div_buscar_movimientos").hide("linear");
			$("#div_buscar_fabricantes").show("swing");
			$("#divformulariolistafabicantes").show("swing");
		});

		$("#productos").click(function(){
			if($("#div_buscar_fabricantes").is(':visible')){
				$("#div_buscar_fabricantes").hide("linear");
				$("#divformulariolistafabicantes").hide("linear");
			}
			if($("#div_buscar_movimientos").is(':visible'))
				$("#div_buscar_movimientos").hide("linear");
			$("#div_buscar_productos").show("swing");
		});

		$("#movimientos").click(function(){
			if($("#div_buscar_fabricantes").is(':visible')){
				$("#div_buscar_fabricantes").hide("linear");
				$("#divformulariolistafabicantes").hide("linear");
			}
			if($("#div_buscar_productos").is(':visible'))
				$("#div_buscar_productos").hide("linear");
			$("#div_buscar_movimientos").show("swing");
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

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de inventario</b></h5>
</header>
<form id="flistac" name="flistac" method="post">
	<input type="hidden" id="telf_eliminar" name="telf_eliminar">
	<input type="hidden" id="telf_editar" name="telf_editar">
</form>
<?php

	function guardar($bd)
	{
		global $basedatos;
		if($bd->insertar_datos(1,$basedatos,"fabricante","nombre",$_POST["fabricante_nombre"]))
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
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda_fabricantes" name="fbusqueda_fabricantes" method="post">
			<h4 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Buscar productos</h4>
			<div class="w3-container">
				<div class="w3-row w3-section">
					<div class="w3-cell w3-rest">
						<input class="w3-input w3-border" id="buscar_productos" name="buscar_productos" type="text" placeholder="Buscar">
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
					<button id='agregar_productos' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Agregar</button>
				</div>
			</div>
		</div>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fagregar_productos" name="fagregar_productos" method="post" style="display:none;">
			<div class="w3-row w3-section">
				<label for="trabajo" class='w3-text-blue'><b>Nombre&nbsp;del&nbsp;producto</b></label>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="productos_nombre" name="productos_nombre" type="text" placeholder="Producto">
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
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
			<h2 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Busqueda movimientos</h2>
			<div class="w3-row w3-section">
				<div class="w3-rest">
					<input class="w3-input w3-border" id="buscar_movimientos" name="buscar_movimientos" type="text" placeholder="Buscar">
				</div>
			</div>
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
				<div class="w3-row w3-section">
					<div class="w3-cell">
						<button id='fabricantes' class="w3-button w3-dulcevanidad">Fabricantes</button>
					</div>
					<div class="w3-cell">
						<button id='productos' class="w3-button w3-dulcevanidad">Productos</button>
					</div>
					<div class="w3-cell">
						<button id='movimientos' class="w3-button w3-dulcevanidad">Movimientos</button>
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
			echo"<div id='loader'></div>";
			echo"<div id='divformulariolistafabicantes' style='display:none;'></div>";
			
			if (isset($_POST["fabricante_nombre"]))//Agregar fabricante
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
						alertify.alert("","NO SE PUDO GUARDAR EL FABRICANTE").set('label', 'Aceptar');
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