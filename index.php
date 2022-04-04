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
		$sql="SELECT login FROM usuario WHERE login='".$_POST["login"]."' AND pass='".$_POST["pass"]."';";
		$result = $bd->mysql->query($sql);
		//echo $sql;
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
					<input class="w3-button w3-block w3-dulcevanidad" type="submit" id="enviar" name="enviar" value="Entrar" onclick="if(this.form.pass.value!='') this.form.pass.value=CryptoJS.SHA3(this.form.pass.value);">
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
		if(!isset($_POST["enviar"]) and !isset($_SESSION["login"]) or isset($_POST["cerrar"]))
		{
			login_form();
		}
		else
		{
			if(isset($_POST["enviar"]))
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
