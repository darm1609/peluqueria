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
			<form id='form_tabla_reporte' name='form_tabla_reporte' method='post'>
				<?php
					if(isset($_POST["buscar_productos"])) echo"<input type='hidden' id='buscar_productos' name='buscar_productos' value='".$_POST["buscar_productos"]."'>";
					if(isset($_POST["cantxpag"])) echo"<input type='hidden' id='cantxpag' name='cantxpag' value='".$_POST["cantxpag"]."'>";
					if(isset($_POST["pag"])) echo"<input type='hidden' id='pag' name='pag' value='".$_POST["pag"]."'>";
					if(isset($_POST["mostrarxpag"])) echo"<input type='hidden' id='mostrarxpag' name='mostrarxpag' value='".$_POST["mostrarxpag"]."'>";
					echo"<input type='hidden' id='bfecha2' name='bfecha2' value='".$_POST["bfecha2"]."'>";
					echo"<input type='hidden' id='bfecha_unix' name='bfecha_unix' value='".$_POST["bfecha_unix"]."'>";
					echo"<input type='hidden' id='bfecha' name='bfecha' value='".$_POST["bfecha"]."'>";
				?>
				<div class="w3-row-padding">
					<div class="w3-third">
						<label for="pag">P&aacute;gina:</label>
						<select class="w3-select w3-border" id="pag" name="pag" onchange="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista_reporte();">
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
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista_reporte();">
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
							echo"<input type='hidden' id='accion_eliminar_reporte' name='accion_eliminar_reporte'>";
							echo"<input type='hidden' id='accion_modificar_reporte' name='accion_modificar_reporte'>";
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
									if (false)
									{
										echo"<i class='icon-pencil icon_table' id='editar_<?php echo $i; ?>' name='editar_<?php echo $i; ?>' alt='Modificar' title='Modificar' ";
										?>
										onclick="document.getElementById('accion_modificar_productos').value='<?php echo $row[0]; ?>';return enviardatos_lista_productos();"
										<?php
										echo"'></i>";
									}
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
		$sql = "SELECT 
			pm.id_producto id,
			p.nombre producto,
			p.medida,
			SUM(CASE WHEN pm.entrada_salida = 'entrada' THEN pm.cantidad ELSE 0 END) as entradas,
			SUM(CASE WHEN pm.entrada_salida = 'salida' THEN pm.cantidad * -1 ELSE 0 END) as salidas,
			SUM(CASE WHEN pm.entrada_salida = 'entrada' THEN pm.cantidad ELSE pm.cantidad * -1 END) as total
		FROM 
			productos_movimientos pm
		INNER JOIN productos p on pm.id_producto = p.id_producto
		WHERE
			pm.fecha_num <= '".$_POST["bfecha_unix"]."'
		GROUP BY
			pm.id_producto;";
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

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if ($result = crear_sql_busqueda($bd))
		{
			$colespeciales=array();
			$colocultar=array();
			if(isset($_POST["pag"]) and !empty($_POST["pag"]))
				$pag=$_POST["pag"];
			if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
				$cantxpag=$_POST["cantxpag"];
			$colocultar[0] = "id";
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
?>