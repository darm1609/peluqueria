<?php
	
	class BaseDatos_Principal
	{
		private $servidor;
		private $puerto;
		private $usuario;
		private $password;
		private $basedatos;
		public $mysql;
		public $link;
		public $mensaje;
		public $conectado;
		public $ultimo_result;
        public $log;
		
		function __construct($servidor,$puerto,$usr,$pass,$basedatos)
		{
			$this->servidor=$servidor;
			$this->puerto=$puerto;
			$this->usuario=$usr;
			$this->password=$pass;
			$this->basedatos=$basedatos;
			$mySqlConex = new mysqli();
			$this->mysql = $mySqlConex;
			$this->link = $mySqlConex->real_connect($servidor, $usr, $pass, $basedatos);
			if(!$this->link)
			{
				$this->mensaje="FALLA CONEXION CON MYSQL";
				$this->conectado=false;
			}
			else
			{
				$this->mensaje="CONEXION SATISFACTORIA";
				$this->conectado=true;
			}
		}
	}
	
	class BaseDatos extends BaseDatos_Principal
	{
		// constructor
    	public function __construct($servidor,$puerto,$usr,$pass,$basedatos)
    	{
    		parent::__construct($servidor,$puerto,$usr,$pass,$basedatos);
    	}

        /*
		Recibe: Numero de campos a insertar, Nombre de la Base de Datos, Nombre de la Tabla, Nombre del campo 1, Nombre del campo 2,..., Nombre del campo N, Valor del campo 1, Valor del campo 2,..., Valor del campo N, nombre del campo de id tipo secuencia de ultima insercion
		Devuelve: TRUE si realiza el insert satisfactoriamente y FALSE si ocurre un error.
		*/
		function insertar_datos()
		{
			$arreglo=func_get_args();
			if(is_numeric($arreglo[0]))
			{
				if(count($arreglo)==2*$arreglo[0]+3 or count($arreglo)==2*$arreglo[0]+4)
				{	
					$cadsql="INSERT INTO ".$arreglo[2]." (";
					for($i=1;$i<=$arreglo[0];$i++)
						$cadsql.=$arreglo[$i+2].", ";
					$cadsql[strlen($cadsql)-2]=")";
					$cadsql.="VALUES (";
					for($i=$arreglo[0]+3;$i<=$arreglo[0]*2+2;$i++)
					{
						if(!empty($arreglo[$i]) or $arreglo[$i]=="0")
							$cadsql.="'".$arreglo[$i]."', ";
						else
							$cadsql.="NULL, ";
					}
					$cadsql[strlen($cadsql)-2]=")";
					$cadsql[strlen($cadsql)-1]=";";
					//echo $cadsql."<br>";
                    //$this->log.=$cadsql."\n\r";
                    //$this->ultimo_result=@pg_query($this->link,$cadsql);
					$result = $this->mysql->query($cadsql);
					if($result)
					{
						$this->ultimo_result = $this->mysql->insert_id;
						return true;
					}
					else
						return false;
				}
				else
					return false;
			}
			else
				return false;
		}
		
		/*
		Recibe: Numero de campos de busqueda, Numero de campos a actualizar, Nombre de la base de datos, Nombre de la tabla, Nombre del campo de busqueda 1, Valor del campo de busqueda 1,...Nombre del campo de busqueda N, Valor del campo de busqueda N, Nombre del campo a actualizar 1, Valor original del campo a actualizar 1, Valor nuevo del campo a actualizar 1,...,Nombre del campo a actualizar N, Valor original del campo a actualizar N, Valor nuevo del campo a actualizar N
		Devuelve: TRUE si realiza el insert satisfactoriamente y FALSE si ocurre un error.
		*/
		function actualizar_datos()
		{
			$arreglo=func_get_args();
			$cambio=false;
			if(is_numeric($arreglo[0]) and is_numeric($arreglo[1]))
			{
				if(count($arreglo)==$arreglo[0]*2+$arreglo[1]*3+4)
				{
					$cadsql="UPDATE ".$arreglo[3]." SET ";
					for($i=$arreglo[0]*2+4;$i<=count($arreglo)-2;$i+=3)
					{
						if($arreglo[$i+1]!=$arreglo[$i+2])
						{
							$cambio=true;
							if(!empty($arreglo[$i+2]) or (is_numeric($arreglo[$i+2]) and $arreglo[$i+2]==0))
								$cadsql.=$arreglo[$i]."='".$arreglo[$i+2]."', ";
                                                        else
								$cadsql.=$arreglo[$i]."=NULL, ";
						}
					}
					$cadsql[strlen($cadsql)-2]=" ";
					$cadsql.="WHERE ";
					for($i=0;$i<=$arreglo[0];$i+=2)
                        $cadsql.=$arreglo[3].".".$arreglo[$i+4]."='".$arreglo[$i+4+1]."' AND ";
					$cadsql[strlen($cadsql)-2]=" ";
					$cadsql[strlen($cadsql)-3]=" ";
					$cadsql[strlen($cadsql)-4]=" ";
					$cadsql=trim($cadsql);
					$cadsql.=";";
					//echo $cadsql."<br>";
                    //$this->log.=$cadsql."\n\r"; 
					if($cambio)
					{
						if($this->mysql->query($cadsql))
							return true;
						else
							return false;
					}
					else
						return true;
				}
				else
					return false;
			}
			else 
				return false;		
		}
		
		/*
		Recibe: Numero de campos de busqueda, Nombre de la base de datos, Nombre de la tabla, Nombre del campo de busqueda 1, Valor del campo de busqueda 1,...Nombre del campo de busqueda N, Valor del campo de busqueda N
		Devuelve: TRUE si realiza el insert satisfactoriamente y FALSE si ocurre un error.
		*/
		function eliminar_datos()
		{
			$arreglo=func_get_args();
			if(is_numeric($arreglo[0]))
			{
				if(count($arreglo)==$arreglo[0]*2+3)
				{
					$cadsql="DELETE FROM ".$arreglo[2]." WHERE ";
					for($i=3;$i<=count($arreglo)-2;$i+=2)
					{
						$cadsql.=$arreglo[$i]."='".$arreglo[$i+1]."' AND ";
					}
					$cadsql[strlen($cadsql)-1]=" ";
					$cadsql[strlen($cadsql)-2]=" ";
					$cadsql[strlen($cadsql)-3]=" ";
					$cadsql[strlen($cadsql)-4]=" ";
					$cadsql=trim($cadsql);
					$cadsql.=";";
                    //echo $cadsql."<br>";
                    //$this->log.=$cadsql."\n\r"; 
					if($this->mysql->query($cadsql))
						return true;
					else
						return false;
				}
				else
					return false;
			}
			else
				return false;
		}	
		
		/**********************************************************************************************
		Funcion: Existe
		Parametros Recibidos:
			Recibe una lista de variables las cuales son: numero de campos, nombre de la tabla, campo 1, valor 1, campo 2, valor 2, campo n, valor n
		Objetivo: Devolver true si existe un registro en la tabla que coinsida con los campos y valores dados
		**********************************************************************************************/
		function existe()
		{
			$numargs=func_num_args();
			$arg_list=func_get_args();
			$num_campos=$arg_list[0];
			$cadconsul="SELECT * FROM $arg_list[1] WHERE ";
			for($i=2;$i<=$num_campos+2;$i++)
			{
				$cadconsul.="$arg_list[$i]=";
				$i++;
				$cadconsul.="'$arg_list[$i]' AND ";
			}
			$cadconsul[strlen($cadconsul)-1]=" ";
			$cadconsul[strlen($cadconsul)-2]=" ";
			$cadconsul[strlen($cadconsul)-3]=" ";
			$cadconsul[strlen($cadconsul)-4]=" ";
			$cadconsul=trim($cadconsul);
			$cadconsul.=";";
			//echo $cadconsul;
			//$cadconsul=utf8_encode($cadconsul);
			$consul=$this->mysql->query($cadconsul);
			if($consul)
			{
				$num=$consul->num_rows;
				$consul->free();
				if(!empty($num))
				{
					unset($num);
					return true;
				}
				else
				{
					unset($num);
				}
			}
			return false;
        }
        
        public function crear_log()
        {
            $f=fopen("log/bd.txt","w");
            if($f)
            {
                fputs($f,$this->log); 
                fclose($f);
            }
        }
	}	
?>