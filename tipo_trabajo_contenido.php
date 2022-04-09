<script type="text/javascript">
	$(document).ready(function(){
		$("#agregar_tipo_trabajo").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
		});
	});

	function submit_tipo_trabajo()
	{
		var valido=new Boolean(true);
		if(document.getElementById('trabajo').value=='')
		{
			valido=false;
			alertify.alert("","EL NOMBRE DEL TRABAJO NO PUEDE ESTAR VACIO").set('label', 'Aceptar');
		}
		if(valido)
			document.getElementById('fagregar').submit();
	}

	function enviardatos_lista()
	{
		ajax=objetoAjax();
		$("#loader").show();
		$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
		ajax.open("POST","tipo_trabajo_contenido_lista.php",true);
		ajax.onreadystatechange = function() 
		{
			if (ajax.readyState == 1)
			{
				$('#loader').html('<div style="width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			}
			if (ajax.readyState == 4)
			{
				$.post("tipo_trabajo_contenido_lista.php",$("#flista").serialize(),function(data)
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

	enviardatos_lista();

	function eliminar_trabajo(x)
	{
		document.getElementById("id_motivo_ingreso").value=x;
		alertify.confirm('','Eliminar el trabajo', function(){ alertify.success('Sí');enviardatos_lista(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}
</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administraci&oacute;n de Trabajos</b></h5>
</header>
<form id="flista" name="flista" method="post">
	<input type="hidden" id="id_motivo_ingreso" name="id_motivo_ingreso">
</form>
<?php
	function guardar($bd)
	{
		global $basedatos;
		if($bd->insertar_datos(2,$basedatos,"motivo_ingreso","motivo","login",mb_strtoupper($_POST["trabajo"],'UTF-8'),$_SESSION["login"]))
			return true;
		else
			return false;
	}

	function formulario_agregar_tipo_trabajo()
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
			<h2 class="w3-center">Nuevo Trabajo</h2>
			<div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="trabajo"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="trabajo" name="trabajo" type="text" placeholder="Tipo de Trabajo">
				</div>
			</div>
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_tipo_trabajo();" value="Guardar">
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
			echo"<div id='loader'></div>";
			if(isset($_POST["trabajo"]))
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
						alertify.alert("","NO SE PUDO GUARDAR EL TRABAJO").set('label', 'Aceptar');
					</script>
					<?php
				}
			}
			?>
			<div class="w3-container">
				<button id='agregar_tipo_trabajo' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Agregar Tipo de Trabajo</button>
			</div>
			<?php
			echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
			formulario_agregar_tipo_trabajo();
			echo"</div>";
			echo"<div id='divformulariolista'></div>";
		}
		else
		{
			?>
			<div class="w3-panel w3-yellow">
				<h3>Advertencia</h3>
				<p>Acceso Restringido</p>
			</div> 
			<?php
		}
	}
?>