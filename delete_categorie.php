<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*funciones*/
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(1);

  /*obtener datos*/
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","ID de la categoría falta.");
    redirect('categorie.php');
  }
?>
<?php
  $delete_id = delete_by_id('categories',(int)$categorie['id']);
  if($delete_id){
      $session->msg("s","Categoría eliminada");
      redirect('categorie.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('categorie.php');
  }
?>
