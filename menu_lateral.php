<?php
  function mostrar_nombre_usuario()
  {
    global $servidor, $puerto, $usuario, $pass, $basedatos;
    $bd=new BaseDatos($servidor,$puerto,$usuario,$pass,$basedatos);
    if($bd->conectado)
    {
      if(isset($_SESSION["login"]))
      {
        $login=$_SESSION["login"];
      }
      else
      {
        if (isset($_POST["chEmpleado"]))
        {
          if(isset($_POST["selEmpleado"]))
          {
            $login=$_POST["selEmpleado"];
          }
        }
        else
        {
          if(isset($_POST["login"]))
          {
            $login=$_POST["login"];
          }
        }
      }
      $sql="SELECT nombre, apellido FROM usuario WHERE login='".$login."';";
      $result = $bd->mysql->query($sql);
      unset($sql);
      if ($result)
      {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        if (count($row))
        {
          echo $row[0]["nombre"]."&nbsp;".$row[0]["apellido"];
          $result->free();
        }
        else
        {
          $sql = "SELECT e.* FROM usuario u inner join empleado e on e.empleado_telf = u.empleado_telf where e.empleado_telf = '".$login."';";
          $result = $bd->mysql->query($sql);
          unset($sql);
          if ($result)
          {
            $row = $result->fetch_all(MYSQLI_ASSOC);
            if (count($row))
            {
              echo $row[0]["nombre"]."&nbsp;".$row[0]["apellido"];
            }
          }
        }
      }
      else
        unset($result);
    }
  }
?>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar">
  <br>
  <div class="w3-container w3-row">
    <br>
    <div class="w3-col s4">
      <img src="imagenes/avatar2.png" class="w3-circle w3-margin-right" style="width:46px">
    </div>
    <div class="w3-col s8 w3-bar">
      <span>Bienvenid@, <strong><?php mostrar_nombre_usuario(); ?></strong></span><br>
      <a href="#" class="w3-bar-item w3-button"><i class="icon-user"></i></a>
      <a href="editar_usuario.php" class="w3-bar-item w3-button"><i class="icon-cog"></i></a>
      <a href="calendario_citas.php" class="w3-bar-item w3-button"><i class=" icon-calendar2"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>MENU</h5>
  </div>
  <div class="w3-bar-block">
    <!--<a href="#" class="w3-bar-item w3-button w3-padding" onclick="w3_close()" title="close menu"></a>-->
    <?php
      if (usuario_empleado())
      {
    ?>
      <a href="calendario_citas.php" style="text-decoration:none;" class="w3-bar-item w3-button w3-padding <?php if(isset($citas)) echo "w3-dulcevanidad"; ?>">Citas</a>
    <?php        
      }
      else
      {
    ?>
      <a href="usuario.php" style="text-decoration:none;" class="w3-bar-item w3-button w3-padding <?php if(isset($u)) echo "w3-dulcevanidad"; ?>">Usuario</a>
      <a href="cliente.php" style="text-decoration:none;" class="w3-bar-item w3-button w3-padding <?php if(isset($c)) echo "w3-dulcevanidad"; ?>">Cliente</a>
      <a href="empleado.php" class="w3-bar-item w3-button w3-padding <?php if(isset($e)) echo "w3-dulcevanidad"; ?>">Empleado</a>
      <a href="campaña.php" style="text-decoration:none;" class="w3-bar-item w3-button w3-padding <?php if(isset($campaña)) echo "w3-dulcevanidad"; ?>">Campa&ntilde;a</a>
      <a href="tipo_trabajo.php" class="w3-bar-item w3-button w3-padding <?php if(isset($tipo)) echo "w3-dulcevanidad"; ?>">Tipo de Trabajo</a>
      <a href="vale_pago.php" class="w3-bar-item w3-button w3-padding <?php if(isset($vale_pago)) echo "w3-dulcevanidad"; ?>">Vale Pago</a>
      <a href="egreso.php" class="w3-bar-item w3-button w3-padding <?php if(isset($egreso)) echo "w3-dulcevanidad"; ?>">Egreso</a>
      <a href="venta.php" class="w3-bar-item w3-button w3-padding <?php if(isset($venta)) echo "w3-dulcevanidad"; ?>">Venta</a>
      <a href="abono_peluqueria.php" class="w3-bar-item w3-button w3-padding <?php if(isset($abono_peluqueria)) echo "w3-dulcevanidad"; ?>">Abono Peluquer&iacute;a</a>
      <a href="abono_empleado.php" class="w3-bar-item w3-button w3-padding <?php if(isset($abono_empleado)) echo "w3-dulcevanidad"; ?>">Abono Empleado</a>
      <a href="ingreso.php" class="w3-bar-item w3-button w3-padding <?php if(isset($ingreso)) echo "w3-dulcevanidad"; ?>">Ingreso</a>
      <a href="cuadre_de_caja.php" class="w3-bar-item w3-button w3-padding <?php if(isset($cuadre)) echo "w3-dulcevanidad"; ?>">Cuadre de caja</a>
    <?php
      }
    ?>
  </div>
</nav>
<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
<script type="text/javascript">
    $(document).ready(function(){
      // Get the Sidebar
      

      // Get the DIV with overlay effect
      

      // Toggle between showing and hiding the sidebar, and add overlay effect
    });

    var mySidebar = document.getElementById("mySidebar");

    var overlayBg = document.getElementById("myOverlay");

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