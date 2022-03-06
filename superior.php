<?php
	
?>
<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
	<button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="icon-menu"></i>Menu</button>
	<span class="w3-bar-item w3-right">
		<i class="icon-exit texto-boton" label="Cerrar Sesion" title="Cerrar Sesion" onclick="document.getElementById('f_cerrar').submit();"></i>
	</span>
</div>
<form id="f_cerrar" name="f_cerrar" method="post" action="index.php">
	<input type="hidden" id="cerrar" name="cerrar" value="">
</form>