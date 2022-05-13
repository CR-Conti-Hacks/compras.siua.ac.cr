<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Editar proveedor';

  /*funciones*/
  require_once('includes/load.php');


  /*Derecho de pagina*/
  page_require_level(3);

  /*Obtener datos*/
  $proveedor = find_by_id('proveedores',(int)$_GET['id']);
  if(!$proveedor){
    $session->msg("d","No existe un proveedor con este id.");
    redirect('proveedores.php');
  }

if(isset($_POST['editar_proveedor'])){
  $req_field = array('nombre_proveedor');
  validate_fields($req_field);
  $nombre_proveedor = remove_junk($db->escape($_POST['nombre_proveedor']));
  $telefono_proveedor = remove_junk($db->escape($_POST['telefono_proveedor']));
  $correo_proveedor = remove_junk($db->escape($_POST['correo_proveedor']));

  if(empty($errors)){
     $sql = "UPDATE proveedores SET nombre='{$nombre_proveedor}',telefono='{$telefono_proveedor}',correo='{$correo_proveedor}'";
     $sql .= " WHERE id='{$proveedor['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Proveedor modificado correctamente.");
       redirect('proveedores.php',false);
     } else {
       $session->msg("d", "Ha ocurrido un error al editar el proveedor o no se han modificado ningún valor");
       redirect('proveedores.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('proveedores.php',false);
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
          <span class="glyphicon glyphicon-edit"></span>
          <span>Editar Proveedor: <?php echo remove_junk($proveedor['nombre']);?></span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="editar_proveedor.php?id=<?php echo (int)$proveedor['id'];?>">
          <div class="form-group">
            <label for="nombre_proveedor" class="control-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre_proveedor" value="<?php echo remove_junk($proveedor['nombre']);?>">
          </div>
          <div class="form-group">
            <label for="telefono_proveedor" class="control-label">Teléfono:</label>
            <input type="text" class="form-control" name="telefono_proveedor" value="<?php echo remove_junk($proveedor['telefono']);?>">
          </div>
          <div class="form-group">
            <label for="correo_proveedor" class="control-label">Correo:</label>
            <input type="text" class="form-control" name="correo_proveedor" value="<?php echo remove_junk($proveedor['correo']);?>">
          </div>
          <button type="submit" name="editar_proveedor" class="btn btn-compras">Actualizar</button>
          <button type="button"  class="btn btn-compras" onclick="location.href='proveedores.php'">Regresar</button>
        </form>
      </div>
    </div>
    <div class="col-md-4"></div>
  </div>
</div>



<?php include_once('layouts/footer.php'); ?>
