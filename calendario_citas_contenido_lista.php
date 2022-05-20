<script type="text/javascript">
	$(document).ready(function(){
		$(window).resize(function() {
			let divHoras = document.getElementById("horarios");
			$(".cita").width(divHoras.clientWidth - 105);
			$(".cita").show();
		});

		let divHoras = document.getElementById("horarios");
		$(".cita").width(divHoras.clientWidth - 105);
		$(".cita").show();
	});
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
		background-color: #ff0000;
		height: 2em;
		width: 1382px;
		margin-left: 5em;
		display: none;
	}
</style>
<?php
    session_start();
	require("head.php");
	require("config.php");
	require("librerias/basedatos.php");
	require("funciones_generales.php");

	function formulario_horas($bd)
	{
		?>
		<div id="horarios" class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
			<div class="w3-row w3-section">
				<div id="cita_id_1" class="div-cita cita">
					Cita
				</div>
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
        formulario_horas($bd);
    }
?>