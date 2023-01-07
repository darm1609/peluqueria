<script type="text/javascript">
	
	$(document).ready(function(){
		$("#fabricantes").click(function(){
			if($("#div_buscar_productos").is(':visible'))
				$("#div_buscar_productos").hide("linear");
			if($("#div_buscar_movimientos").is(':visible'))
				$("#div_buscar_movimientos").hide("linear");
			$("#div_buscar_fabricantes").show("swing");
		});

		$("#productos").click(function(){
			if($("#div_buscar_fabricantes").is(':visible'))
				$("#div_buscar_fabricantes").hide("linear");
			if($("#div_buscar_movimientos").is(':visible'))
				$("#div_buscar_movimientos").hide("linear");
			$("#div_buscar_productos").show("swing");
		});

		$("#movimientos").click(function(){
			if($("#div_buscar_fabricantes").is(':visible'))
				$("#div_buscar_fabricantes").hide("linear");
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
		
	}

	function formulario_busqueda_fabricantes($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
			<div class="w3-container">
				<div class="w3-row w3-section">
					<div class="w3-cell w3-rest">
						<input class="w3-input w3-border" id="buscar_productos" name="buscar_productos" type="text" placeholder="Buscar">
					</div>
					<div class="w3-cell">
						<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda();">
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
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
			<h2 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Busqueda productos</h2>
			<div class="w3-row w3-section">
				<div class="w3-rest">
					<input class="w3-input w3-border" id="buscar_productos" name="buscar_productos" type="text" placeholder="Buscar">
				</div>
			</div>
			<div class="w3-row w3-section">
				<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda();">
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
						<button id='fabricantes' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Fabricantes</button>
					</div>
					<div class="w3-cell">
						<button id='productos' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Productos</button>
					</div>
					<div class="w3-cell">
						<button id='movimientos' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Movimientos</button>
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
			echo"<div id='divformulariolista'></div>";
			
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