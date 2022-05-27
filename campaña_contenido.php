<script type="text/javascript">

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
			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_envio();" value="Enviar">
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