<script type="text/javascript">

    $(document).ready(function(){
		$(function() {
			$(".fecha").datepicker({
				dateFormat:"dd-mm-yy",
				dayNamesMin:[ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
				monthNames:[ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
			});
		});
	});

    function enviardatos_busqueda()
	{
		let valido=true;
		if(document.getElementById('bfecha_desde').value=='')
		{
			valido=false;
			alertify.alert("","LA FECHA DESDE NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
		}
		else
		{
			if(!validaFechaDDMMAAAA(document.getElementById('bfecha_desde').value))
			{
				valido=false;
				alertify.alert("","FECHA DE BUSQUEDA DESDE NO ES VALIDA").set('label', 'Aceptar');
			}
		}
		if (valido) 
		{
			if(document.getElementById('bfecha_hasta').value=='')
			{
				valido=false;
				alertify.alert("","LA FECHA DESDE NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
			}
			else
			{
				if(!validaFechaDDMMAAAA(document.getElementById('bfecha_hasta').value))
				{
					valido=false;
					alertify.alert("","FECHA DE BUSQUEDA DESDE NO ES VALIDA").set('label', 'Aceptar');
				}
			}
		}
		if (valido) 
		{
			let fechaDesde = $("#bfecha_desde").val();
			let fechaHasta = $("#bfecha_hasta").val();
			if (Date.parse(fechaDesde[6] + fechaDesde[7] + fechaDesde[8] + fechaDesde[9] + "/" + fechaDesde[3] + fechaDesde[4] + "/" + fechaDesde[0] + fechaDesde[1]) > Date.parse(fechaHasta[6] + fechaHasta[7] + fechaHasta[8] + fechaHasta[9] + "/" + fechaHasta[3] + fechaHasta[4] + "/" + fechaHasta[0] + fechaHasta[1]))
			{
				valido=false;
				alertify.alert("","FECHA DE BUSQUEDA NO VALIDA").set('label', 'Aceptar');
			}
		}
		if (valido)
		{
			ajax=objetoAjax();
			$("#loader").show();
			$('#loader').html('<div style="display:block;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
			ajax.open("POST","cuadre_de_caja_contenido_lista.php",true);
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 1)
				{
					$('#loader').html('<div style="position:absolute;width:100%;text-align:center;"><img src="imagenes/loader.gif"/></div>');
				}
				if (ajax.readyState == 4)
				{
					$.post("cuadre_de_caja_contenido_lista.php",$("#fbusqueda").serialize(),function(data)
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
<header class="w3-container" style="padding-top:22px">
	<h5><b>Cuadre De Caja</b></h5>
</header>
<?php
    function formulario_busqueda()
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
            <div class="w3-row w3-section">
				<div class="w3-half">
					<label>
						Desde
					</label>
					<?php
						$hoy=date("d-m-Y",time());
					?>
					<input type="text" class="w3-input w3-border fecha" id="bfecha_desde" name="bfecha_desde" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>">
				</div>
				<div class="w3-half">
					<label>
						Hasta
					</label>
					<?php
						$hoy=date("d-m-Y",time());
					?>
					<input type="text" class="w3-input w3-border fecha" id="bfecha_hasta" name="bfecha_hasta" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>">
				</div>
                <div class="w3-row w3-section">
					<br>
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
            formulario_busqueda();
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