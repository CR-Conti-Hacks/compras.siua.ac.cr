<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*funciones*/
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(3);

  /*Obtener datos*/
  $proveedor = find_by_id('proveedores',(int)$_GET['id']);
  if(!$proveedor){
    $session->msg("d","No existe proveedor con este ID.");
    redirect('proveedores.php');
  }
?>
<?php

  
  /*Cambiar la partida a la default*/
  if(eliminarProveedoresXProductoXidProv((int)$_GET['id'])){
    $delete_id = delete_by_id('proveedores',(int)$proveedor['id']);
    if($delete_id){
        $session->msg("s","Proveedor eliminado correctamente");
        redirect('proveedores.php');
    } else {
        $session->msg("d","Ha ocurrido un error al eliminar el proveedor");
        redirect('proveedores.php');
    }

  }else{
    $session->msg("d","Ha ocurrido al eliminar los proveedores por producto");
    redirect('proveedores.php');
  }


  
?>
