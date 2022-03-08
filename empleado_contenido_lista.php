<script type="text/javascript">

</script>
<?php
	session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function guardar_porcentajes($bd)
	{
		global $basedatos;
		if($bd->insertar_datos(7,$basedatos,"porcentaje_ganancia","empleado_cedula","fecha","porcentaje_peluqueria","porcentaje_dueño","porcentaje_empleado","fecha_num","login",$_POST["empleado_cedula"],date("Y-m-d",time()),$_POST["porcentaje_peluqueria"],$_POST["porcentaje_dueño"],$_POST["porcentaje_empleado"],time(),$_SESSION["login"]))
		{
			$n=$_POST["porcentajes_motivos"];
			for($i=1;$i<=$n;$i++)
			{
				if(!$bd->insertar_datos(8,$basedatos,"motivo_porcentaje_ganancia","id_motivo_ingreso","empleado_cedula","fecha","porcentaje_empleado","porcentaje_dueño","porcentaje_peluqueria","fecha_num","login",$_POST["sel_motivo_".$i],$_POST["empleado_cedula"],date("Y-m-d",time()),$_POST["porcentaje_empleado_motivo_".$i],$_POST["porcentaje_dueño_motivo_".$i],$_POST["porcentaje_peluqueria_motivo_".$i],time(),$_SESSION["login"]))
				{
					unset($n);
					return false;
				}
			}
			unset($n);
			return true;
		}
		else
			return false;
	}

	function guardar_modificar($bd)
	{
		global $basedatos;
		if($bd->actualizar_datos(1,1,$basedatos,"ingreso","empleado_cedula",$_POST["oempleado_cedula"],"empleado_cedula",$_POST["oempleado_cedula"],$_POST["mempleado_cedula"]))
		{
			if($bd->actualizar_datos(1,1,$basedatos,"porcentaje_ganancia","empleado_cedula",$_POST["oempleado_cedula"],"empleado_cedula",$_POST["oempleado_cedula"],$_POST["mempleado_cedula"]))
			{
				if($bd->actualizar_datos(1,1,$basedatos,"motivo_porcentaje_ganancia","empleado_cedula",$_POST["oempleado_cedula"],"empleado_cedula",$_POST["oempleado_cedula"],$_POST["mempleado_cedula"]))
				{
					if($bd->actualizar_datos(1,1,$basedatos,"vale_pago","empleado_cedula",$_POST["oempleado_cedula"],"empleado_cedula",$_POST["oempleado_cedula"],$_POST["mempleado_cedula"]))
					{
						if($bd->actualizar_datos(1,6,$basedatos,"empleado","empleado_cedula",$_POST["oempleado_cedula"],"empleado_cedula",$_POST["oempleado_cedula"],$_POST["mempleado_cedula"],"nombre",$_POST["onombre"],$_POST["mnombre"],"apellido",$_POST["oapellido"],$_POST["mapellido"],"genero",$_POST["ogenero"],$_POST["mgenero"],"correo",$_POST["ocorreo"],$_POST["mcorreo"],"telf",$_POST["otelf"],$_POST["mtelf"]))
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
			else
				return false;
		}
		else
			return false;
	}

	function eliminar_empleado($bd)
	{
		global $basedatos;
		if($bd->eliminar_datos(1,$basedatos,"empleado","empleado_cedula",$_POST["accion_eliminar"]))
			return true;
		else
			return false;
	}

	function exite($bd)
	{
		if($bd->existe(1,"empleado","empleado_cedula",$_POST["mempleado_cedula"]))
			return true;
		else
			return false;
	}

	function formulario_modificar_porcentaje($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fporcentaje" name="fporcentaje" method="post">
			<h2 class="w3-center">Porcentajes de Ganancia</h2>
			<div class="w3-row w3-section" style="border:1px solid #cccccc;font-size:12px;">
				<?php
					$sql="SELECT id_porcentaje_ganancia, empleado_cedula, fecha, porcentaje_peluqueria, porcentaje_dueño, porcentaje_empleado, fecha_num, login FROM porcentaje_ganancia WHERE empleado_cedula='".$_POST["accion_porcentaje"]."' ORDER BY fecha_num DESC;";
					$result = $bd->mysql->query($sql);
					unset($sql);
					if($result)
					{
						$n = $result->num_rows;
						if(!empty($n))
						{
							while($row = $result->fetch_array())
							{
								echo "<b>".$row["fecha"][8].$row["fecha"][9]."-".$row["fecha"][5].$row["fecha"][6]."-".$row["fecha"][0].$row["fecha"][1].$row["fecha"][2].$row["fecha"][3]."</b>";
								echo " ".$row["porcentaje_empleado"]."% Empleado ".$row["porcentaje_peluqueria"]."% Peluquer&iacute;a ".$row["porcentaje_dueño"]."% Due&ntilde;o";
								$porcentaje_empleado=$row["porcentaje_empleado"];
								$porcentaje_peluqueria=$row["porcentaje_peluqueria"];
								$porcentaje_dueño=$row["porcentaje_dueño"];
								echo "<br>";
							}
							unset($row);
						}
						else
						{
							echo"<b>No hay porcentajes establecidos</b>";
						}
						unset($n);
						$result->free();
					}
					else
						unset($result);
					$sql="SELECT id_motivo_porcentaje_ganancia, id_motivo_ingreso, empleado_cedula, fecha, porcentaje_empleado, porcentaje_dueño, porcentaje_peluqueria, fecha_num, login FROM motivo_porcentaje_ganancia WHERE empleado_cedula='".$_POST["accion_porcentaje"]."' ORDER BY fecha_num ASC;";
					$result = $bd->mysql->query($sql);
					unset($sql);
					if($result)
					{
						$n = $result->num_rows;
						if(!empty($n))
						{
							while($row = $result->fetch_array())
							{
								$motivo=motivo($row["id_motivo_ingreso"],$bd);
								echo "<b>".$row["fecha"][8].$row["fecha"][9]."-".$row["fecha"][5].$row["fecha"][6]."-".$row["fecha"][0].$row["fecha"][1].$row["fecha"][2].$row["fecha"][3]."</b>";
								echo " ".$motivo." ".$row["porcentaje_empleado"]."% Empleado ".$row["porcentaje_peluqueria"]."% Peluquer&iacute;a ".$row["porcentaje_dueño"]."% Due&ntilde;o";
								echo "<br>";
								unset($motivo);
							}
							unset($row);
						}
						unset($n);
						$result->free();
					}
					else
						unset($result);
				?>
			</div>
			<div class="w3-row w3-section">
				<label for="porcentaje_empleado" class="w3-text-blue"><b>% Empleado</b></label>
				<div class="w3-rest"><input class="w3-input w3-border" id="porcentaje_empleado" name="porcentaje_empleado" type="text" placeholder="%" onkeypress="return NumCheck(event, this)" tabindex="1" value="<?php if(isset($porcentaje_empleado)) if(empty($porcentaje_empleado)) echo 0; else echo $porcentaje_empleado; ?>"></div>
			</div>
			<div class="w3-row w3-section">
				<label for="porcentaje_peluqueria" class="w3-text-blue"><b>% Empresa</b></label>
				<div class="w3-rest"><input class="w3-input w3-border" id="porcentaje_peluqueria" name="porcentaje_peluqueria" type="text" placeholder="%" onkeypress="return NumCheck(event, this)" tabindex="2" value="<?php if(isset($porcentaje_peluqueria)) if(empty($porcentaje_peluqueria)) echo 0; else echo $porcentaje_peluqueria; ?>"></div>
			</div>
			<div class="w3-row w3-section">
				<label for="porcentaje_dueño" class="w3-text-blue"><b>% Due&ntilde;o</b></label>
				<div class="w3-rest"><input class="w3-input w3-border" id="porcentaje_dueño" name="porcentaje_dueño" type="text" placeholder="%" onkeypress="return NumCheck(event, this)" tabindex="3" value="<?php if(isset($porcentaje_dueño)) if(empty($porcentaje_dueño)) echo 0; else echo $porcentaje_dueño; ?>"></div>
			</div>
			<?php
				$sql="SELECT id_motivo_ingreso, motivo FROM motivo_ingreso;";
				$result = $bd->mysql->query($sql);
				unset($sql);
				if($result)
				{
					$arreglo=array();
					$i=0;
					while($row = $result->fetch_array())
					{
						$arreglo[$i][0]=$row["id_motivo_ingreso"];
						$arreglo[$i][1]=$row["motivo"];
						$i++;
					}
					$result->free();
				}
				else
					unset($result);
				$n=count($arreglo);
				$arreglo=json_encode($arreglo);
			?>
			<div class="w3-row w3-section">
				<div class="w3-rest">
					<b>Agregar Porcentajes Por Tipo de Trabajo:&nbsp;</b>
					<?php
						echo"<i class='icon-plus4 icon_mas' onclick='agregar_campos(".$arreglo.",".$n.");'></i>";
					?>
					&nbsp;
					<i class="icon-minus3 icon_menos" onclick="eliminar_campos();"></i>
				</div>
			</div>
			<div id="div_por_motivo"></div>
			<div class="w3-row w3-section">
				<p>
				<div class="w3-half">
					<input type="button" class="w3-button w3-block w3-red" onclick="return enviardatos_porcentaje();" value="Cancelar">
				</div>
				<div class="w3-half">
					<input type="button" class="w3-button w3-block w3-green" onclick="submit_porcentaje();" value="Guardar">
				</div>
				</p>
			</div>
			<input type="hidden" id="porcentajes_motivos" name="porcentajes_motivos" value="0">
			<input type="hidden" id="porcentajes_correctos" name="porcentajes_correctos">
			<?php
				if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
				if(isset($_POST["chbcedula"])) echo"<input type='hidden' id='chbcedula' name='chbcedula' value='".$_POST["chbcedula"]."'>";
				if(isset($_POST["bcedula"])) echo"<input type='hidden' id='bcedula' name='bcedula' value='".$_POST["bcedula"]."'>";
				if(isset($_POST["chbnombre"])) echo"<input type='hidden' id='chbnombre' name='chbnombre' value='".$_POST["chbnombre"]."'>";
				if(isset($_POST["bnombre"])) echo"<input type='hidden' id='bnombre' name='bnombre' value='".$_POST["bnombre"]."'>";
				if(isset($_POST["accion_porcentaje"])) echo"<input type='hidden' id='empleado_cedula' name='empleado_cedula' value='".$_POST["accion_porcentaje"]."'>";
			?>
		</form>
		<?php
	}

	function formulario_modificar($bd)
	{
		$sql="SELECT empleado_cedula, nombre, apellido, genero, telf, correo FROM empleado WHERE empleado_cedula='".$_POST["accion_modificar"]."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if($result)
		{
			$row = $result->fetch_all(MYSQLI_ASSOC);
			?>
			<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fmodificar" name="fmodificar" method="post">
				<input type="hidden" id="guardar_modificar" name="guardar_modificar" value="">
				<h2 class="w3-center">Empleado</h2>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="mempleado_cedula"><i class="icon-drivers-license-o" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="oempleado_cedula" name="oempleado_cedula" value="<?php echo $row[0]['empleado_cedula']; ?>">
						<input class="w3-input w3-border" id="mempleado_cedula" name="mempleado_cedula" type="text" placeholder="C&eacute;dula" onkeypress="return NumCheck2(event, this)" tabindex="1" value="<?php echo $row[0]['empleado_cedula']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="mnombre"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="onombre" name="onombre" value="<?php echo $row[0]['nombre']; ?>">
						<input class="w3-input w3-border" id="mnombre" name="mnombre" type="text" placeholder="Nombre" maxlength="30" tabindex="2" value="<?php echo $row[0]['nombre']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="mapellido"><i class="icon-pencil" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="oapellido" name="oapellido" value="<?php echo $row[0]['apellido']; ?>">
						<input class="w3-input w3-border" id="mapellido" name="mapellido" type="text" placeholder="Apellido" maxlength="30" tabindex="3" value="<?php echo $row[0]['apellido']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section" style="text-align:center;">
					<input type="hidden" id="ogenero" name="ogenero" value="<?php echo $row[0]['genero']; ?>">
					<label>
						<i class="icon-mars" style="font-size:37px;"></i>&nbsp;
						<input type="radio" class="w3-radio" id="mgenero" name="mgenero" value="m" tabindex="5"
						<?php
							if($row[0]['genero'] == "m")
								echo"checked";
						?>
						>
					</label>
					<label>
						<i class="icon-venus" style="font-size:37px;"></i>&nbsp;
						<input type="radio" class="w3-radio" id="mgenero" name="mgenero" value="f" tabindex="4"
						<?php
							if($row[0]['genero'] == "f")
								echo"checked";
						?>
						>
					</label>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="mtelf"><i class=" icon-phone" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="otelf" name="otelf" value="<?php $row[0]['telf']; ?>">
						<input class="w3-input w3-border" id="mtelf" name="mtelf" type="text" placeholder="Tel&eacute;fono" maxlength="11" onkeypress="return NumCheck3(event, this)" tabindex="6" value="<?php echo $row[0]['telf']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<div class="w3-col" style="width:50px"><label for="correo"><i class="icon-mail2" style="font-size:37px;"></i></label></div>
					<div class="w3-rest">
						<input type="hidden" id="ocorreo" name="ocorreo" value="<?php echo $row[0]['correo']; ?>">
						<input class="w3-input w3-border" id="mcorreo" name="mcorreo" type="text" placeholder="Correo Electr&oacute;nico" maxlength="255" tabindex="7" value="<?php echo $row[0]['correo']; ?>">
					</div>
				</div>
				<div class="w3-row w3-section">
					<p>
					<div class="w3-half">
						<input type="button" class="w3-button w3-block w3-red" onclick="return enviardatos_modificar();" value="Cancelar">
					</div>
					<div class="w3-half">
						<input type="button" class="w3-button w3-block w3-green" onclick="return confirmar_modificar();" value="Guardar">
					</div>
					</p>
				</div>
				<?php
					if(isset($_POST["sel_opcion"])) echo"<input type='hidden' id='sel_opcion' name='sel_opcion' value='".$_POST["sel_opcion"]."'>";
					if(isset($_POST["chbcedula"])) echo"<input type='hidden' id='chbcedula' name='chbcedula' value='".$_POST["chbcedula"]."'>";
					if(isset($_POST["bcedula"])) echo"<input type='hidden' id='bcedula' name='bcedula' value='".$_POST["bcedula"]."'>";
					if(isset($_POST["chbnombre"])) echo"<input type='hidden' id='chbnombre' name='chbnombre' value='".$_POST["chbnombre"]."'>";
					if(isset($_POST["bnombre"])) echo"<input type='hidden' id='bnombre' name='bnombre' value='".$_POST["bnombre"]."'>";
				?>
			</form>
			<?php
			pg_free_result($result);
		}
		else
			unset($result);
		?>
		<?php
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
						<select class="w3-select w3-border" id="pag" name="pag" onchange="document.getElementById('accion_eliminar').value='';document.getElementById('accion_porcentaje').value='';document.getElementById('accion_modificar').value='';return enviardatos_lista();">
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
						<input type="button" class="w3-button w3-block w3-blue" id="mostrarxpag" name="mostrarxpag" value="Mostrar" onclick="document.getElementById('accion_eliminar').value='';document.getElementById('accion_porcentaje').value='';document.getElementById('accion_modificar').value='';return enviardatos_lista();">
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
							if($pag>1) for($i=0;$i<$pag-1;$i++) for($j=0;$j<$cantxpag;$j++) $row=$result->fetch_array();
							echo"<input type='hidden' id='accion_eliminar' name='accion_eliminar'>";
							echo"<input type='hidden' id='accion_modificar' name='accion_modificar'>";
							echo"<input type='hidden' id='accion_porcentaje' name='accion_porcentaje'>";
							for($i=$ini;$i<=$fin and $i<$fil;$i++)
							{
								echo"<tr>";
								$row=$result->fetch_array();
								$num_col=count($row)/2;
								echo"<td align='center' nowrap>";
									if($row[0]!="17734140")
									{
										echo"<i class='icon-cross2 icon_table' id='eliminar_<?php echo $i; ?>' name='eliminar_<?php echo $i; ?>' alt='Eliminar' title='Eliminar' ";
										?>
										onclick="document.getElementById('accion_eliminar').value='<?php echo $row[0]; ?>';return confirmar_eliminar('<?php echo $row[0]; ?>');"
										<?php
										echo"'></i>";
										echo"&nbsp;&nbsp;";
									}
									echo"<i class='icon-pencil icon_table' id='editar_<?php echo $i; ?>' name='editar_<?php echo $i; ?>' alt='Modificar' title='Modificar' ";
									?>
									onclick="document.getElementById('accion_modificar').value='<?php echo $row[0]; ?>';return enviardatos_lista();"
									<?php
									echo"'></i>";
									echo"&nbsp;&nbsp;";
									echo"<b class='icon_table' id='porcentaje_<?php echo $i; ?>' name='porcentaje_<?php echo $i; ?>' alt='Porcentaje de Ganancia' title='Porcentaje de Ganancia'";
									?>
									onclick="document.getElementById('accion_porcentaje').value='<?php echo $row[0]; ?>';return enviardatos_lista();"
									<?php
									echo">%</b>";
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
					if(isset($_POST["chbcedula"])) echo"<input type='hidden' id='chbcedula' name='chbcedula' value='".$_POST["chbcedula"]."'>";
					if(isset($_POST["bcedula"])) echo"<input type='hidden' id='bcedula' name='bcedula' value='".$_POST["bcedula"]."'>";
					if(isset($_POST["chbnombre"])) echo"<input type='hidden' id='chbnombre' name='chbnombre' value='".$_POST["chbnombre"]."'>";
					if(isset($_POST["bnombre"])) echo"<input type='hidden' id='bnombre' name='bnombre' value='".$_POST["bnombre"]."'>";
				?>
			</form>
			</p>
		</div>
		<?php
	}

	function crear_sql_busqueda($bd)
	{
		if(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="especificar")
		{
			$where=" ";
			if(isset($_POST["chbcedula"]) and !empty($_POST["bcedula"]))
				$where.="empleado_cedula='".$_POST["bcedula"]."' AND ";
			if(isset($_POST["chbnombre"]) and !empty($_POST["bnombre"]))
				$where.="nombre LIKE '%".$_POST["bnombre"]."%' OR apellido LIKE '%".$_POST["bnombre"]."%' AND ";
			$where[strlen($where)-1]=" ";
			$where[strlen($where)-2]=" ";
			$where[strlen($where)-3]=" ";
			$where[strlen($where)-4]=" ";
			$where=trim($where);
			$sql="SELECT empleado_cedula, empleado_cedula AS cédula, nombre, apellido, telf, correo FROM empleado WHERE ".$where.";";
		}
		elseif(isset($_POST["sel_opcion"]) and $_POST["sel_opcion"]=="listar")
		{
			$sql="SELECT empleado_cedula, empleado_cedula AS cédula, nombre, apellido, telf, correo FROM empleado;";
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
			$result->free();
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
		if(isset($_POST["porcentajes_correctos"]) and !empty($_POST["porcentajes_correctos"]))
		{
			if(guardar_porcentajes($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","LOS DATOS SE GUARDARON SATISFACTORIAMENTE").set('label', 'Aceptar');
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
		if(isset($_POST["accion_eliminar"]) and !empty($_POST["accion_eliminar"]))
		{
			if(eliminar_empleado($bd))
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","EMPLEADO ELIMINADO SATISFACTORIAMENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
			else
			{
				?>
				<script language='JavaScript' type='text/JavaScript'>
					alertify.alert("","NO SE PUDO ELIMINAR EL CLIENTE").set('label', 'Aceptar');
				</script>
				<?php
			}
		}
		if(isset($_POST["guardar_modificar"]) and !empty($_POST["guardar_modificar"]))
		{
			$valido=true;
			if($_POST["oempleado_cedula"]!=$_POST["mempleado_cedula"])
			{
				if(exite($bd))
				{
					$valido=false;
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","EL EMPLEADO "+<?php echo $_POST["mempleado_cedula"]; ?>+" YA SE ENCUENTRA REGISTRADO").set('label', 'Aceptar');
					</script>
					<?php
				}
			}
			if($valido)
			{
				if(guardar_modificar($bd))
				{
					?>
					<script language='JavaScript' type='text/JavaScript'>
						alertify.alert("","LOS DATOS SE GUARDARON SATISFACTORIAMENTE").set('label', 'Aceptar');
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
			unset($valido);
		}
		if(isset($_POST["accion_modificar"]) and !empty($_POST["accion_modificar"]))
		{
			formulario_modificar($bd);
		}
		elseif(isset($_POST["accion_porcentaje"]) and !empty($_POST["accion_porcentaje"]))
		{
			formulario_modificar_porcentaje($bd);
		}
		else
		{
			if($result=crear_sql_busqueda($bd))
			{
				$colespeciales=array();
				$colocultar=array();
				if(isset($_POST["pag"]) and !empty($_POST["pag"]))
					$pag=$_POST["pag"];
				if(isset($_POST["cantxpag"]) and !empty($_POST["cantxpag"]))
					$cantxpag=$_POST["cantxpag"];
				$colocultar[0]="empleado_cedula";
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