<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'partidas';

  /*funciones*/
  require_once('includes/load.php');


  /*Derecho de pagina*/
  page_require_level(3);
  
  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'numero';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';
  $criterio = (isset($_GET['criterio'])) ? $_GET['criterio'] : '';


  /*Obtener datos*/
  $listaPartidas = find_all_partidas($columna,$ordenamiento,$criterio);


  if(isset($_POST['agregar_partida'])){

    $req_field = array('numero_partida','nombre_partida','montoInicial_partida','montoActual_partida');
    validate_fields($req_field);

    $numero_partida = remove_junk($db->escape($_POST['numero_partida']));
    $nombre_partida = remove_junk($db->escape($_POST['nombre_partida']));
    $montoInicial_partida = remove_junk($db->escape($_POST['montoInicial_partida']));
    $montoActual_partida = remove_junk($db->escape($_POST['montoActual_partida']));

   
    if(empty($errors)){
      $sql  = "INSERT INTO partidas (numero,nombre,montoInicial,montoActual)";
      $sql .= " VALUES ('{$numero_partida}','{$nombre_partida}',{$montoInicial_partida},{$montoActual_partida})";
      if($db->query($sql)){
        $session->msg("s", "Partida agregada correctamente.");
        redirect('partidas.php',false);
      } else {
        $session->msg("d", "Ha ocurrido un error al guardar la partida");
        redirect('partidas.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('partidas.php',false);
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
            <span>Agregar Partida</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="partidas.php">
            <div class="form-group">
                <label for="numero_partida" class="control-label">Número:</label>
                <input type="text" class="form-control" name="numero_partida" placeholder="Número de partida" required>
            </div>
            <div class="form-group">
                <label for="nombre_proveedor" class="control-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre_partida" placeholder="Nombre partida" required>
            </div>
            <div class="form-group">
                <label for="montoInicial_partida" class="control-label">Monto Inicial:</label>
                <input type="text" class="form-control" name="montoInicial_partida" id="montoInicial_partida" placeholder="Monto inicial de partida" value="0.00">
            </div>
            <div class="form-group">
                <label for="montoActual_partida" class="control-label">Monto Actual:</label>
                <input type="text" class="form-control" name="montoActual_partida" id="montoActual_partida" placeholder="Monto actual de partida" value="0.00">
            </div>
            <button type="submit" name="agregar_partida" class="btn btn-compras">Agregar</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-usd"></span>
            <span>Lista de partidas</span>
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
              <tr class="titulo_tabla">
                <th class="text-center titulo_tabla" style="width: 50px;">#</th>
                <th>
                  Número 
                  <a onclick="recargar('numero','ASC')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('numero','DESC')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
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
                  Monto Inicial
                  <a onclick="recargar('montoInicial','ASC')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('montoInicial','DESC')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="titulo_tabla">
                  Monto Actual
                  <a onclick="recargar('montoActual','ASC')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('montoActual','DESC')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="text-center" style="width: 100px;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($listaPartidas as $partida):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk($partida['numero']); ?></td>
                    <td><?php echo remove_junk($partida['nombre']); ?></td>
                    <td><?php echo remove_junk($partida['montoInicial']); ?></td>
                    <td><?php echo remove_junk($partida['montoActual']); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <?php
                          if( (int)$partida['id']==1 ){
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
                          <a href="editar_partida.php?id=<?php echo (int)$partida['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                            <span class="glyphicon glyphicon-edit"></span>
                          </a>
                          <a href="eliminar_partida.php?id=<?php echo (int)$partida['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
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
        Le informamos que esta partida no puede ser modificada ni eliminada
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
    var pagina ="partidas.php";
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