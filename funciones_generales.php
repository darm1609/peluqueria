<?php
	function usuario_admin()
	{
		global $servidor, $puerto, $usuario, $pass, $basedatos;
		$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
		if($bd->conectado)
		{
			$sql="SELECT administrador FROM usuario WHERE login='".$_SESSION["login"]."';";
			$result = $bd->mysql->query($sql);
			if ($result)
			{
				$admin = $result->fetch_all(MYSQLI_ASSOC);
				$result->free();
				if ($admin[0]["administrador"] == 1)
				{
					unset($admin);
					return true;
				}
				else
				{
					unset($admin);
					return false;
				}
			}
			else
				unset($result);
		}
		return false;
	}

	function usuario_cajero()
	{
		global $servidor, $puerto, $usuario, $pass, $basedatos;
		$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
		if($bd->conectado)
		{
			$sql="SELECT cajero FROM usuario WHERE login='".$_SESSION["login"]."';";
			$result = $bd->mysql->query($sql);
			if ($result)
			{
				$admin = $result->fetch_all(MYSQLI_ASSOC);
				$result->free();
				if ($admin[0]["cajero"] == 1)
				{
					unset($admin);
					return true;
				}
				else
				{
					unset($admin);
					return false;
				}
			}
			else
				unset($result);
		}
		return false;
	}

	function fecha_dd_mm_yy($cad,$bd=false)
	{
		return $cad[8].$cad[9]."-".$cad[5].$cad[6]."-".$cad[0].$cad[1].$cad[2].$cad[3];
	}

	function nombre_empleado($empleado_cedula,$bd)
	{
		$sql="SELECT nombre, apellido FROM empleado WHERE empleado_cedula='".$empleado_cedula."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			$datos = $result->fetch_all(MYSQLI_ASSOC);
			$nombre = $datos[0]["nombre"]." ".$datos[0]["apellido"];
			$result->free();
			return $nombre;
		}
		else
			unset($result);
	}

	function motivo($id_motivo_ingreso,$bd)
	{
		$sql="SELECT motivo FROM motivo_ingreso WHERE id_motivo_ingreso='".$id_motivo_ingreso."';";
		$result = $bd->mysql->query($sql);
		unset($sql);
		if ($result)
		{
			$datos = $result->fetch_all(MYSQLI_ASSOC);
			$motivo = $datos[0]["motivo"];
			$result->free();
			return $motivo;
		}
		else
			unset($result);
	}
?>