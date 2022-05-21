<script type="text/javascript">
	$(document).ready(function(){
		$(window).resize(function() {
			let divHoras = document.getElementById("horarios");
			$(".cita").width(divHoras.clientWidth - 120);
			$(".cita").show();
		});

		let divHoras = document.getElementById("horarios");
		$(".cita").width(divHoras.clientWidth - 120);
		$(".cita").show();
	});

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
	}
</style>
<?php
    session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

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
						where c.empleado_telf = '".$_POST["empleado_telf"]."' and e.visible = 1;";
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
									// echo "6->".strtotime("2022-05-21T09:50")."<br>";
									// echo "7->".strtotime("2022-05-21T10:")."<br>";
									echo "<div id='cita_id_".$row["id_citas"]."' class='cita div-cita' style='background-color: ".$color."; margin-top: ".$margin_top."px; height: ".$height."px;'>";
										echo "<div class='w3-third'>";
											echo $hora_desde." - ".$hora_hasta." - ".$cliente;
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
						where e.visible = '1';";
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
									echo "<div id='cita_id_".$row["id_citas"]."' class='cita div-cita' style='background-color: ".$color."; margin-top: ".$margin_top."px; height: ".$height."px;'>";
										echo "<div class='w3-third'>";
											echo $hora_desde." - ".$hora_hasta." - ".$cliente;
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
				?>
				<table class="table" cellpadding="1em" cellspacing="0">
					<tr>
						<td class="table-border-calendar-1">6:00 AM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">7:00 AM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">8:00 AM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">9:00 AM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">10:00 AM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">11:00 AM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">12:00 M</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">01:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">02:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">03:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">04:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">05:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">06:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">07:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">08:00 PM</td>
					</tr>
					<tr>
						<td class="table-border-calendar-1">09:00 PM</td>
					</tr>
				</table>
			</div>
		</div>
		<?php
	}

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        formulario_horas_empleado($bd);
    }
?>