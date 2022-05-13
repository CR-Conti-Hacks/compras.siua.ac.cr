<?php
	/*Time zone*/
  	date_default_timezone_set('America/Costa_Rica');

  	/*funciones*/
  	require_once('includes/load.php');

  	/*Derecho de pagina*/
  	page_require_level(3);


  	/*Recibir parametros*/
	$IdProv = (int)$_REQUEST['IdProv'];
	$IdProd = (int)$_REQUEST['IdProd'];
	$marcado = (int)$_REQUEST['marcado'];
	ob_clean();
	echo actualizaProveedor_x_producto($marcado,$IdProv,$IdProd);
?>