<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id('productos',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","ID vacío");
    redirect('product.php');
  }
?>
<?php

  /*Eliminar proveedores de productos*/
  $proveedores = find_all_nombre_proveedores_x_producto((int)$product['id']);
  error_log(print_r($proveedores,TRUE));

  for($i=0; $i<count($proveedores);$i++){
    eliminarProveedorXProducto((int)$product['id'],$proveedores[$i]["IdProv"]);
  }

  $delete_id = delete_by_id('productos',(int)$product['id']);
  if($delete_id){
      $session->msg("s","Producto eliminado");
      redirect('product.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('product.php');
  }
?>
