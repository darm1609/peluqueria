<?php	
	global $servidor;
	global $puerto;
	global $usuario;
	global $pass;
	global $basedatos;
	$servidor='localhost';
	$puerto='3306';
	$usuario='root';
	$pass='123456';
	$basedatos='peluqueria';
	ini_set("display_errors","On");
	date_default_timezone_set("America/Bogota");
	setlocale(LC_MONETARY, 'es_CO');
	global $mensajeRecordatorio;
	global $mensajeCreacionCita;
	global $mensajeRecordatorioUnaHora;
	$mensajeRecordatorioUnaHora = "DULCE VANIDAD le recuerda que tiene una cita con [empleado] que comienza en aproximadamente 1 hora";
	$mensajeRecordatorio = "DULCE VANIDAD le recuerda que tiene una cita con [empleado] que comienza en aproximadamente [hora]";
	$mensajeCreacionCita = "DULCE VANIDAD le informa que se agendo una cita para el [dia] [hora] con [empleado]";
?>