<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function eliminar_egreso($bd)
	{
		global $basedatos;
		$valida = true;
		if (!$bd->eliminar_datos(1,$basedatos,"egreso_efectivo","id_egreso",$_POST["accion_eliminar"]))
			$valida = false;
		if ($valida)
			if(!$bd->eliminar_datos(1,$basedatos,"egreso_transferencia","id_egreso",$_POST["accion_eliminar"]))
				$valida = false;
		if ($valida)
			if(!$bd->eliminar_datos(1,$basedatos,"egreso_debito","id_egreso",$_POST["accion_eliminar"]))
					$valida = false;
		if ($valida)
			if(!$bd->eliminar_datos(1,$basedatos,"egreso","id_egreso",$_POST["accion_eliminar"]))
				$valida = false;
		return $valida;
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
						<select class="w3-select w3-border" id="pag" name="pag" onchange="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value='';return enviardatos_lista();">
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
						<input type="button" class="w3-button w3-block w3-dulcevanidad" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="if ($('#accion_eliminar').length) document.getElementById('accion_eliminar').value='';return enviardatos_lista();">
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
									if(!empty($row[$j]))
									{
										$especial=false;
										foreach($colespeciales as $indice=>$valor) 
										{											
											if($indice==$j)
											{
												$especial=true;
												if(is_callable($valor))
												{
													echo"<td align='center' nowrap>";
													echo $valor($row[$j],$bd);
													echo"</td>";
												}
												else
												{
													echo"<td align='center'>";
													echo $row[$j];
													echo"</td>";
												}
											}
										}
										if(!$especial)
										{
											echo"<td align='center'>";
											echo $row[$j];
											echo"</td>";
										}
									}
									else
									{
										echo"<td align='center'>";
										echo"&nbsp;";
										echo"</td>";
									}
								}
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				</div>
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
				$where="fecha='".$_POST["bfecha"]."' ";
			$sql="select e.id_egreso, e.fecha, case when e.efectivo = 1 then ee.monto else '' end as 'efectivo', case when e.debito = 1 then ed.monto else '' end as 'datáfono', case when e.transferencia = 1 then et.monto else '' end as 'transferencia', case when e.transferencia = 1 then et.referencia else '' end as 'referencia', ifnull(ee.monto,0) + ifnull(et.monto,0) + ifnull(ed.monto,0) total, e.motivo from egreso e left join egreso_efectivo ee on e.id_egreso = ee.id_egreso left join egreso_transferencia et on e.id_egreso = et.id_egreso left join egreso_debito ed on e.id_egreso = ed.id_egreso where ".$where." order by e.fecha_num asc;";
			unset($where);
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="todo")
		{
			$sql="select e.id_egreso, e.fecha, case when e.efectivo = 1 then ee.monto else '' end as 'efectivo', case when e.debito = 1 then ed.monto else '' end as 'datáfono', case when e.transferencia = 1 then et.monto else '' end as 'transferencia', case when e.transferencia = 1 then et.referencia else '' end as 'referencia', ifnull(ee.monto,0) + ifnull(et.monto,0) + ifnull(ed.monto,0) total, e.motivo from egreso e left join egreso_efectivo ee on e.id_egreso = ee.id_egreso left join egreso_transferencia et on e.id_egreso = et.id_egreso left join egreso_debito ed on e.id_egreso = ed.id_egreso order by e.fecha_num asc;";
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
			if(eliminar_egreso($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","EGRESO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIMINAR EL EGRESO").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		if($result=crear_sql_busqueda($bd))
		{
			//$colespeciales=array(1=>"fecha_dd_mm_yy");
			$colespeciales = array();
			$colocultar = array();
			$colformatomoney = array();
			if(isset($_POST["pag"]) and !empty($_POST["pag"]))
				$pag=$_POST["pag"];
			if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
				$cantxpag=$_POST["cantxpag"];
			$colocultar[0] = "id_egreso";
			$colespeciales[2] = "Efectivo";
			$colespeciales[3] = "Datáfono";
			$colespeciales[4] = "Transferencia";
			$colespeciales[6] = "Total";
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