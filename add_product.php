<?php
  /*time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo pagina*/
  $page_title = 'Agregar producto';

  /*funciones*/
  require_once('includes/load.php');
  
  /*Derecho de pagina*/
  page_require_level(3);

  /*Obtener datos*/
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $partidas = find_all_partidas('numero','ASC','');
  $proveedores = find_all_proveedores('nombre','ASC','');
  $configuracion = find_all('configuracion');
  $tipoCambioDolar = $configuracion[0]['tipoCambioDolar'];



 if(isset($_POST['agregar_producto'])){
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

     $fecha    = make_date();
     $query  = "INSERT INTO productos (";
     $query .=" idCat, idPar, nombre,precioDolares,precioColones,imagen,fecha,descripcion,caracteristicas,justificacion";
     $query .=") VALUES (";
     $query .= "'{$p_categoriaProducto}', '{$p_partidaProducto}', '{$p_nombreProducto}', '{$p_precioDolares_producto}', '{$p_precioColones_producto}', '{$media_id}', '{$fecha}', '{$p_descripcionProducto}', '{$p_caracteristicasProductos}', '{$p_justificacionProducto}'";
     $query .=")";
     

     if($db->query($query)){
       $session->msg('s',"Producto agregado exitosamente. ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Ha ocurrido un error al agregar el producto.');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
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
          <span class="glyphicon glyphicon-plus"></span>
          <span>Agregar producto</span>
          <span>- Precio dólar: <?=$tipoCambioDolar?></span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
            <div class="form-group">
              <label for="nombre_producto" class="control-label">Nombre producto:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="nombre_producto" placeholder="Nombre producto"> 
              </div>
            </div>
            <div class="form-group">
              <label for="descripcion_producto" class="control-label">Descripción producto:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-align-justify"></i>
                </span>
                <textarea class="form-control" name="descripcion_producto" placeholder="Descripción producto"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="caracteristicas_producto" class="control-label">Caracteristicas producto (Separadas por "|"):</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-list"></i>
                </span>
                <textarea class="form-control" name="caracteristicas_producto" placeholder="Caracteristicas producto"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="justificacion_producto" class="control-label">Justificación producto:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-saved"></i>
                </span>
                <textarea class="form-control" name="justificacion_producto" placeholder="Justificación producto"></textarea>
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
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
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
                    <option value="">Selecciona una imagen</option>
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'] ?>">
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <label for="precioDolares_producto" class="control-label">Precio Dólares:</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="text" class="form-control" name="precioDolares_producto" id="precioDolares_producto" placeholder="Precio dólares" value="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="precioColones_producto" class="control-label">Precio Colones:</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      ₡
                    </span>
                    <input type="text" class="form-control" name="precioColones_producto" id="precioColones_producto" placeholder="Precio colones" value="0.00">
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
                      <option value="<?php echo (int)$partida['id'] ?>">
                        <?php echo $partida['numero']." | ".$partida['nombre'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="text-align: center;">
                <button type="submit" name="agregar_producto" class="btn btn-compras">Agregar producto</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
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