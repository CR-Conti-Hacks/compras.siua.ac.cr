<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Proveedores';

  /*funciones*/
  require_once('includes/load.php');


  /*Derecho de pagina*/
  page_require_level(3);
  
  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'nombre';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';
  $criterio = (isset($_GET['criterio'])) ? $_GET['criterio'] : '';

  /*Obtener datos*/
  $listaProveedores = find_all_proveedores($columna,$ordenamiento,$criterio);

  if(isset($_POST['agregar_proveedor'])){

    $req_field = array('nombre_proveedor');
    validate_fields($req_field);
    $nombre_proveedor = remove_junk($db->escape($_POST['nombre_proveedor']));
    $telefono_proveedor = remove_junk($db->escape($_POST['telefono_proveedor']));
    $correo_proveedor = remove_junk($db->escape($_POST['correo_proveedor']));
    if(empty($errors)){
      $sql  = "INSERT INTO proveedores (nombre,telefono,correo)";
      $sql .= " VALUES ('{$nombre_proveedor}','{$telefono_proveedor}','{$correo_proveedor}')";
      if($db->query($sql)){
        $session->msg("s", "Proveedor agregado correctamente.");
        redirect('proveedores.php',false);
      } else {
        $session->msg("d", "Ha ocurrido un error al guardar el proveedor");
        redirect('proveedores.php',false);
      }
    }else {
      $session->msg("d", $errors);
      redirect('proveedores.php',false);
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
          <span>Agregar Proveedor</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="proveedores.php">
          <div class="form-group">
            <label for="nombre_proveedor" class="control-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre_proveedor" placeholder="Nombre del proveedor" required>
          </div>
          <div class="form-group">
            <label for="telefono_proveedor" class="control-label">Teléfono:</label>
            <input type="text" class="form-control" name="telefono_proveedor" placeholder="Teléfono del proveedor">
          </div>
          <div class="form-group">
            <label for="correo_proveedor" class="control-label">Correo:</label>
            <input type="text" class="form-control" name="correo_proveedor" placeholder="Correo del proveedor">
          </div>
          <button type="submit" name="agregar_proveedor" class="btn btn-compras">Agregar</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-plane"></span>
          <span>Lista de proveedores</span>
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
                Nombre
                <a onclick="recargar('nombre','ASC')">
                  <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                </a>
                <a onclick="recargar('nombre','DESC')">
                  <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                </a>
              </th>
              <th class="titulo_tabla">
                Teléfono
                <a onclick="recargar('telefono','ASC')">
                  <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                </a>
                <a onclick="recargar('telefono','DESC')">
                  <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                </a>
              </th>
              <th class="titulo_tabla">
                Correo
                <a onclick="recargar('correo','ASC')">
                  <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                </a>
                <a onclick="recargar('correo','DESC')">
                  <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                </a>
              </th>
              <th class="text-center titulo_tabla" style="width: 100px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($listaProveedores as $proveedor):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk($proveedor['nombre']); ?></td>
                <td><?php echo remove_junk($proveedor['telefono']); ?></td>
                <td><?php echo remove_junk($proveedor['correo']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <?php
                      if( (int)$proveedor['id']==1 ){
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
                      <a href="editar_proveedor.php?id=<?php echo (int)$proveedor['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="eliminar_proveedor.php?id=<?php echo (int)$proveedor['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
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
        Le informamos que este proveedor no puede modificar ni eliminar
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
    var pagina ="proveedores.php";
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