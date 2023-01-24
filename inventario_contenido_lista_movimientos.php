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
			<form id='form_tabla_movimientos' name='form_tabla_movimientos' method='post'>
				<?php
					if(isset($_POST["buscar_movimientos"])) echo"<input type='hidden' id='buscar_movimientos' name='buscar_movimientos' value='".$_POST["buscar_movimientos"]."'>";
				?>
				<div class="w3-row-padding">
					<div class="w3-third">
						<label for="pag">P&aacute;gina:</label>
						<select class="w3-select w3-border" id="pag" name="pag" onchange="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista_movimientos();">
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
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value=''; if ($('#accion_modificar').length) document.getElementById('accion_modificar').value='';return enviardatos_lista_movimientos();">
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
							echo"<input type='hidden' id='accion_eliminar_movimientos' name='accion_eliminar_movimientos'>";
							echo"<input type='hidden' id='accion_modificar_movimientos' name='accion_modificar_movimientos'>";
							$admin = usuario_admin();
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row=$result->fetch_array();
								$num_col=count($row)/2;
								echo"<td align='center' nowrap>";
									if ($admin) {
										echo"<i class='icon-cross2 icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' ";
										?>
										onclick="document.getElementById('accion_eliminar_movimientos').value='<?php echo $row[0]; ?>';return confirmar_eliminar_movimiento('<?php echo $row[0]; ?>');"
										<?php
										echo"'></i>";
										echo"&nbsp;&nbsp;";
									}
									if (false) { //Se quita la opcion editar
										echo"<i class='icon-pencil icon_table' id='editar_<?php echo $i; ?>' name='editar_<?php echo $i; ?>' alt='Modificar' title='Modificar' ";
										?>
										onclick="document.getElementById('accion_modificar').value='<?php echo $row[0]; ?>';return enviardatos_lista();"
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
		$sql = "";
		if (strlen($_POST["buscar_movimientos"]))
		{
			$sql = "SELECT
				m.id_productos_movimientos id, 
				m.fecha,
				f.nombre fabricante, 
				p.nombre producto,
				m.entrada_salida movimiento,
				p.medida,
				m.cantidad
			FROM
				productos_movimientos m 
				INNER JOIN productos p on m.id_producto = p.id_producto
				INNER JOIN fabricantes f on p.id_fabricante = f.id_fabricante
			WHERE
				m.fecha like '%".$_POST['buscar_movimientos']."%' or
				f.nombre like '%".$_POST['buscar_movimientos']."%' or
				p.nombre like '%".$_POST['buscar_movimientos']."%' or
				m.entrada_salida like '%".$_POST['buscar_movimientos']."%' or
				m.medida like '%".$_POST['buscar_movimientos']."%' or
				m.cantidad like '%".$_POST['buscar_movimientos']."%';";
		}
		else
		{
			$sql = "SELECT
				m.id_productos_movimientos id, 
				m.fecha,
				f.nombre fabricante, 
				p.nombre producto,
				m.entrada_salida movimiento,
				p.medida,
				m.cantidad
			FROM
			productos_movimientos m 
			INNER JOIN productos p on m.id_producto = p.id_producto
			INNER JOIN fabricantes f on p.id_fabricante = f.id_fabricante;";
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

	function eliminar_movimiento($bd)
	{
		global $basedatos;
		if ($bd->eliminar_datos(1,$basedatos,"productos_movimientos","id_productos_movimientos ",$_POST["accion_eliminar_movimientos"]))
			if ($bd->eliminar_datos(1,$basedatos,"productos_movimientos_relaciones","id_productos_movimientos ",$_POST["accion_eliminar_movimientos"]))
				return true;
		return false;
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(isset($_POST["accion_eliminar_movimientos"]) and !empty($_POST["accion_eliminar_movimientos"]))
		{
			if(eliminar_movimiento($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","MOVIMIENTO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIINAR EL MOVIMIENTO").set('label', 'Aceptar');
				</script>
				<?php
			}
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
	}
?>