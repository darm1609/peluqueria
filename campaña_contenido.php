<header class="w3-container" style="padding-top:22px">
	<h5><b>Campañas</b></h5>
</header>
<?php
    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(usuario_admin())
		{
            
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