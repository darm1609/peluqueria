<script type="text/javascript">
	
</script>
<?php
	session_start();
	require("head.php");
	require("config.php");
	require("funciones_generales.php");
	require("librerias/basedatos.php");

	function mostrar_busqueda($result,$colespeciales,$colocultar,$bd,$pag=1,$cantxpag=20)
	{
		$fil=$result->num_rows;
		$cantpag=$fil/$cantxpag;
		$cantpag=ceil($cantpag);
		if($pag>$cantpag)
			$pag=$cantpag;
		for($i=1,$ini=0,$fin=$cantxpag-1;$i<$pag;$i++,$ini+=$cantxpag,$fin+=$cantxpag);
		?>
		<div class="w3-container">
			<p>
			<form id='form_tabla_fabricantes' name='form_tabla_fabricantes' method='post'>
				<?php
					if(isset($_POST["buscar_fabricantes"])) echo"<input type='hidden' id='buscar_fabricantes' name='buscar_fabricantes' value='".$_POST["buscar_fabricantes"]."'>";
				?>
				<div class="w3-row-padding">
					<div class="w3-third">
						<label for="pag">P&aacute;gina:</label>
						<select class="w3-select w3-border" id="pag" name="pag" onchange="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista_fabricantes();">
						<?php
							for($i=1;$i<=$cantpag;$i++)
							{
								if($i==$pag)
									echo"<option value='$i' selected>$i</option>";
								else
									echo"<option value='$i'>$i</option>";
							}
						?>
						</select>
					</div>
					<div class="w3-third">
						<label for="cantxpag">Cantidad de Registros por P&aacute;gina:</label>
						<input type="text" class="w3-input w3-border" id="cantxpag" name="cantxpag" onKeyPress="return NumCheck2(event, this)" value="<?php echo $cantxpag;?>">
					</div>
					<div class="w3-third">
						<br>
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista_fabricantes();">
					</div>
				</div>
				<div style="overflow-x: scroll; overflow-x: auto;">
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<tr class="w3-dulcevanidad">
							<th class='tableheadresul' align='center'>&nbsp;</th>
							<?php
								$col = $result->fetch_fields();
								foreach($col as $valor)
								{
									if (!in_array($valor->name, $colocultar))
									{
										?>
										<th class='tableheadresul' align='center' nowarp>
											<?php
												echo ucwords($valor->name);
											?>
										</th>
										<?php
									}
								}
								unset($col);
							?>
						</tr>
					</thead>
					<tbody>
						<?php
							if($pag>1) for($i=0;$i<$pag-1;$i++) for($j=0;$j<$cantxpag;$j++) $row=$result->fetch_array();
							echo"<input type='hidden' id='accion_eliminar_fabricantes' name='accion_eliminar_fabricantes'>";
							echo"<input type='hidden' id='accion_modificar_fabricantes' name='accion_modificar_fabricantes'>";
							$admin = usuario_admin();
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row=$result->fetch_array();
								$num_col=count($row)/2;
								echo"<td align='center' nowrap>";
									if ($admin and false) { //Se quita la opcion de eliminar
										echo"<i class='icon-cross2 icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' ";
										?>
										onclick="document.getElementById('accion_eliminar').value='<?php echo $row[0]; ?>';return confirmar_eliminar('<?php echo $row[0]; ?>');"
										<?php
										echo"'></i>";
										echo"&nbsp;&nbsp;";
									}
									echo"<i class='icon-pencil icon_table' id='editar_<?php echo $i; ?>' name='editar_<?php echo $i; ?>' alt='Modificar' title='Modificar' ";
									?>
									onclick="document.getElementById('accion_modificar_fabricantes').value='<?php echo $row[0]; ?>';return enviardatos_lista_fabricantes();"
									<?php
									echo"'></i>";
								echo"</td>";
								for($j=1;$j<$num_col;$j++)
								{
									echo"<td align='center'>";
									if(!empty($row[$j]))
									{
										$especial=false;
										foreach($colespeciales as $indice=>$valor) 
										{
											if($indice==$j)
											{
												$especial=true;
												if(is_callable($valor))
													echo $valor($row[$j],$bd);
												else
													echo $row[$j];
											}
										}
										if(!$especial)
											echo $row[$j];
									}
									else
										echo"&nbsp;";
									echo"</td>";
								}
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				</div>
			</form>
			</p>
		</div>
		<?php
	}

	function crear_sql_busqueda($bd)
	{
		$sql = "";
		if (strlen($_POST["buscar_fabricantes"]))
		{
			$sql = "SELECT * FROM fabricantes WHERE nombre like '%".$_POST["buscar_fabricantes"]."%';";
		}
		else
		{
			$sql = "SELECT * FROM fabricantes;";
		}
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			$n = $result->num_rows;
			if (!empty($n))
			{
				unset($n);
				return $result;
			}
			else
			{
				unset($n);
				$result->free();
				return false;
			}
			$result->free();
		}
		else
			unset($result);
		return false;
	}

	function formulario_modificar_fabricantes($bd)
	{
		$sql = "SELECT nombre FROM fabricantes WHERE id_fabricante = '".$_POST["accion_modificar_fabricantes"]."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if($result)
		{
			$row = $result->fetch_all(MYSQLI_ASSOC);
			?>
			<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fmodificar_fabricante" name="fmodificar_fabricante" method="post">
				<?php
					if(isset($_POST["buscar_fabricantes"])) echo"<input type='hidden' id='buscar_fabricantes' name='buscar_fabricantes' value='".$_POST["buscar_fabricantes"]."'>";
					if(isset($_POST["cantxpag"])) echo"<input type='hidden' id='cantxpag' name='cantxpag' value='".$_POST["cantxpag"]."'>";
					if(isset($_POST["pag"])) echo"<input type='hidden' id='pag' name='pag' value='".$_POST["pag"]."'>";
					if(isset($_POST["mostrarxpag"])) echo"<input type='hidden' id='mostrarxpag' name='mostrarxpag' value='".$_POST["mostrarxpag"]."'>";
					echo"<input type='hidden' id='id_fabricante' name='id_fabricante' value='".$_POST["accion_modificar_fabricantes"]."'>";
					echo"<input type='hidden' id='guardar_modificar_fabricante' name='guardar_modificar_fabricante' value=''>";
				?>
				<div class="w3-row w3-section">
					<label for="trabajo" class='w3-text-blue'><b>Nombre&nbsp;del&nbsp;fabricante</b></label>
					<div class="w3-rest">
						<?php
							echo "<input type='hidden' id='ofabricante_nombre' name='ofabricante_nombre' value='".$row[0]["nombre"]."'>";
						?>
						<input class="w3-input w3-border" id="mfabricante_nombre" name="mfabricante_nombre" type="text" placeholder="Fabricante" value="<?php echo $row[0]['nombre']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<p>
					<div class="w3-half">
						<input type="button" class="w3-button w3-block w3-red" onclick="return enviardatos_modificar_fabricantes();" value="Cancelar">
					</div>
					<div class="w3-half">
						<input type="button" class="w3-button w3-block w3-green" onclick="return confirmar_modificar_fabricante();" value="Guardar">
					</div>
					</p>
				</div>
			</form>
			<?php
		}
		else
			unset($result);
	}

	function guardar_modificar_fabricante($bd)
	{
		global $basedatos;
		if($bd->actualizar_datos(1,1,$basedatos,"fabricantes","id_fabricante",$_POST["id_fabricante"],"nombre",$_POST["ofabricante_nombre"],$_POST["mfabricante_nombre"]))
			return true;
		else
			return false;
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(isset($_POST["guardar_modificar_fabricante"]) and !empty($_POST["guardar_modificar_fabricante"]))
		{
			if (guardar_modificar_fabricante($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","LOS DATOS SE MODIFICARON SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","ERROR AL MODIFICAR LOS DATOS").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		elseif(isset($_POST["accion_modificar_fabricantes"]) and !empty($_POST["accion_modificar_fabricantes"]))
		{
			formulario_modificar_fabricantes($bd);
		}
		else 
		{
			if($result = crear_sql_busqueda($bd))
			{
				$colespeciales=array();
				$colocultar=array();
				if(isset($_POST["pag"]) and !empty($_POST["pag"]))
					$pag=$_POST["pag"];
				if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
					$cantxpag=$_POST["cantxpag"];
				$colocultar[0]="id_fabricante";
				if(isset($pag) and isset($cantxpag))
					mostrar_busqueda($result,$colespeciales,$colocultar,$bd,$pag,$cantxpag);
				else
					mostrar_busqueda($result,$colespeciales,$colocultar,$bd);
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","LA CONSULTA NO GENERO RESULTADOS").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
	}
?>