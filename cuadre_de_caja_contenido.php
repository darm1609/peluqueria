<script type="text/javascript">

    $(document).ready(function(){
		$(function() {
			$("#bfecha").datepicker({
				dateFormat:"dd-mm-yy",
				dayNamesMin:[ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
				monthNames:[ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
			});
		});
	});

    function enviardatos_busqueda()
	{
		let valido=true;
		if(document.getElementById('bfecha').value=='')
		{
			valido=false;
			alertify.alert("","LA FECHA DE CONSULTA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
		}
		else
		{
			if(!validaFechaDDMMAAAA(document.getElementById('bfecha').value))
			{
				valido=false;
				alertify.alert("","LA FECHA DE BUSQUEDA NO ES VALIDA").set('label', 'Aceptar');
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
                <table border="0" style="width: 100%;">
                    <tr>
                        <td>
                            <label>
                                Fecha
                            </label>
                            <?php
                                $hoy=date("d-m-Y",time());
                            ?>
                            <input type="text" class="w3-input w3-border" id="bfecha" name="bfecha" placeholder="dd-mm-aaaa" value="<?php echo $hoy; ?>">
                        </td>
                    </tr>
                </table>
                <div class="w3-row w3-section">
                    <input class="w3-button w3-block w3-blue" type="button" id="enviar" name="enviar" value="Consultar" onclick="return enviardatos_busqueda();">
                </div>
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
            formulario_busqueda();
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