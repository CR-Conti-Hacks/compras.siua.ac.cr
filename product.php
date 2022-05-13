<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Lista de productos';

  /*funciones*/
  require_once('includes/load.php');
  
  /*Derecho de pagina*/
  page_require_level(3);

  /*****************************************************/
  /***************** PAGINACION ************************/
  /*****************************************************/
  /*Reiniciamos la variable pagina*/
  $pagina = false;

  /*preguntamos si viene la variable pagina le asigamos este valor*/
  if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
  /*Iniciamos los valores iniciales*/
  }

  if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
  }else {
    $inicio = ($pagina - 1) * REGISTROS_X_PAGINA;
  }

  /*****************************************************/
  /***************** PAGINACION ************************/
  /*****************************************************/

  //Recibir parametros
  if(isset($_GET['IdCategoria'])){
    $IdCategoria = $_GET['IdCategoria'];
  }else{
    $IdCategoria = 0;
  }

  if(isset($_GET['IdPartida'])){
    $IdPartida = $_GET['IdPartida'];
  }else{
    $IdPartida = 0;
  }

  if(isset($_GET['criterio'])){
    $criterio = $_GET['criterio'];
  }else{
    $criterio = "";
  }

  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'nombre';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';


  /*Obtener catalogos*/
  $all_categories = find_all('categories');
  $all_partidas = find_all('partidas');
 


  /*obtener lso produtos*/
  $productos = join_product_table_busqueda($IdCategoria,$criterio,$inicio,REGISTROS_X_PAGINA,$columna,$ordenamiento,$IdPartida);

 
  /*obtener cantidad de prodcutos que cumplan con el crierio*/
  $cantidadProductos = count(obtenerCantidadProductosXCaterogiaYCriterio($IdCategoria,$criterio,$IdPartida));
  //calculo el total de paginas
  $total_paginas = ceil($cantidadProductos / REGISTROS_X_PAGINA);

?>
<?php include_once('layouts/header.php'); ?>
<input type="hidden" id="columna" name="columna" value="<?=$columna?>">
<input type="hidden" id="ordenamiento" name="ordenamiento" value="<?=$ordenamiento?>">
<input type="hidden" id="inicio" name="inicio" value="<?=$inicio?>">
<input type="hidden" id="pagina" name="pagina" value="<?=$pagina?>">

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">



          <!-- *********************************************************************************************************-->
          <!-- **********************************       Buscador   *****************************************************-->
          <!-- *********************************************************************************************************-->
          <div class="row">
            <div class="col col-xs-12">
              <div class="panel panel-default">
                <div class="panel-heading clearfix">
                  <strong>
                    <span class="glyphicon glyphicon-search"></span>
                    <span>Buscar Producto</span>
                  </strong>
                    <a href="add_product.php" class="btn btn-compras pull-right btn-sm"> Agregar Producto</a>
                </div>
                <div class="panel-body">
                  <div class="row">
                    
                    <!-- ***************************************************-->
                    <!-- ***************   Categoria    ********************-->
                    <!-- ***************************************************-->
                    <div class="col col-xs-12 col-lg-6">
                      <div class="form-group">
                        <label for="comboCategorias">Categoría:</label>
                        <select 
                          class="selectpicker show-tick show-menu-arrow form-control" 
                          data-live-search="true" 
                          title="[SELECCIONE]" 
                          id="comboCategorias" 
                          data-style="btn-primary"
                          data-width="fit"
                          >
                          <option value="0" 
                          <?php 
                             if($IdCategoria == 0){
                                echo "selected";
                              }
                          ?> >[Todas]</option>
                          <?php  foreach ($all_categories as $cat): ?>
                            <option data-tokens="<?php echo $cat['name'] ?>" value="<?=(int)$cat['id']?>" 
                            <?php
                              if($IdCategoria == $cat['id']){
                                echo "selected";
                              }
                            ?>
                    
                              ><?php echo $cat['name'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="comboPartidas">Partida: </label>
                        <select 
                          class="selectpicker show-tick show-menu-arrow form-control" 
                          data-live-search="true" 
                          title="[SELECCIONE]" 
                          id="comboPartidas" 
                          data-style="btn-primary"
                          data-width="fit"
                          >
                          <option value="0" 
                          <?php 
                             if($IdPartida == 0){
                                echo "selected";
                              }
                          ?> >[Todas]</option>
                          <?php  foreach ($all_partidas as $partida): ?>
                            <option data-tokens="<?php echo $partida['numero'] ?>" value="<?=(int)$partida['id']?>" 
                            <?php
                              if($IdPartida == $partida['id']){
                                echo "selected";
                              }
                            ?>
                    
                              ><?php echo $partida['numero']." (".$partida['nombre'].")" ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <!-- ***************************************************-->
                    <!-- ***************************************************-->
                    <!-- ***************************************************-->


                    <!-- ***************************************************-->
                    <!-- ***************   Categoria    ********************-->
                    <!-- ***************************************************-->
                    <div class="col col-xs-12 col-lg-6">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Digite un criterio</label>
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="Buscar producto..." id="criterio" value="<?=$criterio?>">
                          <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="btnBuscar">Buscar!</button>
                          </span>
                        </div><!-- /input-group -->
                      </div>
                    </div>
                    <!-- ***************************************************-->
                    <!-- ***************************************************-->
                    <!-- ***************************************************-->



                  </div><!--row campos busqueda-->

                  


                </div><!-- panel body buscar-->
              </div><!-- panel buscar-->
            </div><!-- col buscar-->
          </div> <!-- fila buscar-->


          <br />
          <span style="display: block; text-align:center;">Total de productos: <?=$cantidadProductos?></span>
          <br />
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center titulo_tabla">Detalle</th>
                <th class="text-center titulo_tabla">#</th>
                <th class="text-center titulo_tabla" > Imagen</th>
                <th class="text-center titulo_tabla" style="min-width: 120px;"> 
                  Nombre 
                  <a onclick="recargar('nombre','ASC','')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('nombre','DESC','')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="text-center titulo_tabla" style="min-width: 120px;"> 
                  Descripción 
                  <a onclick="recargar('descripcion','ASC','')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('descripcion','DESC','')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="text-center titulo_tabla" > Justificación </th>
                <th class="text-center titulo_tabla" style="min-width: 120px;"> 
                  Categoría 
                  <a onclick="recargar('categoria','ASC','')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('categoria','DESC','')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="text-center titulo_tabla" style="min-width: 150px;"> 
                  Proveedores
                </th>
                <th class="text-center titulo_tabla" style="min-width: 70px;" > 
                  $
                  <a onclick="recargar('precioDolares','ASC','')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('precioDolares','DESC','')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="text-center titulo_tabla" style="min-width: 70px;"> 
                  ₡ 
                  <a onclick="recargar('precioColones','ASC','')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('precioColones','DESC','')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="text-center titulo_tabla" style="min-width: 120px;"> 
                  Partida 
                  <a onclick="recargar('partida','ASC','')">
                    <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                  </a>
                  <a onclick="recargar('partida','DESC','')">
                    <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                  </a>
                </th>
                <th class="text-center titulo_tabla" > Acciones </th>
              </tr>
            </thead>
            <tbody>
              <?php 
              if(count($productos) == 0){
              ?>
                <tr>
                  <td colspan="12" style="text-align: center;">
                    No existen productos con estos criterios
                  </td>
                </tr>

              <?php
              }else{
                foreach ($productos as $product):

                  ?>

                <tr>
                  <td style="text-align:center;">
                    <a onclick="muestraFila('fila<?=$product['id']?>','fecha<?=$product['id']?>');">
                      <span class="glyphicon glyphicon-chevron-down" aria-hidden="true" id="fecha<?=$product['id']?>"></span>
                    </a>
                  </td>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td>
                    <?php if($product['image'] === '0'): ?>
                      <a href="uploads/products/no_image.jpg" data-lightbox="No imágen Disponible" data-title="No imágen Disponible">
                        <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="" >
                      </a>
                    <?php else: ?>
                      <a href="uploads/products/<?php echo $product['image'];?>" data-lightbox="<?php echo $product['image'];?>" data-title="<?php echo $product['image'];?>">
                        <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                      </a>
                  <?php endif; ?>
                  </td>
                  <td> <?php echo html_entity_decode(remove_junk($product['nombre'])); ?></td>
                  <td> <?php echo html_entity_decode(remove_junk($product['descripcion'])); ?></td>
                 
                  <td> <?php echo html_entity_decode(remove_junk($product['justificacion'])); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                  <td>
                    <?php
                      /*Obtener todos los proveedores del producto*/
                      $listaProveedoresXProductos = find_all_nombre_proveedores_x_producto($product['id']);
                      $count = 1;
                      if(count($listaProveedoresXProductos) >0){
                        foreach ($listaProveedoresXProductos as $PXP) {

                          echo $count.". ".$PXP['nombre'].'<br />';
                          $count++;
                        }
                      }else{
                          echo "NO DISPONIBLE";
                      }

                    ?>
                  </td>
                  <td class="text-center"> <?php echo remove_junk($product['precioDolares']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['precioColones']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['numeroPartida'])." (".remove_junk($product['nombrePartida']).")"; ?></td>
                  <td class="text-center" style="min-width: 100px;">
                    <div class="btn-group">
                      <a href="productos_proveedores.php?IdProd=<?php echo (int)$product['id'];?>" class="btn btn-warning btn-xs"  title="Administrar Proveedores" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-log-out"></span>
                      </a>
                      <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-trash"></span>
                      </a>
                      <a href="agregar_compra.php?nombre=<?php echo urlencode($product['nombre']);?>" class="btn btn-success btn-xs"  title="Agregar" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-plus"></span>
                      </a>

                    </div>
                  </td>
                </tr>
                <tr style="display: none;" id="fila<?=$product['id']?>">
                  <td colspan="12">
                    <h3>Caracteristicas</h3>
                     <ol>
                      <?php 


                      $caracteristicas =  html_entity_decode(remove_junk($product['caracteristicas'])); 
                      $acaracteristicas =  explode("|", $caracteristicas);

                      foreach ($acaracteristicas  as &$valor) {
                          echo '<li>'.$valor.'</li>';
                      }
                      ?>
                      </ol>
                  </td>
                </tr>
               <?php 
                endforeach; 
              } //fin else resultado vacio

              ?>


            </tbody>
          </table>
          <?php
            echo '<nav>';
            echo '<ul class="pagination">';
         
            if ($total_paginas > 1) {
                if ($pagina != 1) {
                    echo '<li class="page-item"><a class="page-link" onclick="recargar('."''".','."''".','.($pagina-1).');"><span aria-hidden="true">&laquo;</span></a></li>';
                }
         
                for ($i=1;$i<=$total_paginas;$i++) {
                    if ($pagina == $i) {
                        echo '<li class="page-item active"><a class="page-link" >'.$pagina.'</a></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" onclick="recargar('."''".','."''".','.($i).');">'.$i.'</a></li>';
                    }
                }
         
                if ($pagina != $total_paginas) {
                    echo '<li class="page-item"><a class="page-link" onclick="recargar('."''".','."''".','.($pagina+1).');"><span aria-hidden="true">&raquo;</span></a></li>';
                }
            }
            echo '</ul>';
            echo '</nav>';
          ?>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
<script>
  window.onload = function() {

    document.querySelector('#criterio').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
          recargar("","","");
        }
    });
    document.querySelector("#comboCategorias").addEventListener('change', (event) => {
      recargar("","","");
    });
    document.querySelector("#comboPartidas").addEventListener('change', (event) => {
      recargar("","","");
    });
    document.querySelector("#btnBuscar").addEventListener("click", function(){
      recargar("","","");
    }); 
};

function recargar(columna, ordenamiento,pagina){


  /*Si no hay columna*/
  if(columna=="" ){
    columna = document.querySelector("#columna").value;
  }
  /*Si no hay ordenamiento*/
  if(ordenamiento=="" ){
    ordenamiento = document.querySelector("#ordenamiento").value;
  }

  /*Si no hay pagina*/
  if(pagina=="" || pagina=='undefined'){
    var pagina = document.querySelector("#pagina").value;
  }
  /*Obtener el id de categoria*/
  var IdCategoria = document.querySelector("#comboCategorias").options[document.querySelector("#comboCategorias").selectedIndex].value;

  /*Obtener el id de partida*/
  var IdPartida = document.querySelector("#comboPartidas").options[document.querySelector("#comboPartidas").selectedIndex].value;

  /*Obtener el criterio de busqueda*/
  var criterio = document.querySelector("#criterio").value;

  window.location.href = "product.php?columna="+columna+"&ordenamiento="+ordenamiento+"&IdCategoria="+IdCategoria+"&criterio="+criterio+"&pagina="+pagina+"&IdPartida="+IdPartida;
}


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



<script>
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    })
</script>

