<script type="text/javascript">
	$(document).ready(function(){
		$(window).resize(function() {
			let divHoras = document.getElementById("horarios");
			$(".cita").width(divHoras.clientWidth - 120);
			$(".cita").show();
			$(".cita-multiple").show();
		});

		let divHoras = document.getElementById("horarios");
		$(".cita").width(divHoras.clientWidth - 120);
		$(".cita").show();
		$(".cita-multiple").show();

		$(".add-cita").on("click", function(e){
			let elem = $(this);
			let hora = elem.data("hora");
			$("#add_cita_hora option").each(function(){
				let text = $(this).text();
				let val = $(this).attr('value');
				if (Number(hora) == Number(val))
				{
					$(this).attr("selected", true);
				}
			});
			$("#modal_nueva_cita").show();
		});
	});

	function confirmar_crear_cita()
	{
		alertify.confirm('','¿Desea crear la cita?', function(){ alertify.success('Sí');enviardatos_crear_cita(); }, function(){ alertify.error('No')}).set('labels', {ok:'Sí', cancel:'No'});
	}

	//1 hora o 60 min son 150px de height
	//1 min son
	//15min 37.5px de height

	//150px cada hora son 2,5px cada min
</script>
<style>
	.table {
		border-collapse: collapse;
		width: 100%;
	}
    .table-border-calendar-1 {
		height: 10em;
        border-bottom: 1px solid #cccccc;
		vertical-align: top;
    }

	.div-cita {
		position: absolute;
		margin-left: 6em;
		display: none;
		padding: 0.5em;
		font-size: smaller;
		border: 1px solid #7b304b; 
		overflow: auto;
		cursor: pointer;
	}

	.div-cita-multiple {
		position: absolute;
		display: none;
		padding: 0.5em;
		font-size: smaller;
		border: 1px solid #7b304b; 
		cursor: pointer;
	}

	.tooltip {
	position: relative;
	display: inline-block;
	border-bottom: 1px dotted black;
	}

	.tooltip .tooltiptext {
	visibility: hidden;
	width: 120px;
	background-color: black;
	color: #fff;
	text-align: center;
	border-radius: 6px;
	padding: 5px 0;

	/* Position the tooltip */
	position: absolute;
	z-index: 1;
	}

	.tooltip:hover .tooltiptext {
	visibility: visible;
	}
</style>
<?php
    session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function crear_modal_add_cita($empleado, $bd)
	{
		echo "<div id='modal_nueva_cita' class='w3-modal'>";
			echo "<div class='w3-modal-content'>";
				echo "<div id='divformularioadd'></div>";
				echo "<div id='closeAddCitaModal' onclick=\"document.getElementById('modal_nueva_cita').style.display='none'\" class='w3-button w3-display-topright'>&times;</div>";
				echo "<div class='w3-display-topleft' style='padding: 1em;'>";
					echo "<b>Nueva cita</b><br><br>"; 
				echo "</div>";
				echo "<br><br>";
        		echo "<div style='padding: 1em;'>";
				?>
				<form id='faddcita' name='faddcita' method="post">
					<div class="w3-row w3-section">
						<div class="w3-col" style="width:50px"><label for="id_motivo_ingreso"><i class="icon-clock2" style="font-size:37px;"></i></label></div>
						<div class="w3-rest">
							<select class="w3-select" id="add_cita_hora" name="add_cita_hora">
								<option value="">Hora</option>
								<?php
									for ($i = 6; $i <= 21; $i++)
									{
										$iAmPm = $i > 12 ? $i - 12 : $i;
										$amPm = $i > 12 ? "pm" : "am";
										if ($i == 12)
											$amPm = "m";
										$iFormat = $iAmPm < 10 ? "0".$iAmPm : $iAmPm;
										echo "<option value='".$i."'>".$iFormat." ".$amPm."</option>";
									}
								?>
							</select>
							<select class="w3-select" id="add_cita_minuto" name="add_cita_minuto">
								<option value="">Minuto</option>
								<?php
									for ($i = 0; $i <= 59; $i++)
									{
										$iFormat = $i < 10 ? "0".$i : $i;
										echo "<option value='".$i."'>".$iFormat."</option>";
									}
								?>
							</select>
							<select class="w3-select" id="add_cita_duracion" name="add_cita_duracion">
								<option value="">Duración</option>
								<option value="900">15 min</option>
								<option value="1200">20 min</option>
								<option value="1500">25 min</option>
								<option value="1800">30 min</option>
								<option value="2100">35 min</option>
								<option value="2400">40 min</option>
								<option value="2700">45 min</option>
								<option value="3000">50 min</option>
								<option value="3300">55 min</option>
								<option value="3600">1 hora</option>
								<option value="3900">1 hora 5 min</option>
								<option value="4200">1 hora 10 min</option>
								<option value="4500">1 hora 15 min</option>
								<option value="4800">1 hora 20 min</option>
								<option value="5100">1 hora 25 min</option>
								<option value="5400">1 hora 30 min</option>
								<option value="5700">1 hora 35 min</option>
								<option value="6000">1 hora 40 min</option>
								<option value="6300">1 hora 45 min</option>
								<option value="6600">1 hora 50 min</option>
								<option value="6900">1 hora 55 min</option>
								<option value="7200">2 horas</option>
								<option value="7500">2 horas 5 min</option>
								<option value="7800">2 horas 10 min</option>
								<option value="8100">2 horas 15 min</option>
								<option value="8400">2 horas 20 min</option>
								<option value="8700">2 horas 25 min</option>
								<option value="9000">2 horas 30 min</option>
								<option value="9300">2 horas 35 min</option>
								<option value="9600">2 horas 40 min</option>
								<option value="9900">2 horas 45 min</option>
								<option value="10200">2 horas 50 min</option>
								<option value="10500">2 horas 55 min</option>
								<option value="10800">3 horas</option>
								<option value="11100">3 horas 5 min</option>
								<option value="11400">3 horas 10 min</option>
								<option value="11700">3 horas 15 min</option>
								<option value="12000">3 horas 20 min</option>
								<option value="12300">3 horas 25 min</option>
								<option value="12600">3 horas 30 min</option>
								<option value="12900">3 horas 35 min</option>
								<option value="13200">3 horas 40 min</option>
								<option value="13500">3 horas 45 min</option>
								<option value="13800">3 horas 50 min</option>
								<option value="14100">3 horas 55 min</option>
								<option value="14400">4 horas</option>
								<option value="14700">4 horas 5 min</option>
								<option value="15000">4 horas 10 min</option>
								<option value="15300">4 horas 15 min</option>
								<option value="15600">4 horas 20 min</option>
								<option value="15900">4 horas 25 min</option>
								<option value="16200">4 horas 30 min</option>
								<option value="16500">4 horas 35 min</option>
								<option value="16800">4 horas 40 min</option>
								<option value="17100">4 horas 45 min</option>
								<option value="17400">4 horas 50 min</option>
								<option value="17700">4 horas 55 min</option>
								<option value="18000">5 horas</option>
								<option value="18300">5 horas 5 min</option>
								<option value="18600">5 horas 10 min</option>
								<option value="18900">5 horas 15 min</option>
								<option value="19200">5 horas 20 min</option>
								<option value="19500">5 horas 25 min</option>
								<option value="19800">5 horas 30 min</option>
								<option value="20100">5 horas 35 min</option>
								<option value="20400">5 horas 40 min</option>
								<option value="20700">5 horas 45 min</option>
								<option value="21000">5 horas 50 min</option>
								<option value="21300">5 horas 55 min</option>
								<option value="21600">6 horas</option>
								<option value="21900">6 horas 5 min</option>
								<option value="22200">6 horas 10 min</option>
								<option value="22500">6 horas 15 min</option>
								<option value="22800">6 horas 20 min</option>
								<option value="23100">6 horas 25 min</option>
								<option value="23400">6 horas 30 min</option>
								<option value="23700">6 horas 35 min</option>
								<option value="24000">6 horas 40 min</option>
								<option value="24300">6 horas 45 min</option>
								<option value="24600">6 horas 50 min</option>
								<option value="24900">6 horas 55 min</option>
								<option value="25200">7 horas</option>
							</select>
						</div>
					</div>
					<div class="w3-row w3-section">
						<div class="w3-col" style="width:50px"><label for="add_cita_empleado_telf"><i class="icon-id-badge" style="font-size:37px;"></i></label></div>
						<div class="w3-rest" style="padding-top: 0.5em;">
							<?php
								if (empty($empleado))
								{
							?>
								<select class="w3-select" id="add_cita_empleado_telf" name="add_cita_empleado_telf">
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
							<?php
								}
								else
								{
									$sql = "select nombre, apellido from empleado where empleado_telf = '".$empleado."';";
									$result = $bd->mysql->query($sql);
									unset($sql);
									if($result)
									{
										while($row = $result->fetch_array())
										{
											echo "<input type='hidden' id='add_cita_empleado_telf' name='add_cita_empleado_telf' value='".$empleado."'>";
											echo $row["nombre"]." ".$row["apellido"]; 	
										}
										unset($row);
										$result->free();
									}
									else
										unset($result);
								}
							?>
						</div>
					</div>
					<div class="w3-row w3-section">
						<div class="w3-col" style="width:50px"><label for="id_motivo_ingreso"><i class="icon-menu" style="font-size:37px;"></i></label></div>
						<div class="w3-rest">
							<select class="w3-select" id="add_cita_id_motivo_ingreso" name="add_cita_id_motivo_ingreso">
								<option value="">Tipo de Trabajo</option>
								<?php
									$sql="SELECT id_motivo_ingreso, motivo FROM motivo_ingreso order by motivo asc;";
									$result = $bd->mysql->query($sql);
									unset($sql);
									if($result)
									{
										while($row = $result->fetch_array())
										{
											echo"<option value='".$row["id_motivo_ingreso"]."'>".$row["motivo"]."</option>";
										}
										unset($row);
										$result->free();
									}
									else
										unset($result);
								?>
							</select>
						</div>
					</div>
					<div class="w3-row w3-section">
						<div class="w3-col" style="width:50px"><label for="add_cita_cliente_telf"><i class="icon-menu" style="font-size:37px;"></i></label></div>
						<div class="w3-rest">
							<select class="w3-select" id="add_cita_cliente_telf" name="add_cita_cliente_telf">
								<option value="">Cliente</option>
								<?php
									$sql="SELECT telf, nombre, apellido, alias FROM cliente;";
									$result = $bd->mysql->query($sql);
									unset($sql);
									if($result)
									{
										while($row = $result->fetch_array())
										{
											if(empty($row["alias"]))
												echo"<option value='".$row["telf"]."'>".$row["nombre"]." ".$row["apellido"]."</option>";
											else
												echo"<option value='".$row["telf"]."'>".$row["alias"]." - ".$row["nombre"]." ".$row["apellido"]."</option>";
										}
										unset($row);
										$result->free();
									}
									else
										unset($result);
								?>
							</select>
						</div>
					</div>
					<label for="observacion"><b>Nota</b></label>
					<div class="w3-row">
						<div class="w3-rest">
							<textarea style="float: left;width: 100%;height: auto;" id="add_cita_nota" name="add_cita_nota"></textarea>
						</div>
					</div>
					<div class="w3-row w3-section">
						<div class="w3-rest">
							<input class="w3-check" type="checkbox" id="add_cita_sms" name="add_cita_sms" value="1" checked>
							<label for="add_cita_sms">&nbsp;&nbsp;&nbsp;Notificar al cliente por SMS</label>
						</div>
					</div>
					<div class="w3-row w3-section">
						<p>
						<div class="w3-rest">
							<input type="button" class="w3-button w3-block w3-green" onclick="return confirmar_crear_cita();" value="Crear cita">
						</div>
						</p>
					</div>
				</form>
				<?php
				echo "</div>";
			echo "</div>";
		echo "</div>";
	}

	function formulario_horas_empleado($bd)
	{
		if (empty($_POST["empleado_telf"]))
		{
			$sql = "select * from empleado where visible = 1 order by nombre asc;";
			$result = $bd->mysql->query($sql);
			unset($sql);
			if ($result)
			{
				if (!empty($result->num_rows))
				{
					$array_citas = $result->fetch_all(MYSQLI_ASSOC);
					$result->free();
					echo "<div class='w3-container w3-card-4 w3-light-grey w3-margin'>";
					foreach ($array_citas as $row)
					{
						$color = $row["color"] ? $row["color"] : "#f99fbf";
						echo "<div class='w3-row w3-section w3-quarter'>";
						echo "<table border='0'>";
						echo "<tr><td style='background-color: ".$color."; width: 1.5em; min-width: 1.5em;' nowrap>&nbsp;</td><td nowrap>".$row["nombre"]." ".$row["apellido"]."</td></tr>";
						echo "</table>";
						echo "</div>";
					}
					echo "</div>";
				}
			}
			else
				unset ($result);
		}
		?>
		<div id="horarios" class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">			
			<div class="w3-row w3-section">
				<?php
					if (!empty($_POST["empleado_telf"]))
					{
						$sql = "select 
							c.*, 
							e.color, 
							m.motivo tipo, 
							concat(cc.nombre,' ',cc.apellido) cliente
						from citas c 
							inner join empleado e on c.empleado_telf = e.empleado_telf 
							inner join cliente cc on c.id_cliente = cc.id_cliente
							left join motivo_ingreso m on c.id_motivo_ingreso = m.id_motivo_ingreso
						where c.empleado_telf = '".$_POST["empleado_telf"]."' and e.visible = 1 and c.estado = 1;";
						$result = $bd->mysql->query($sql);
						unset($sql);
						if ($result)
						{
							if (!empty($result->num_rows))
							{
								$array_citas = $result->fetch_all(MYSQLI_ASSOC);
								$result->free();
								$pxMarginTop = 2.5;
								$pxHeigth = 37.5;
								foreach ($array_citas as $row)
								{
									$fecha_cita = strtotime(date("Y-m-d",$row["desde"]));
									$margin_top = ((($row["desde"] - $fecha_cita) - 21600) / 60) * $pxMarginTop;
									$height = (($row["hasta"] - $row["desde"]) * $pxHeigth) / 900;
									$hora_desde = date("h:i a",$row["desde"]);
									$hora_hasta = date("h:i a",$row["hasta"]);
									$color = $row["color"] ? $row["color"] : "#f99fbf";
									$tipo = $row["tipo"];
									$cliente = $row["cliente"];
									// echo "6->".strtotime("2022-05-21T09:40")."<br>";
									// echo "7->".strtotime("2022-05-21T10:00")."<br>";
									echo "<div id='cita_id_".$row["id_citas"]."' class='cita div-cita' style='background-color: ".$color."; margin-top: ".$margin_top."px; height: ".$height."px;'>";
										echo "<div class='w3-third'>";
											echo "<b>".$hora_desde." - ".$hora_hasta." - ".$cliente."</b>";
										echo "</div>";
										echo "<div class='w3-third'>";
											echo $tipo;
										echo "</div>";
									echo "</div>";
								}
								unset ($array_citas);
							}
						}
						else
							unset($result);
					}
					else
					{
						$sql = "select 
							c.*, 
							e.color, 
							m.motivo tipo, 
							concat(cc.nombre,' ',cc.apellido) cliente,
							concat(e.nombre,' ',e.apellido) empleado
						from citas c 
							inner join empleado e on c.empleado_telf = e.empleado_telf 
							inner join cliente cc on c.id_cliente = cc.id_cliente
							left join motivo_ingreso m on c.id_motivo_ingreso = m.id_motivo_ingreso
						where e.visible = '1' and c.estado = 1;";
						$result = $bd->mysql->query($sql);
						unset($sql);
						if ($result)
						{
							if (!empty($result->num_rows))
							{
								$array_citas = $result->fetch_all(MYSQLI_ASSOC);
								$result->free();
								$pxMarginTop = 2.5;
								$pxHeigth = 37.5;
								$empleado_aux = "";
								$margin_array = array();
								$i = 0;
								$margin_left = 6;
								foreach ($array_citas as $row)
								{									
									if ($i == 0)
									{
										$empleado_aux = $row["empleado_telf"];
										$margin_left = 6;
										$margin_array[$row["empleado_telf"]] = 6;
									}
									else
									{
										if ($empleado_aux != $row["empleado_telf"])
										{
											$encontrado = false;
											foreach ($margin_array as $i => $v)
											{
												if ($i == $row["empleado_telf"])
												{
													$encontrado = true;
													$margin_left = $v;
												}
											}
											if (!$encontrado)
											{
												$margin_left += 2;
											}
										}
									}

									$fecha_cita = strtotime(date("Y-m-d",$row["desde"]));
									$margin_top = ((($row["desde"] - $fecha_cita) - 21600) / 60) * $pxMarginTop;
									$height = (($row["hasta"] - $row["desde"]) * $pxHeigth) / 900;
									$hora_desde = date("h:i a",$row["desde"]);
									$hora_hasta = date("h:i a",$row["hasta"]);
									$color = $row["color"] ? $row["color"] : "#f99fbf";
									$tipo = $row["tipo"];
									$cliente = $row["cliente"];

									echo "<div id='cita_id_".$row["id_citas"]."' class='cita-multiple div-cita-multiple' style='margin-left: ".$margin_left."em; background-color: ".$color."; margin-top: ".$margin_top."px; height: ".$height."px;'>";
										echo "<p class='tooltip' style='font-size: 10px;'>";
										echo "&nbsp;";
										echo "<span class='tooltiptext'><b>".$row["empleado"]."</b><br>".$hora_desde." - ".$hora_hasta." - ".$cliente."</span>";
										echo "<p>";
									echo "</div>";

									$i++;
									$empleado_aux = $row["empleado_telf"];
								}
								unset ($array_citas);
							}
						}
						else
							unset($result);
					}
				?>
				<table class="table" cellpadding="1em" cellspacing="0">
					<?php
						$horaIni = 6;
						$horaFin = 21;
						for ($hora = $horaIni; $hora <= $horaFin ; $hora++)
						{
							$horaFormat = $hora > 12 ? $hora - 12 : $hora;
							$amPm = "";
							if ($hora == 12)
								$amPm = "M";
							if ($hora < 12)
								$amPm = "AM";
							if ($hora > 12)
								$amPm = "PM";
							if ($horaFormat < 10)
								$horaFormat = "0".$horaFormat;
							echo "<tr><td class='table-border-calendar-1'>".$horaFormat.":00".$amPm."<br><br><i id='add-cita-".$hora."' class='icon-calendar-plus-o add-cita' data-hora='".$hora."' style='font-size:37px;cursor:pointer;'></i></td></tr>";
						}
					?>
				</table>
			</div>
		</div>
		<?php
		crear_modal_add_cita($_POST["empleado_telf"], $bd);
	}

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        formulario_horas_empleado($bd);
    }
?>