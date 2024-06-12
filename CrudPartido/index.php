<?php
    require_once("vista/layouts/header.php");
?>


<?php
	
	require_once("config.php");
	require_once("controlador/index.php");
	if(isset($_GET['m'])):    
    	if(method_exists("modeloController",$_GET['m'])):
        	modeloController::{$_GET['m']}();
    	endif;
	else:
    	
	endif;
?>
<!-- Agregar botón para ver la tabla -->
<a class="btn" href="index.php?m=index&table=Equipo">Ver tabla PARTIDO</a>

<?php
    require_once("vista/layouts/footer.php");
?>
