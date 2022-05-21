<script type="text/javascript">
    
    function enviardatos_busqueda()
	{
        let valido = true;
        let fecha = $("#bfecha").val();
        let empleado = $("#empleado_telf").val();

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
            let fechaUnix = Number(Date.parse(fechaFormato)) / 1000;
            $("#bfecha_unix").val(fechaUnix.toString());
        }

        // if (valido)
        // {
        //     if (!empleado.length)
        //     {
        //         valido = false;
        //         alertify.alert("","DEBE SELECCIONAR UN EMPLEADO").set('label', 'Aceptar');
        //     }   
        // }

        if (valido)
        {
            ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","calendario_citas_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("calendario_citas_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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
    }

</script>
<?php
    function formulario_consulta($bd)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
            <div class="w3-row w3-section">
                <div class="w3-col" style="width:50px"><label for="bfecha"><i class="icon-calendar2" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
                    <input type="hidden" id="bfecha_unix" name="bfecha_unix">
                    <input type='date' class='w3-input w3-border' id='bfecha' name='bfecha'>
				</div>
            </div>
            <div class="w3-row w3-section">
                <div class="w3-col" style="width:50px"><label for="bfecha"><i class="icon-id-badge" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
                    <select class="w3-select" id="empleado_telf" name="empleado_telf">
						<option value="">Empleado</option>
						<?php
							$sql="SELECT empleado_telf, nombre, apellido FROM empleado where visible = '1';";
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
				</div>
            </div>
            <div class="w3-row w3-section">
                <div class="w3-row w3-section">
                    <input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar" name="enviar" value="Consultar" onclick="return enviardatos_busqueda();">
                </div>
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
            <header class="w3-container" style="padding-top:22px">
                <h5><i class="icon-calendar2"></i>&nbsp;<b>Calendario de citas</b></h5>
            </header>
            <?php
            formulario_consulta($bd);
            echo"<div id='loader'></div>";
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