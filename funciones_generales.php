<?php
	if ( ! function_exists( 'money_format' ) ) {

		function money_format($format, $number)
		{
			$regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
					  '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
			if (setlocale(LC_MONETARY, 0) == 'C') {
				setlocale(LC_MONETARY, '');
			}
			$locale = localeconv();
			preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
			foreach ($matches as $fmatch) {
				$value = floatval($number);
				$flags = array(
					'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
								   $match[1] : ' ',
					'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
					'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
								   $match[0] : '+',
					'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
					'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
				);
				$width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
				$left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
				$right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
				$conversion = $fmatch[5];
		
				$positive = true;
				if ($value < 0) {
					$positive = false;
					$value  *= -1;
				}
				$letter = $positive ? 'p' : 'n';
		
				$prefix = $suffix = $cprefix = $csuffix = $signal = '';
		
				$signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
				switch (true) {
					case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
						$prefix = $signal;
						break;
					case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
						$suffix = $signal;
						break;
					case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
						$cprefix = $signal;
						break;
					case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
						$csuffix = $signal;
						break;
					case $flags['usesignal'] == '(':
					case $locale["{$letter}_sign_posn"] == 0:
						$prefix = '(';
						$suffix = ')';
						break;
				}
				if (!$flags['nosimbol']) {
					$currency = $cprefix .
								($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
								$csuffix;
				} else {
					$currency = '';
				}
				$space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';
		
				$value = number_format($value, $right, $locale['mon_decimal_point'],
						 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
				$value = @explode($locale['mon_decimal_point'], $value);
		
				$n = strlen($prefix) + strlen($currency) + strlen($value[0]);
				if ($left > 0 && $left > $n) {
					$value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
				}
				$value = implode($locale['mon_decimal_point'], $value);
				if ($locale["{$letter}_cs_precedes"]) {
					$value = $prefix . $currency . $space . $value . $suffix;
				} else {
					$value = $prefix . $value . $space . $currency . $suffix;
				}
				if ($width > 0) {
					$value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
							 STR_PAD_RIGHT : STR_PAD_LEFT);
				}
		
				$format = str_replace($fmatch[0], $value, $format);
			}
			return $format;
		  }
		}

	function Transferencia($val, $bd)
	{
		return money_format('%.2n', $val);
	}

	function Efectivo($val, $bd)
	{
		return money_format('%.2n', $val);
	}

	function Total($val, $bd)
	{
		return money_format('%.2n', $val);
	}

	function Datáfono($val, $bd)
	{
		return money_format('%.2n', $val);
	}

	function Deuda($val, $bd)
	{
		return money_format('%.2n', $val);
	}

	function Pagado($val, $bd)
	{
		return money_format('%.2n', $val);
	}

	function Fecha($val, $bd)
	{
		return $val;
	}

	function usuario_empleado()
	{
		global $servidor, $puerto, $usuario, $pass, $basedatos;
		$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
		if($bd->conectado)
		{
			$sql="SELECT empleado FROM usuario WHERE empleado_telf='".$_SESSION["login"]."';";
			$result = $bd->mysql->query($sql);
			if ($result)
			{
				$empleado = $result->fetch_all(MYSQLI_ASSOC);
				$result->free();
				if (count($empleado))
				{
					if ($empleado[0]["empleado"] == 1)
					{
						unset($empleado);
						return true;
					}
					else
					{
						unset($empleado);
						return false;
					}
				}
			}
			else
				unset($result);
		}
		return false;
	}

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
				if (count($admin)) {
					if ($admin[0]["administrador"] == 1)
					{
						unset($admin);
						return true;
					}
				}
				unset($admin);
				return false;
			}
			else
				unset($result);
		}
		return false;
	}

	function usuario_consulta()
	{
		global $servidor, $puerto, $usuario, $pass, $basedatos;
		$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
		if($bd->conectado)
		{
			$sql="SELECT consulta FROM usuario WHERE login='".$_SESSION["login"]."';";
			$result = $bd->mysql->query($sql);
			if ($result)
			{
				$admin = $result->fetch_all(MYSQLI_ASSOC);
				$result->free();
				if (count($admin))
				{
					if ($admin[0]["consulta"] == 1)
					{
						unset($admin);
						return true;
					}
				}
				unset($admin);
				return false;
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
				if (count($admin))
				{
					if ($admin[0]["cajero"] == 1)
					{
						unset($admin);
						return true;
					}
				}
				unset($admin);
				return false;
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

	function nombre_empleado($empleado_telf,$bd)
	{
		$sql="SELECT nombre, apellido FROM empleado WHERE empleado_telf='".$empleado_telf."';";
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