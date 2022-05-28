<?php
    session_start();
	require("head.php");
	require("config.php");
	require("funciones_generales.php");
	require("librerias/basedatos.php");

    function enviar_sms_cliente($altiriaSMS, $bd)
    {
        ?>
        <script>
            $("#resultado").append("<div class='w3-row w3-section' id='box_de_envio'><b>Mensaje enviado a:</b><br><br></div>");
        </script>
        <?php
        foreach ($_POST as $i => $v)
        {
            if (stristr($i, "cliente_"))
            {
                $cliente = "57".$v;
                $mensaje = $_POST["mensaje"];
                $response = $altiriaSMS->sendSMS($cliente, $mensaje);
                $sql = "select telf, nombre, apellido from cliente where telf = '".$v."';";
                $result = $bd->mysql->query($sql);
                unset($sql);
                if ($result)
                {
                    $array = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                    if ($response)
                    {
                        ?>
                        <script>
                            $("#resultado").append("<?php echo '<i class=\"icon-check-circle-o\" style=\"font-size:37px; color:green;\"></i> '.$array[0]['telf'].' '.$array[0]['nombre'].' '.$array[0]['apellido'].'<br>'; ?>");
                        </script>
                        <?php
                    }
                    else
                    {
                        ?>
                        <script>
                            $("#resultado").append("<?php echo '<i class=\"icon-times-circle-o\" style=\"font-size:37px; color:red;\"></i> '.$array[0]['telf'].' '.$array[0]['nombre'].' '.$array[0]['apellido'].'<br>'; ?>");
                        </script>
                        <?php
                    }
                }
            }
        }
        ?>
        <script>
            $("#resultado").append("<br>");
        </script>
        <?php
    }

    function enviar_sms_todos($altiriaSMS, $bd)
    {
        ?>
        <script>
            $("#resultado").append("<div class='w3-row w3-section' id='box_de_envio'><b>Mensaje enviado a:</b><br><br></div>");
        </script>
        <?php
        $sql = "select telf, nombre, apellido from cliente;";
        $result = $bd->mysql->query($sql);
        unset($sql);
        if ($result)
        {
            $array = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            foreach ($array as $row)
            {
                $cliente = "57".$row["telf"];
                $mensaje = $_POST["mensaje"];
                $response = $altiriaSMS->sendSMS($cliente, $mensaje);
                if ($response)
                {
                    ?>
                    <script>
                        $("#resultado").append("<?php echo '<i class=\"icon-check-circle-o\" style=\"font-size:37px; color:green;\"></i> '.$row['telf'].' '.$row['nombre'].' '.$row['apellido'].'<br>'; ?>");
                    </script>
                    <?php
                }
                else
                {
                    ?>
                    <script>
                        $("#resultado").append("<?php echo '<i class=\"icon-times-circle-o\" style=\"font-size:37px; color:red;\"></i> '.$row['telf'].' '.$row['nombre'].' '.$row['apellido'].'<br>'; ?>");
                    </script>
                    <?php
                }
            }
        }
        ?>
        <script>
            $("#resultado").append("<br>");
        </script>
        <?php
    }

    function todos()
    {
        if (isset($_POST["enviar_todos"]))
            return true;
        return false;
    }

    function formulario_envios($bd)
    {
        ?>
		<div class="w3-container w3-card-4 w3-light-grey w3-text-blue w3-margin" id="resultado" name="resultado" method="post">
        </div>
		<?php
    }

    global $servidor, $puerto, $usuario, $pass, $basedatos;
	$bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
	if($bd->conectado)
	{
        //print_r($_POST);
        formulario_envios($bd);
        if (todos())
        {
            enviar_sms_todos($altiriaSMS, $bd);
        }
        else
        {
            enviar_sms_cliente($altiriaSMS, $bd);
        }
    }
?>