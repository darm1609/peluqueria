</div>
	<script type="text/javascript" src="js/formato.js"></script>
	
	<?php
		if(isset($error_1) and $error_1)//Error en index.php login y pass vacios
		{
			?>
		    <script language='JavaScript' type='text/JavaScript'>
				alertify.alert("","123EL LOGIN Y/O CONTRASE\u00d1A NO PUEDEN ESTAR VACIOS").set('label', 'Aceptar');
			</script>
			<?php
			unset($error_1);
		}
		if(isset($error_2) and $error_2)//Error en index.php login y pass no validos
		{
			?>
		    <script language='JavaScript' type='text/JavaScript'>
				alertify.alert("","EL LOGIN Y/O CONTRASE\u00d1A NO ES VALIDO").set('label', 'Aceptar');
			</script>
			<?php
			unset($error_2);
		}
	?>
</body>
</html>