<?php
	session_start();
	require("head.php");
	require("config.php");
	require("funciones_generales.php");
	require("librerias/basedatos.php");

	function eliminar_trabajo($bd)
	{
		global $basedatos;
		if($bd->actualizar_datos(1,1,$basedatos,"ingreso","id_motivo_ingreso",$_POST["id_motivo_ingreso"],"id_motivo_ingreso","x","0"))
		{
			if($bd->actualizar_datos(1,1,$basedatos,"motivo_porcentaje_ganancia","id_motivo_ingreso",$_POST["id_motivo_ingreso"],"id_motivo_ingreso","x","0"))
			{
				if($bd->eliminar_datos(1,$basedatos,"motivo_ingreso","id_motivo_ingreso",$_POST["id_motivo_ingreso"]))
					return true;
				else
					return false;
			}
			else
				return false;
		}
		else
			return false;
	}

	function formulario_lista($bd)
	{
		$sql="SELECT id_motivo_ingreso, motivo FROM motivo_ingreso order by motivo asc;";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if($result)
		{
			$n = $result->num_rows;
			$admin = usuario_admin();
			if(!empty($n))
			{
				?>
				<div class="w3-container">
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
				<thead>
				<tr>
				<?php
					if ($admin and false)
						echo "<th width='10px'></th>";
				?>
				<th>Tipo</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i=0;
				while($row = $result->fetch_array())
				{
					$i++;
					$id_motivo_ingreso=$row["id_motivo_ingreso"];
					echo"<tr>";
					if ($admin and false)
					{
						echo"<td>";
						echo"<i class='icon-cross2 icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' onclick='";
						?>
						eliminar_trabajo("<?php echo $id_motivo_ingreso; ?>");
						<?php
						echo"'></i>";
						unset($id_motivo_ingreso);
						echo"</td>";
					}
					echo"<td>".$row["motivo"]."</td>";
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
		if(isset($_POST["id_motivo_ingreso"]) and !empty($_POST["id_motivo_ingreso"]))
		{
			if(eliminar_trabajo($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","TRABAJO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIMINAR EL TRABAJO").set('label', 'Aceptar');
				</script>
				<?php
			}			
		}
		formulario_lista($bd);
	}
?>