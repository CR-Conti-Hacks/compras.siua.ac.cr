<?php
   /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Agregar Producto Compra';
  
  /*funciones*/
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(3);

  /*obtener tipo de cambio*/
  $configuracion = find_all('configuracion');
  $tipoCambioDolar = $configuracion[0]['tipoCambioDolar'];

  


  if(isset($_POST['agregarCompra'])){
    $req_fields = array('idProducto','justificacionProducto','precioColones','precioDolares','cantidadSolicitadaProducto','totalColones','totalDolares' );
    validate_fields($req_fields);
    if(empty($errors)){
      $usuarioActual = current_user();


      $idProducto                   = $db->escape((int)$_POST['idProducto']);
      $justificacionProducto        = $db->escape($_POST['justificacionProducto']);
      $precioColones                = $db->escape($_POST['precioColones']);
      $precioDolares                = $db->escape($_POST['precioDolares']);
      $cantidadSolicitadaProducto   = $db->escape((int)$_POST['cantidadSolicitadaProducto']);
      $totalColones                 = $db->escape($_POST['totalColones']);
      $totalDolares                 = $db->escape($_POST['totalDolares']);
      $fecha                        = date("Y-m-d");
      $idUsuario                    = $usuarioActual['id'];
      $anno                         = date("Y");
      $prioridad                    = $db->escape((int)$_POST['prioridad']);


      /*Determinar si ya existe el producto para el usuario */

      $compra = buscarCompraXProdUsuAnnno($idProducto,$idUsuario,$anno);
      

      /*Si ya existe el producto actualice*/
      if(count($compra)>=1){
        $sql = "UPDATE compras SET precioColones='{$precioColones}', precioDolares='{$precioDolares}', totalColones='{$totalColones}',totalDolares='{$totalDolares}'," ;
        $sql .= "fecha='${fecha}', justificacion='{$justificacionProducto}',tipoCambioDolar='{$tipoCambioDolar}',cantidadProducto='{$cantidadSolicitadaProducto}', prioridad='{$prioridad}' ";
        $sql .= " WHERE id = ".$compra[0]['id'];
        if($db->query($sql)){
          $session->msg('s',"Producto se ha actualizado correctamente (El producto ya estaba agregado se actualizó las cantidades)");
            redirect('agregar_compra.php', false);
        } else {
          $session->msg('d','Ha ocurrido un error al agregar la compra.');
            redirect('agregar_compra.php', false);
        }

      /*No existe el producto*/
      }else{

        $sql = "INSERT INTO compras (idProd,idUsu,precioColones,precioDolares,totalColones,totalDolares,fecha,justificacion,tipoCambioDolar,cantidadProducto,estado, prioridad) VALUES (";
        $sql .= "'{$idProducto}','{$idUsuario}','{$precioColones}','{$precioDolares}','{$totalColones}','{$totalDolares}','{$fecha}','{$justificacionProducto}','{$tipoCambioDolar}','{$cantidadSolicitadaProducto}','1','{$prioridad}')";
        if($db->query($sql)){
          $session->msg('s',"Producto agregado correctamente");
            redirect('agregar_compra.php', false);
        } else {
          $session->msg('d','Ha ocurrido un error al agregar la compra.');
            redirect('agregar_compra.php', false);
        }
        
      }

    }else {
      $session->msg("d", $errors);
      redirect('agregar_compra.php',false);
    }
  }


  //Ugit si se envia el nombre
  if(isset($_GET['nombre'])){
    $nombreProduto = $_GET['nombre'];
  }else{
    $nombreProduto = "";
  }

?>
<?php include_once('layouts/header.php'); ?>
<input type="hidden" name="tipoCambioDolar" id="tipoCambioDolar" value="<?=$tipoCambioDolar?>">
<div class="row">
  <div class="col-md-10">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="formBusqueda">
        <div class="form-group">
          <div class="input-group">
            <input type="text" id="busquedaProducto" class="form-control" name="title" value="<?=$nombreProduto?>" placeholder="Buscar por el nombre del producto">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-compras">Agregar</button>
            </span>
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
  <div class="col-md-2 pull-right">
    <button type="button" class="btn btn-compras" onclick="location.href='mis_compras.php'">Ir a Mis Compras</button>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Agregar Productos</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="agregar_compra.php">
         <table class="table table-bordered">
           <thead>
            <th class="titulo_tabla text-center">Detalle</th>
            <th class="titulo_tabla">Imágen</th>
            <th class="titulo_tabla">Nombre </th>
            <th class="titulo_tabla">Descripción</th>
            <th class="titulo_tabla">Justificación</th>
            <th class="titulo_tabla">Prioridad</th>
            <th class="titulo_tabla">Precio Colones</th>
            <th class="titulo_tabla">Precio Dólares</th>
            <th class="titulo_tabla">Cant.Prod. Solicitados</th>
            <th class="titulo_tabla">Total ₡</th>
            <th class="titulo_tabla">Total $</th>
            <th class="titulo_tabla">Agregar</th>
           </thead>
             <tbody  id="informacionProducto"> </tbody>
         </table>
       </form>
      </div>
    </div>
  </div>

</div>

<?php include_once('layouts/footer.php'); ?>

<script>
function muestraFila(fila,fecha){
  /*Esta cerrado*/
  if(document.querySelector("#"+fecha).className == 'glyphicon glyphicon-chevron-down'){
    document.querySelector("#"+fecha).className = 'glyphicon glyphicon-chevron-up';
    document.querySelector("#"+fila).removeAttribute("style");  
  }else if(document.querySelector("#"+fecha).className == 'glyphicon glyphicon-chevron-up'){
    document.querySelector("#"+fecha).className = 'glyphicon glyphicon-chevron-down';
    document.querySelector("#"+fila).setAttribute("style","display:none");  
  }

}
</script>