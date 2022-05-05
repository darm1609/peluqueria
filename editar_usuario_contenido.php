<script type="text/javascript">

    $(document).ready(function(){
        $(".submit").click(function(e){
            e.preventDefault();
            var valido=new Boolean(true);
            if (!$("#npassword").val().length && !$("#nrpassword").val().length)
            {
                valido=false;
                alertify.alert("","LA CONTRASEÑA NO PUEDE ESTAR VACIA").set('label', 'Aceptar');
            }
            if (valido)
            {
                if ($("#npassword").val() != $("#nrpassword").val())
                {
                    valido=false;
                    alertify.alert("","LAS CONTRASEÑAS COINCIDEN").set('label', 'Aceptar');
                }
            }
            if(valido)
            {
                $("#passhash").val(CryptoJS.SHA3($("#npassword").val()));
                document.getElementById('fagregar').submit();
            }
        });
    });
    
</script>
<?php
    function guardar($bd)
    {
        global $basedatos;
        $pass = $_POST["passhash"];
		if($bd->actualizar_datos(1,1,$basedatos,"usuario","login",$_SESSION["login"],"pass","x",$pass))
			return true;
		else
			return false;
    }

    function formulario_cambio_de_contraseña($bd)
    {
        ?>
        <form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
            <input type='hidden' id='passhash' name='passhash'>
			<h2 class="w3-center">Cambio de contrase&ntilde;a</h2>
            <div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="npassword"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="npassword" name="npassword" type="password" placeholder="Nueva contraseña">
				</div>
			</div>
            <div class="w3-row w3-section">
				<div class="w3-col" style="width:50px"><label for="nrpassword"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
				<div class="w3-rest">
					<input class="w3-input w3-border" id="nrpassword" name="nrpassword" type="password" placeholder="Repite la nueva contraseña">
				</div>
			</div>
            <div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green submit" value="Guardar">
			</div>
        </form>
        <?php
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        ?>
        <header class="w3-container" style="padding-top:22px">
	        <h5><b>Usuario:&nbsp;</b><?php echo $_SESSION["login"]; ?></h5>
        </header>
        <?php
        formulario_cambio_de_contraseña($bd);
        if (isset($_POST["npassword"]) and isset($_POST["nrpassword"]))
        {
            if (guardar($bd))
            {
                ?>
                <script language='JavaScript' type='text/JavaScript'>
                    alertify.alert("","SE CAMBIO EL LA CONTRASEÑA CORRECTAMENTE").set('label', 'Aceptar');
                </script>
                <?php
            }
        }
    }
?>