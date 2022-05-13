<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  $partida = find_by_id('partidas',(int)$_GET['id']);
  if(!$partida){
    $session->msg("d","No existe una partida con este ID.");
    redirect('partidas.php');
  }
?>
<?php

  /*Cambiar la partida a la default*/
  if(actualizarDefectoPartidaProducto((int)$partida['id'])){
    $delete_id = delete_by_id('partidas',(int)$partida['id']);
    if($delete_id){
        $session->msg("s","Partida eliminada correctamente");
        redirect('partidas.php');
    } else {
        $session->msg("d","Ha ocurrido un error al eliminar la partida");
        redirect('partidas.php');
    }

  }else{
    $session->msg("d","Ha ocurrido al establecer la partida defecto");
    redirect('partidas.php');
  }


?>
