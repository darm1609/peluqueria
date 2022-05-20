<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");

	function formulario_lista($bd)
	{
		$sql="SELECT login, nombre, apellido, administrador, consulta, empleado FROM usuario;";
		$result=$bd->mysql->query($sql);
		unset($sql);
		if($result)
		{
			$n=$result->num_rows;
			if(!empty($n))
			{
				?>
				<div class="w3-container">
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
				<thead>
				<tr>
				<th></th>
				<th>Login</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Administrador</th>
				<th>Consulta</th>
				<th>Empleado</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i=0;
				$rows = $result->fetch_all(MYSQLI_ASSOC);
				foreach($rows as $row)
				{
					$i++;
					$login=$row["login"];
					echo"<tr>";
					echo"<td>";
					if($login!="admin")
					{
						echo"<i class='icon-user-times icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' onclick='";
						?>
						eliminar_usuario("<?php echo $login; ?>");
						<?php
						echo"'></i>";
					}
					unset($login);
					echo"</td>";
					echo"<td>".$row["login"]."</td>";
					echo"<td>".$row["nombre"]."</td>";
					echo"<td>".$row["apellido"]."</td>";
					echo"<td>";
					if($row["administrador"]=="1")
						echo"Si";
					else
						echo"No";
					echo"</td>";
					echo"<td>";
					if($row["consulta"]=="1")
						echo"Si";
					else
						echo"No";
					echo"</td>";
					echo"<td>";
					if($row["empleado"]=="1")
						echo"Si";
					else
						echo"No";
					echo"</td>";
					echo"</tr>";
				}
				?>
				</tbody>
				</table>
				</div>
				<?php
			}
			unset($n);
			$result->free();
		}
		else
			unset($result);
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(isset($_POST["login_eliminar"]) and !empty($_POST["login_eliminar"]))
		{
			if($bd->eliminar_datos(1,$basedatos,"usuario","login",$_POST["login_eliminar"]))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","USUARIO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIMINAR EL USUARIO").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		formulario_lista($bd);
	}
?>