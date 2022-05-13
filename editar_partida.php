<?php
  /*time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo pagina*/
  $page_title = 'Editar partida';

  /*funciones*/
  require_once('includes/load.php');
  
  /*Derecho de pagina*/
  page_require_level(3);

  /*Obtener datos*/
  $partida = find_by_id('partidas',(int)$_GET['id']);
  if(!$partida){
    $session->msg("d","No existe una partida con este id.");
    redirect('partidas.php');
  }

if(isset($_POST['editar_partida'])){
  $req_field = array('numero_partida','nombre_partida','montoInicial_partida','montoActual_partida');
  validate_fields($req_field);
  $numero_partida = remove_junk($db->escape($_POST['numero_partida']));
   $nombre_partida = remove_junk($db->escape($_POST['nombre_partida']));
   $montoInicial_partida = remove_junk($db->escape($_POST['montoInicial_partida']));
   $montoActual_partida = remove_junk($db->escape($_POST['montoActual_partida']));

  if(empty($errors)){
     $sql = "UPDATE partidas SET numero='{$numero_partida}',nombre='{$nombre_partida}',montoInicial={$montoInicial_partida},montoActual={$montoActual_partida}";
     $sql .= " WHERE id='{$partida['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Partida modificada correctamente.");
       redirect('partidas.php',false);
     } else {
       $session->msg("d", "Ha ocurrido un error al editar la partida o no se han modificado datos");
       redirect('partidas.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('partidas.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-4"></div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Editar Partida: <?php echo remove_junk($partida['nombre']);?></span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="editar_partida.php?id=<?php echo (int)$partida['id'];?>">
          <div class="form-group">
            <label for="numero_partida" class="control-label">Número:</label>
            <input type="text" class="form-control" name="numero_partida" value="<?php echo remove_junk($partida['numero']);?>">
          </div>
          <div class="form-group">
            <label for="nombre_partida" class="control-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre_partida" value="<?php echo remove_junk($partida['nombre']);?>">
          </div>
          <div class="form-group">
            <label for="montoInicial_partida" class="control-label">Monto Inicial:</label>
            <input type="text" class="form-control" name="montoInicial_partida" id="montoInicial_partida" value="<?php echo remove_junk($partida['montoInicial']);?>">
          </div>
          <div class="form-group">
            <label for="montoActual_partida" class="control-label">Monto Actual:</label>
            <input type="text" class="form-control" name="montoActual_partida" id="montoActual_partida" value="<?php echo remove_junk($partida['montoActual']);?>">
          </div>
          <button type="submit" name="editar_partida" class="btn btn-primary">Actualizar</button>
          <button type="button"  class="btn btn-compras" onclick="location.href='proveedores.php'">Regresar</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-4"></div>
</div>



<?php include_once('layouts/footer.php'); ?>
<script>
  window.onload = function() {
    window.montoInicial_partida = IMask(document.querySelector('#montoInicial_partida'), {
      mask: Number,
      scale: 2,
      signed: false,
      thousandsSeparator: '',
      padFractionalZeros: true,
      normalizeZeros: true,
      radix: '.',
      mapToRadix: ['.']
    });
    window.montoActual_partida = IMask(document.querySelector('#montoActual_partida'), {
      mask: Number,
      scale: 2,
      signed: false,
      thousandsSeparator: '',
      padFractionalZeros: true,
      normalizeZeros: true,
      radix: '.',
      mapToRadix: ['.']
    });

  };

</script>