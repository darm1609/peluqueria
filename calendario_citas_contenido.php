<?php
    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(usuario_admin() or usuario_cajero())
		{
            ?>
            <header class="w3-container" style="padding-top:22px">
                <h5><i class=" icon-calendar2"></i>&nbsp;<b>Calendario de citas</b></h5>
            </header>

            <form class="w3-container w3-margin" method="post">
            <div class="w3-panel w3-pale-yellow w3-border w3-border-yellow">
            <p><b>En construcción</b></p><br>
            <p>Fecha estimada: 01/06/2022</p>
            </div> 
            </form>
            <?php
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