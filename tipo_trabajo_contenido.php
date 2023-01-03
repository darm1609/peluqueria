<script type="text/javascript">
	
	$(document).ready(function(){
		$("#agregar_trabajo").click(function(){
			if($("#divfagregar").is(':visible'))
				$("#divfagregar").hide("linear");
			else
				$("#divfagregar").show("swing");
		});

		$("#modo_principal").click(function(){
			if($("#agregar_nuevo_secundario").is(':visible')) {
				$("#agregar_nuevo_secundario").hide("linear");
			}
			$("#agregar_nuevo_principal").show("swing");
		});

		$("#modo_secundario").click(function(){
			if($("#agregar_nuevo_principal").is(':visible')) {
				$("#agregar_nuevo_principal").hide("linear");
			}
			$("#agregar_nuevo_secundario").show("swing");	
		});
	});

	var nextinput_nuevo_principal=0;

	function agregar_campos_nuevo_principal(arreglo)
	{
		let n = arreglo.length;
		nextinput_nuevo_principal++;
		campo="<div id='contenido_"+nextinput_nuevo_principal+"' class='w3-row w3-section' style='border:1px solid #cccccc;padding:5px;'>";
		campo+="<label for='sel_tipo_trabajo_secundario_nuevo_principal_"+nextinput_nuevo_principal+"' class='w3-text-blue'><b>Secundario existente</b></label>";
		campo+="<select class='w3-select w3-border' id='sel_tipo_trabajo_secundario_nuevo_principal_"+nextinput_nuevo_principal+"' name='sel_tipo_trabajo_secundario_nuevo_principal_"+nextinput_nuevo_principal+"'>";
		campo+="<option value=''>Trabajo</option>";
		let i;
		for(i=0;i<n;i++)
		{
			campo+="<option value='"+arreglo[i][0]+"'>"+arreglo[i][1]+"</option>";
		}
		campo+="</select>";
		campo+="<label for='tipo_trabajo_secundario_nuevo_principal_"+nextinput_nuevo_principal+"' class='w3-text-blue'><b>Secundario nuevo</b></label>";
		campo+="<input class='w3-input w3-border' id='tipo_trabajo_secundario_nuevo_principal_"+nextinput_nuevo_principal+"' name='tipo_trabajo_secundario_nuevo_principal_"+nextinput_nuevo_principal+"' type='text'>";
		campo+="</div>";
		$("#div_tipo_de_trabajo_nuevo_pricipal").append(campo);
	}

	function eliminar_campos_nuevo_principal()
	{
		if(nextinput_nuevo_principal>=1)
		{
			$("#contenido_"+nextinput_nuevo_principal).remove();
			nextinput_nuevo_principal--;
		}
	}

</script>
<header class="w3-container" style="padding-top:22px">
	<h5><b>Administración de Trabajos</b></h5>
</header>
<?php

	function formulario_agregar_trabajo($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="fagregar" name="fagregar" method="post">
			<h2 class="w3-center">Nuevo Trabajo</h2>	
			<div class="w3-row w3-section">
				<label>
					<div class="w3-cell" style="width:50px"><input class="w3-radio" type="radio" id="modo_principal" name="modo" value="principal" checked></div>
					<div class="w3-cell">	
						Agregar nuevo principal
					</div>
				</label>
				<label>
					<div class="w3-cell" style="width:50px"><input class="w3-radio" type="radio" id="modo_secundario" name="modo" value="secundario"></div>
					<div class="w3-cell">	
						Agregar nuevo secundario
					</div>
				</label>
			</div>

			<?php
				$sql="SELECT id_motivo_ingreso, motivo FROM motivo_ingreso WHERE visible='1' AND principal='0' order by motivo asc;";
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
				$arreglo=json_encode($arreglo);
			?>

			<div id="agregar_nuevo_principal">
				<div class="w3-row w3-section">
					<label for="trabajo" class='w3-text-blue'><b>Nuevo&nbsp;principal</b></label>
					<div class="w3-rest">
						<input class="w3-input w3-border" id="trabajo_nuevo_principal" name="trabajo_nuevo_principal" type="text" placeholder="Trabajo">
					</div>
				</div>
				<div class="w3-row w3-section">
					<p>
						Secundario:
						<?php
							echo"<i class='icon-plus4 icon_mas' onclick='agregar_campos_nuevo_principal(".$arreglo.");'></i>";
						?>
						&nbsp;
						<i class="icon-minus3 icon_menos" onclick="eliminar_campos_nuevo_principal();"></i>
					</p>
				</div>
				<div id="div_tipo_de_trabajo_nuevo_pricipal"></div>
			</div>

			<div id="agregar_nuevo_secundario" style='display:none;'>
				<div class="w3-row w3-section">
					<label for="trabajo" class='w3-text-blue'><b>Nuevo&nbsp;secundario</b></label>
					<div class="w3-rest">
						<input class="w3-input w3-border" id="trabajo" name="trabajo" type="text" placeholder="Tipo de Trabajo">
					</div>
				</div>
				<div class="w3-row w3-section">
					<label for="trabajo" class='w3-text-blue'><b>Principal existente</b></label>
					<div class="w3-rest">
						<select class='w3-select w3-border' id='sel_tipo_trabajo_principal' name='sel_tipo_trabajo_principal'>
							<option value=''>Trabajo</option>
							<?php 
								$sql="SELECT id_motivo_ingreso, motivo FROM motivo_ingreso WHERE visible='1' AND principal='1' order by motivo asc;";
								$result = $bd->mysql->query($sql);
								unset($sql);
								if($result)
								{
									$arreglo=array();
									while($row = $result->fetch_array())
									{
										echo "<option value='".$row["id_motivo_ingreso"]."'>".$row["motivo"]."</option>";
									}
									$result->free();
								}
								else
									unset($result);
							?>
						</select>
					</div>
				</div>
			</div>

			<div class="w3-row w3-section">
				<input type="button" class="w3-button w3-block w3-green" onclick="submit_nuevo();" value="Guardar">
			</div>
		</form>
		<?php
	}

	function formulario_busqueda($bd)
	{
		?>
		<form class="w3-container w3-card-4 w3-light-grey w3-margin" id="fbusqueda" name="fbusqueda" method="post">
			<h2 class="w3-text-blue"><i class="icon-search3"></i>&nbsp;Busqueda</h2>
			<p>
				<label>
				<input class="w3-radio" type="radio" id="especificar" name="sel_opcion" value="especificar" onclick="habilitar_especificar();">
				Especificar
				</label>
			</p>
			<div class="w3-row w3-section">
				<table border="0" style="width: 100%;">
					<tr>
						<td align="right">
							<input class="w3-check" type="checkbox" id="chbtelf" name="chbtelf" disabled onclick="if(document.getElementById('chbtelf').checked){document.getElementById('btelf').disabled=false;}else{document.getElementById('btelf').disabled=true;}">
						</td>
						<td>
							<label>
								Tel&eacute;fono
								<input class="w3-input w3-border" type="text" id="btelf" name="btelf" onkeypress="return NumCheck2(event, this)" disabled>
							</label>
						</td>
					</tr>
					<tr>
						<td align="right">
							<input class="w3-check" type="checkbox" id="chbnombre" name="chbnombre" disabled onclick="if(document.getElementById('chbnombre').checked){document.getElementById('bnombre').disabled=false;}else{document.getElementById('bnombre').disabled=true;}">
						</td>
						<td>
							<label>
								Nombre
								<input class="w3-input w3-border" type="text" id="bnombre" name="bnombre" disabled>
							</label>
						</td>
					</tr>
				</table>
			</div>
			<p>
				<label>
				<input class="w3-radio" type="radio" id="listar" name="sel_opcion" value="listar" onclick="deshabilitar_especificar();" checked>
				Listar
				</label>
			</p>
			<div class="w3-row w3-section">
				<input class="w3-button w3-block w3-dulcevanidad" type="button" id="enviar" name="enviar" value="Buscar" onclick="return enviardatos_busqueda();">
			</div>
		</form>
		<?php
	}

	global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
		if(usuario_admin() or usuario_cajero())
		{
			?>
			<div class="w3-container">
				<button id='agregar_trabajo' class="w3-button w3-dulcevanidad"><i class='icon-plus4'>&nbsp;</i>Agregar</button>
			</div>
			<?php
			echo"<div id='divfagregar' class='w3-container' style='display:none;'>";
				formulario_agregar_trabajo($bd);
			echo"</div>";
			formulario_busqueda($bd);
			echo"<div id='loader'></div>";
			echo"<div id='divformulariolista'></div>";			
		}
		else
		{
			?>
			<div class="w3-panel w3-yellow">
				<h3>Advertencia</h3>
				<p>Acceso Restringido / Solo Administradores o Cajeros</p>
			</div> 
			<?php
		}
	}
?>