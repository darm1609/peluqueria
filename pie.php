</div>
	<script>
		// Get the Sidebar
		var mySidebar = document.getElementById("mySidebar");

		// Get the DIV with overlay effect
		var overlayBg = document.getElementById("myOverlay");

		// Toggle between showing and hiding the sidebar, and add overlay effect
		function w3_open() 
		{
		    if (mySidebar.style.display === 'block') 
		    {
		        mySidebar.style.display = 'none';
		        overlayBg.style.display = "none";
		    } 
		    else
		    {
		        mySidebar.style.display = 'block';
		        overlayBg.style.display = "block";
		    }
		}

		// Close the sidebar with the close button
		function w3_close() 
		{
		    mySidebar.style.display = "none";
		    overlayBg.style.display = "none";
		}
	</script>
	<?php
		if(isset($error_1) and $error_1)//Error en index.php login y pass vacios
		{
			?>
		    <script language='JavaScript' type='text/JavaScript'>
				alertify.alert("","EL LOGIN Y/O CONTRASE\u00d1A NO PUEDEN ESTAR VACIOS").set('label', 'Aceptar');
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