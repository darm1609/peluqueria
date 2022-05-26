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
	$mensajeRecordatorio = "DULCE VANIDAD le recuerda que tiene una cita con [empleado] el día [dia] a la(s) [hora] [tipo]";
	$mensajeCreacionCita = "DULCE VANIDAD le informa que se agendo una cita para el [dia] [hora] con [empleado] [tipo]";
	require("librerias/httpPHPAltiria.php");
	$altiriaSMS = new AltiriaSMS();
	$altiriaSMS->setLogin('darm1609@gmail.com');
	$altiriaSMS->setPassword('uy5smaqb');
	$altiriaSMS->setDebug(false);
?>