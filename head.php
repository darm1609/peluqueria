<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
	<meta name="description" content="">
	<meta name="Keywords" content="">
	<meta name="author" content="Daniel Rodriguez">
	<meta name="title" content="Dulce Vanidad">
	<title>Dulce Vanidad</title>
	
	<script type="text/javascript" src="js/validaciones.js"></script>
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="CryptoJS v3.1.2/rollups/sha3.js"></script>
	<!--Ejemplo SHA3-->
	<script type="text/javascript">
		//var hash = CryptoJS.SHA3("vinka2000");
		//alert(hash);
	</script>
	<!--Fin Ejemplo SHA3-->
	<!--Libreria alertify-->
	<link rel="stylesheet" type="text/css" href="alertifyjs/css/alertify.css">
	<script type="text/javascript" src="alertifyjs/alertify.min.js"></script>
	<!--Fin Libreria alertify-->
	<link rel="stylesheet" href="w3/w3.css">
	<link rel="stylesheet" href="w3/font-awesome.min.css">
	<link rel="stylesheet" href="css/interfaz.css">
	<!--Icomoon-->
    <link rel="stylesheet" href="icomoon/style.css">
	<!--Fin Icomoon-->

	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-1.12.4.js"></script>
	<script src="js/jquery-ui.js"></script>

	<link rel="stylesheet" href="css/tabla1.css">
	<link rel="stylesheet" href="css/inputs.css">

	<!--<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/bootstrap.min.js"></script>-->

    <script type="text/javascript">
    	function objetoAjax()
        {
            var xmlhttp = false;
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {

                try {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                    xmlhttp = false; }
            }

            if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
              xmlhttp = new XMLHttpRequest();
            }
            return xmlhttp;
        }
    </script>
    
</head>
<!--class="w3-light-grey"--> 
<body class="background-color">
<?php
	
?>