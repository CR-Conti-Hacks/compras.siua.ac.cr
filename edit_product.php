<?php
  
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Editar producto';

  /*funciones*/
  require_once('includes/load.php');
  
  /*Derecho de pagina*/
  page_require_level(3);

  /*Obtener datos*/
  $all_categories = find_all('categories');
  $partidas = find_all_partidas('numero','ASC','');
  $proveedores = find_all_proveedores('nombre','ASC','');
  $configuracion = find_all('configuracion');
  $tipoCambioDolar = $configuracion[0]['tipoCambioDolar'];

  $all_photo = find_all('media');

  $producto = find_by_id('productos',(int)$_GET['id']);



  
  if(!$producto){
    $session->msg("d","No se encontro un producto con este ID.");
    redirect('product.php');
  }

 if(isset($_POST['editar_producto'])){
    $req_fields = array('nombre_producto','descripcion_producto','caracteristicas_producto','categoria_producto','precioDolares_producto', 'precioColones_producto' );
    validate_fields($req_fields);

   if(empty($errors)){
       /*Obtener datos*/
      $p_nombreProducto            = remove_junk($db->escape($_POST['nombre_producto']));
      $p_descripcionProducto       = remove_junk($db->escape($_POST['descripcion_producto']));
      $p_caracteristicasProductos  = remove_junk($db->escape($_POST['caracteristicas_producto']));
      $p_justificacionProducto     = remove_junk($db->escape($_POST['justificacion_producto']));
      $p_categoriaProducto         = remove_junk($db->escape($_POST['categoria_producto']));
      $p_precioDolares_producto    = remove_junk($db->escape($_POST['precioDolares_producto']));
      $p_precioColones_producto    = remove_junk($db->escape($_POST['precioColones_producto']));
      $p_partidaProducto           = remove_junk($db->escape($_POST['partida_producto']));

       /*Imagen*/
      if (is_null($_POST['imagen_producto']) || $_POST['imagen_producto'] === "") {
        $media_id = '0';
      } else {
        $media_id = remove_junk($db->escape($_POST['imagen_producto']));
      }


      /******************************** falta ********************/
      $query   = "UPDATE productos SET";
      $query  .= " idCat= {$p_categoriaProducto}, idPar={$p_partidaProducto}, nombre='{$p_nombreProducto}', precioDolares={$p_precioDolares_producto},";
      $query  .= " precioColones={$p_precioColones_producto}, imagen={$media_id}, descripcion='{$p_descripcionProducto}', caracteristicas='{$p_caracteristicasProductos}',";
      $query  .= " justificacion='{$p_justificacionProducto}'";
      $query  .= " WHERE id ='{$producto['id']}'";



      //error_log($query);
      $result = $db->query($query);
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Producto actualizado correctamente. ");
        redirect('product.php', false);
      } else {
        $session->msg('d',' Ha ocurrido un error al agregar el producto o no se modificó ningún valor');
        redirect('edit_product.php?id='.$product['id'], false);
      }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
   }

 }

?>
<?php include_once('layouts/header.php'); ?>
<input type="hidden" name="tipoCambioDolar" id="tipoCambioDolar" value="<?=$tipoCambioDolar?>">

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-1"></div>
  <div class="col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-edit"></span>
          <span>Editar producto</span>
          <span>- Precio dólar: <?=$tipoCambioDolar?></span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
           <form method="post" action="edit_product.php?id=<?php echo (int)$producto['id'] ?>">
              <div class="form-group">
                <label for="nombre_producto" class="control-label">Nombre producto:</label>
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="nombre_producto" value="<?php echo html_entity_decode(remove_junk($producto['nombre']));?>"  placeholder="Nombre producto">
                </div>
              </div>
              <div class="form-group">
                <label for="descripcion_producto" class="control-label">Descripción producto:</label>
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-align-justify"></i>
                  </span>
                  <textarea class="form-control" name="descripcion_producto"  placeholder="Descripción producto"><?php echo html_entity_decode(remove_junk($producto['descripcion']));?></textarea>
               </div>
              </div>
              <div class="form-group">
                <label for="caracteristicas_producto" class="control-label">Caracteristicas producto (Separadas por "|"):</label>
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-list"></i>
                  </span>
                  <textarea class="form-control" name="caracteristicas_producto" placeholder="Caracteristicas producto"><?= html_entity_decode(remove_junk($producto['caracteristicas']))?></textarea>
               </div>
              </div>
              <div class="form-group">
                <label for="justificacion_producto" class="control-label">Justificación producto:</label>
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-saved"></i>
                  </span>
                  <textarea class="form-control" name="justificacion_producto" placeholder="Justificación producto"><?php echo html_entity_decode(remove_junk($producto['justificacion']));?></textarea>
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="categoria_producto" class="control-label">Categoría producto:</label>
                    <select 
                      class="form-control selectpicker show-tick show-menu-arrow" 
                      data-live-search="true"
                      data-style="btn-primary"
                      name="categoria_producto"
                    >
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id']; ?>" <?php if($producto['idCat'] === $cat['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>
                  <div class="col-md-6">
                    <label for="imagen_producto" class="control-label">Imagen producto:</label>
                    <select 
                      class="form-control selectpicker show-tick show-menu-arrow" 
                      data-live-search="true"
                      data-style="btn-primary"

                      name="imagen_producto"
                    >
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'];?>" <?php if($producto['imagen'] === $photo['id']): echo "selected"; endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="precioDolares_producto" class="control-label">Precio Dólares:</label>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="glyphicon glyphicon-usd"></i>
                        </span>
                        <input type="text" class="form-control" name="precioDolares_producto" id="precioDolares_producto" placeholder="Precio dólares" value="<?php echo remove_junk($producto['precioDolares']);?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="precioColones_producto" class="control-label">Precio Colones:</label>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="glyphicon glyphicon-usd"></i>
                        </span>
                        <input type="text" class="form-control" name="precioColones_producto" id="precioColones_producto" placeholder="Precio colones" value="<?php echo remove_junk($producto['precioColones']);?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="partida_producto" class="control-label">Partida Producto:</label>
                    <select 
                      class="form-control selectpicker show-tick show-menu-arrow" 
                      data-live-search="true"
                      data-style="btn-primary"

                      name="partida_producto"
                    >
                      <?php  foreach ($partidas as $partida): ?>
                        <option value="<?php echo (int)$partida['id'] ?>"  <?php if($producto['idPar'] === $partida['id']): echo "selected"; endif; ?>>
                          <?php echo $partida['numero']." | ".$partida['nombre'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
               </div>
              </div>
              <button type="submit" name="editar_producto" class="btn btn-compras">Actualizar</button>
          </form>
         </div>
        </div>
      </div><!-- col 8-->
      <div class="col-md-1"></div>
  </div>

<?php include_once('layouts/footer.php'); ?>
<script>

  window.onload = function() {

    window.precioDolares_producto = IMask(document.querySelector('#precioDolares_producto'), {
      mask: Number,
      scale: 2,
      signed: false,
      thousandsSeparator: '',
      padFractionalZeros: true,
      normalizeZeros: true,
      radix: '.',
      mapToRadix: ['.']
    });
    window.precioColones_producto = IMask(document.querySelector('#precioColones_producto'), {
      mask: Number,
      scale: 2,
      signed: false,
      thousandsSeparator: '',
      padFractionalZeros: true,
      normalizeZeros: true,
      radix: '.',
      mapToRadix: ['.']
    });

    //$("#precioDolares_producto").inputmask('Regex', {regex: "^[0-9]{1,6}(\\.\\d{1,2})?$"});

    /*blur dolares*/
    document.querySelector("#precioDolares_producto").addEventListener("blur", function( event ) {
        /*validar si esta vacio*/
        if(document.querySelector("#precioDolares_producto").value == "" || document.querySelector("#precioDolares_producto").value == "0"){
          
          document.querySelector("#precioColones_producto").value = "0.00";  
          document.querySelector("#precioDolares_producto").value = "0.00";  
          window.precioColones_producto.updateValue();
          window.precioDolares_producto.updateValue();
        }else{
          let resultado = parseFloat(document.querySelector("#precioDolares_producto").value) * parseFloat(document.querySelector("#tipoCambioDolar").value);
          document.querySelector("#precioColones_producto").value = resultado.toFixed(2);

        } 

      }, true);

    /* blur colones */
    document.querySelector("#precioColones_producto").addEventListener("blur", function( event ) {
        /*validar si esta vacio*/
        if(document.querySelector("#precioColones_producto").value == "" || document.querySelector("#precioColones_producto").value == "0"){
          
          document.querySelector("#precioColones_producto").value = "0.00";  
          document.querySelector("#precioDolares_producto").value = "0.00";  
          window.precioColones_producto.updateValue();
          window.precioDolares_producto.updateValue();
        }else{
          let resultado = parseFloat(document.querySelector("#precioColones_producto").value) / parseFloat(document.querySelector("#tipoCambioDolar").value);
          document.querySelector("#precioDolares_producto").value = resultado.toFixed(2); 
        }

    }, true);

  
  };

</script>