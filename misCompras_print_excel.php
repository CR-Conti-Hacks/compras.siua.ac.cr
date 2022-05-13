<?php
	$file="comprasUGIT.xls";
	header('Content-Disposition: attachment; filename="'.$file.'"; charset=utf-8)');
	header('Content-Type: application/vnd.ms-excel; charset=utf-8');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: max-age=0');
	header('Pragma: public');


  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo pde página*/
  $page_title = 'Mis compras';

  /*Funciones*/
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(3);

  /*****************************************************/
  /***************** PAGINACION ************************/
  /*****************************************************/
  /*Reiniciamos la variable pagina*/
  $pagina = false;


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

  if(isset($_GET['IdPrioridad'])){
    $IdPrioridad = $_GET['IdPrioridad'];
  }else{
    $IdPrioridad = 0;
  }

  if(isset($_GET['criterio'])){
    $criterio = $_GET['criterio'];
  }else{
    $criterio = "";
  }

  if(isset($_GET['anno'])){
    $anno = $_GET['anno'];
  }else{
    $anno = date("Y");
  }


  /*Obtener catalogos*/
  $all_categories = find_all('categories');
  $all_partidas = find_all('partidas');
 


  /*Obtener usuario actual*/
  $usuario = current_user();
  $idUsu = $usuario['id'];
  /*Obtener las compras del usuario*/
  $misCompras = obtenerTodasMisCompras($idUsu,$anno,$IdCategoria,$criterio,$IdPartida,$IdPrioridad);
  



?>


      <table class="table table-bordered table-striped" id="tableMisCompras" border="1">
        <thead>
          <tr>
            <th style="background-color: #007BFF; color: #FFF;">#</th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Imágen')?> </th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Nombre')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Descripción')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Caracteristicas')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Justificación')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Prioridad')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Categoría')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Partida')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Proveedores')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Precio Colones')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Precio Dólares')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Cantidad')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Total Colones')?></th>
            <th style="background-color: #007BFF; color: #FFF;"><?= utf8_decode('Total Dólares')?></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            if(count($misCompras) == 0){
          ?>
            <tr>
              <td colspan="16" style="text-align: center;">
                No existen productos con estos criterios
              </td>
            </tr>

          <?php 
            }else{
              foreach ($misCompras as $compra):
          ?>
            <tr>
              <td><?php echo count_id();?></td>
              <td>https://compras.siua.ac.cr/uploads/products/<?=remove_junk($compra['imagen'])?></td>
              <td><?php echo utf8_decode($compra['nombre']); ?></td>
              <td><?php echo utf8_decode($compra['descripcion']); ?></td>
              <td>
              	<ol>
                  <?php 


                  $caracteristicas =  utf8_decode($compra['caracteristicas']); 
                  $acaracteristicas =  explode("|", $caracteristicas);

                  foreach ($acaracteristicas  as &$valor) {
                      echo '<li>'.$valor.'</li>';
                  }
                  ?>
                  </ol>
              </td>
              <td><?php echo utf8_decode(html_entity_decode(remove_junk($compra['justificacion']))); ?></td>
              <td>
                  <?php 
                      if($compra['prioridad']=="1"){
                        $prioridad = '<span class="badge badge-success">Baja</span>';
                      }else if($compra['prioridad']=="2"){
                        $prioridad = '<span class="badge badge-warning">Normal</span>';
                      }else if($compra['prioridad']=="3"){
                        $prioridad = '<span class="badge badge-danger">Alta</span>';
                      }
                      echo $prioridad;
                  ?>
              </td>
              <td><?php echo utf8_decode(remove_junk($compra['categoria'])); ?></td>
              <td><?php echo utf8_decode(remove_junk($compra['nombrePartida'])); ?></td>
              <td>
                <?php
                  /*Obtener todos los proveedores del producto*/
                  $listaProveedoresXProductos = find_all_nombre_proveedores_x_producto($compra['idProd']);
                  $count = 1;
                  if(count($listaProveedoresXProductos) >0){
                    foreach ($listaProveedoresXProductos as $PXP) {

                      echo $count.". ".utf8_decode($PXP['nombre']).'<br />';
                      $count++;
                    }
                  }else{
                      echo "NO DISPONIBLE";
                  }

                ?>
              </td>
              <td><?php echo utf8_decode($compra['precioColones']); ?></td>
              <td><?php echo utf8_decode($compra['precioDolares']); ?></td>
              <td><?php echo (int)$compra['cantidad']; ?></td>
              <td><?php echo utf8_decode(remove_junk($compra['totalColones'])); ?></td>
              <td><?php echo utf8_decode(remove_junk($compra['totalDolares'])); ?></td>
            </tr>
          <?php 
              endforeach;
            }
          ?>
        </tbody>
      </table>


   