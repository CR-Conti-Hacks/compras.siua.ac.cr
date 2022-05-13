<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Lista de categorías';

  /*funciones*/
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(3);

  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'name';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';
  $criterio = (isset($_GET['criterio'])) ? $_GET['criterio'] : '';

  /*Obtener datos*/
  $all_categories = find_all_categorias($columna,$ordenamiento,$criterio);

 if(isset($_POST['add_cat'])){
   $req_field = array('categorie-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['categorie-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO categories (name)";
      $sql .= " VALUES ('{$cat_name}')";
      if($db->query($sql)){
        $session->msg("s", "Categoría agregada exitosamente.");
        redirect('categorie.php',false);
      } else {
        $session->msg("d", "Lo siento, registro falló");
        redirect('categorie.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('categorie.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>
<input type="hidden" id="columna" name="columna" value="<?=$columna?>">
<input type="hidden" id="ordenamiento" name="ordenamiento" value="<?=$ordenamiento?>">
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-plus"></span>
          <span>Agregar categoría</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="categorie.php">
          <div class="form-group">
            <input type="text" class="form-control" name="categorie-name" placeholder="Nombre de la categoría" required>
          </div>
          <button type="submit" name="add_cat" class="btn btn-compras">Agregar categoría</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-indent-left"></span>
          <span>Lista de categorías</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar..." value="<?=$criterio?>" id="criterio" name="criterio">
            <span class="input-group-btn">
              <button class="btn btn-compras" type="button" id="btnBuscar" name="btnBuscar">Buscar</button>
            </span>
        </div>
        <br />
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th class="text-center titulo_tabla" style="width: 50px;">#</th>
              <th class="titulo_tabla">
                Categorías
                <a onclick="recargar('name','ASC')">
                  <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                </a>
                <a onclick="recargar('name','DESC')">
                  <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                </a>
              </th>
              <th class="text-center titulo_tabla" style="width: 100px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_categories as $cat):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <?php
                      if( (int)$cat['id']==1 ){
                    ?>
                      <a data-toggle="modal" data-target="#ventanaAdvertenciaNoModEli" class="btn btn-xs btn-info" data-toggle="tooltip" title="Editar">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a data-toggle="modal" data-target="#ventanaAdvertenciaNoModEli"  class="btn btn-xs btn-info" data-toggle="tooltip" title="Eliminar">
                        <span class="glyphicon glyphicon-trash"></span>
                      </a>

                    <?php
                      }else{
                    ?>
                      <a href="edit_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="delete_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
                        <span class="glyphicon glyphicon-trash"></span>
                      </a>

                    <?php
                      }
                    ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- *************************************************************************************************************************************************-->
<!-- **************************************************             MODAL        *********************************************************************-->
<!-- *************************************************************************************************************************************************-->
<div class="modal fade" id="ventanaAdvertenciaNoModEli" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Advertencia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Le informamos que esta categoría no puede ser modificada ni eliminada
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- *************************************************************************************************************************************************-->
<!-- **************************************************             MODAL        *********************************************************************-->
<!-- *************************************************************************************************************************************************-->



<?php include_once('layouts/footer.php'); ?>
<script>
  window.onload = function() {
    document.querySelector("#btnBuscar").addEventListener('click', function(){
      recargar("","");
    });
    document.querySelector('#criterio').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          recargar("","");
        }
    });
    document.querySelector("#criterio").focus();
  };

  function recargar(columna, ordenamiento){
    
    /*pagina*/
    var pagina ="categorie.php";
    /*Si no hay columna*/
    if(columna=="" ){
      columna = document.querySelector("#columna").value;
    }
    /*Si no hay ordenamiento*/
    if(ordenamiento=="" ){
      ordenamiento = document.querySelector("#ordenamiento").value;
    }

    /*Obtener el criterio de busqueda*/
    var criterio = document.querySelector("#criterio").value;

    location.href= pagina+"?"+"columna="+columna+"&ordenamiento="+ordenamiento+"&criterio="+criterio;
    
  }


</script>