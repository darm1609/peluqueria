<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function eliminar_abono_empleado($bd)
	{
		global $basedatos;
		if($bd->eliminar_datos(1,$basedatos,"abono_empleado","id_abono_empleado",$_POST["accion_eliminar"]))
			return true;
		else
			return false;
	}

	function mostrar_busqueda($result,$colespeciales,$colocultar,$bd,$pag=1,$cantxpag=20)
	{
		$fil = $result->num_rows;
		$cantpag=$fil/$cantxpag;
		$cantpag=ceil($cantpag);
		if($pag>$cantpag)
			$pag=$cantpag;
		for($i=1,$ini=0,$fin=$cantxpag-1;$i<$pag;$i++,$ini+=$cantxpag,$fin+=$cantxpag);
		?>
		<div class="w3-container">
			<p>
			<form id='form_tabla' name='form_tabla' method='post'>
				<div class="w3-row-padding">
					<div class="w3-third">
						<label for="pag">P&aacute;gina:</label>
						<select class="w3-select w3-border" id="pag" name="pag" onchange="document.getElementById('accion_eliminar').value='';return enviardatos_lista();">
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
						<input type="button" class="w3-button w3-block w3-blue" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="document.getElementById('accion_eliminar').value='';return enviardatos_lista();">
					</div>
				</div>
				<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
					<thead>
						<tr class="w3-blue">
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
							if($pag>1) for($i=0;$i<$pag-1;$i++) for($j=0;$j<$cantxpag;$j++) $row = $result->fetch_array();
							echo"<input type='hidden' id='accion_eliminar' name='accion_eliminar'>";
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row = $result->fetch_array();
								$num_col=count($row)/2;
								echo"<td align='center' nowrap>";
									echo"<i class='icon-cross2 icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' ";
									?>
									onclick="document.getElementById('accion_eliminar').value='<?php echo $row[0]; ?>';return confirmar_eliminar('<?php echo $row[0]; ?>');"
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
				<?php
					if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
					if(isset($_POST["bfecha"])) echo"<input type='hidden' id='bfecha' name='bfecha' value='".$_POST["bfecha"]."'>";
				?>
			</form>
			</p>
		</div>
		<?php
	}

	function crear_sql_busqueda($bd)
	{
		if(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="fecha")
		{
			$where=" ";
			if(isset($_POST["bfecha"]) and !empty($_POST["bfecha"]))
				$where="abono_empleado.empleado_cedula=empleado.empleado_cedula AND abono_empleado.fecha='".$_POST["bfecha"][6].$_POST["bfecha"][7].$_POST["bfecha"][8].$_POST["bfecha"][9]."-".$_POST["bfecha"][3].$_POST["bfecha"][4]."-".$_POST["bfecha"][0].$_POST["bfecha"][1]."' ";
			$sql="SELECT abono_empleado.id_abono_empleado, abono_empleado.fecha, empleado.nombre, empleado.apellido, abono_empleado.monto FROM abono_empleado, empleado WHERE ".$where."ORDER BY abono_empleado.fecha_num DESC;";
			unset($where);
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="todo")
		{
			$sql="SELECT abono_empleado.id_abono_empleado, abono_empleado.fecha, empleado.nombre, empleado.apellido, abono_empleado.monto FROM abono_empleado, empleado WHERE abono_empleado.empleado_cedula=empleado.empleado_cedula ORDER BY abono_empleado.fecha_num DESC;";
		}
		$result = $bd->mysql->query($sql);
		unset($sql);
		if($result)
		{
			$n = $result->num_rows;
			if(!empty($n))
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
		}
		else
		{
			unset($result);
			return false;
		}
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(isset($_POST["accion_eliminar"]) and !empty($_POST["accion_eliminar"]))
		{
			if(eliminar_abono_empleado($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","ABONO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIMINAR EL ABONO").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		if($result=crear_sql_busqueda($bd))
		{
			//$colespeciales=array(1=>"fecha_dd_mm_yy");
			$colespeciales=array();
			$colocultar = array();
			if(isset($_POST["pag"]) and !empty($_POST["pag"]))
				$pag=$_POST["pag"];
			if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
				$cantxpag=$_POST["cantxpag"];
			$colocultar[0] = "id_abono_empleado";
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