<?php
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

  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'p.nombre';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';



  //Campos
  if(isset($_GET['campos'])){
    $campos = strval($_GET['campos']);
    //hacemos un explode para separar
    $campos = explode(";", $campos);
    //eliminamos el último campos
    array_pop($campos);

  }else{
    $campos = "imagen;nombre;descripcion;caracteristicas;justificacion;prioridad;categoria;nombrePartida;nombrePartida;precioColones;precioDolares;cantidad;totalColones;totalDolares;acciones;";
    //hacemos un explode para separar
    $campos = explode(";", $campos);
    //eliminamos el último campos
    array_pop($campos);
  }  
  
  //Mostrar
  if(isset($_GET['mostrar'])){
    $mostrar = strval($_GET['mostrar']);
    //hacemos un explode para separar
    $mostrar = explode(";", $mostrar);
    //eliminamos el último campos
    array_pop($mostrar);

  }else{
    $mostrar = "1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;";
    //hacemos un explode para separar
    $mostrar = explode(";", $mostrar);
    //eliminamos el último campos
    array_pop($mostrar);
  }  

  /*Obtener catalogos*/
  $all_categories = find_all('categories');
  $all_partidas = find_all('partidas');
 


  /*Obtener usuario actual*/
  $usuario = current_user();
  $idUsu = $usuario['id'];
  /*Obtener las compras del usuario*/
  $misCompras = obtenerMisCompras($idUsu,$anno,$IdCategoria,$criterio,$inicio,REGISTROS_X_PAGINA,$columna,$ordenamiento,$IdPartida,$IdPrioridad,$campos);
  

  /*obtener cantidad de prodcutos que cumplan con el crierio*/
  $cantidadProductos = count(obtenerCantidadMisComprasXCategoriaPartidaCriterio($idUsu,$anno,$IdCategoria,$criterio,$IdPartida,$IdPrioridad));
  //calculo el total de paginas
  $total_paginas = ceil($cantidadProductos / REGISTROS_X_PAGINA);




?>
<?php include_once('layouts/header.php'); ?>


<input type="hidden" id="columna" name="columna" value="<?=$columna?>">
<input type="hidden" id="ordenamiento" name="ordenamiento" value="<?=$ordenamiento?>">
<input type="hidden" id="inicio" name="inicio" value="<?=$inicio?>">
<input type="hidden" id="pagina" name="pagina" value="<?=$pagina?>">


<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">

    <!-- *********************************************************************************************************-->
    <!-- **********************************       Título     *****************************************************-->
    <!-- *********************************************************************************************************-->
    <h2>MIS COMPRAS</h2>

    <!-- *********************************************************************************************************-->
    <!-- **********************************  Configuración   *****************************************************-->
    <!-- *********************************************************************************************************-->
    <div class="row">
      <div class="col col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">

            <strong>
              <a onclick="muestraOcultaConfiguracion('contenedor_Configuracion')">
                <span class="glyphicon glyphicon-wrench"></span>
              </a>
              <span> &nbsp; CONFIGURACIÓN</span>
            </strong>
            <a onclick="recargar('','','')"  class="btn btn-compras pull-right btn-sm"> Aplicar</a>
              
          </div>
          <div class="panel-body" style="display:none;" id="contenedor_Configuracion">
            <div class="row">
              <div class="col col-xs-12 col-lg-12">
                <div class="form-group">
                  <label for="exampleInputEmail1">Marque las columnas visibles:</label>

                  <div class="input-group">
                    <table class="table table-sortable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Columna</th>
                            <th>Mostrar/Ocultar</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                         <?php 
                          for($i=0; $i < count($campos); $i++){
                            switch ($campos[$i]) {
                              case 'imagen':
                                  ?>
                                  <tr draggable="true">
                                      <td>
                                        <span class="glyphicon glyphicon-move"></span>
                                      </td>
                                      <td>
                                        Imágen
                                        <input type="hidden" id="i_imagen" name="campos" value="imagen">
                                      </td>
                                      <td>
                                        <input type="checkbox" id="c_imagen"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                      </td>
                                  </tr>
                                  <?php
                                  break;
                              case 'nombre':
                                  ?>
                                  <tr draggable="true">
                                      <td>
                                        <span class="glyphicon glyphicon-move"></span>
                                      </td>
                                      <td>
                                        Nombre
                                        <input type="hidden" id="i_nombre" name="campos" value="nombre">
                                      </td>
                                      <td>
                                        <input type="checkbox" id="c_nombre"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                      </td>
                                  </tr>
                                  <?php
                                  break;
                              case 'descripcion':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Descripción
                                          <input type="hidden" id="i_descripcion" name="campos" value="descripcion">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_descripcion"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'caracteristicas':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Caracteristicas
                                          <input type="hidden" id="i_caracteristicas" name="campos" value="caracteristicas">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_caracteristicas"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'justificacion':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Justificación
                                          <input type="hidden" id="i_justificacion" name="campos" value="justificacion">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_justificacion"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'prioridad':
                                  ?>
                                  <tr draggable="true">
                                      <td>
                                        <span class="glyphicon glyphicon-move"></span>
                                      </td>
                                      <td>
                                        Prioridad
                                        <input type="hidden" id="i_prioridad" name="campos" value="prioridad">
                                      </td>
                                      <td>
                                        <input type="checkbox" id="c_prioridad"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                      </td>
                                  </tr>
                                  <?php
                                  break;
                              case 'categoria':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Categoría
                                          <input type="hidden" id="i_categoria" name="campos" value="categoria">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_categoria"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'nombrePartida':
                                  ?>
                                  <tr draggable="true">
                                      <td>
                                        <span class="glyphicon glyphicon-move"></span>
                                      </td>
                                      <td>
                                        Partida
                                        <input type="hidden" id="i_nombrePartida" name="campos" value="nombrePartida">
                                      </td>
                                      <td>
                                        <input type="checkbox" id="c_nombrePartida"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                      </td>
                                  </tr>

                                  <?php
                                  break;
                              case 'nombreProveedor':
                                  ?>
                                  <tr draggable="true">
                                      <td>
                                        <span class="glyphicon glyphicon-move"></span>
                                      </td>
                                      <td>
                                        Proveedores
                                        <input type="hidden" id="i_nombreProveedor" name="campos" value="nombreProveedor">
                                      </td>
                                      <td>
                                        <input type="checkbox" id="c_nombreProveedor"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                      </td>
                                  </tr>
                                  <?php
                                  break;
                              case 'precioColones':
                                  ?>
                                  <tr draggable="true">
                                      <td>
                                        <span class="glyphicon glyphicon-move"></span>
                                      </td>
                                      <td>
                                        Precio Colones (₡)
                                        <input type="hidden" id="i_precioColones" name="campos" value="precioColones">
                                      </td>
                                      <td>
                                        <input type="checkbox" id="c_precioColones"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                      </td>
                                  </tr>
                                  <?php
                                  break;
                              case 'precioDolares':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Precio Dólares ($)
                                          <input type="hidden" id="i_precioDolares" name="campos" value="precioDolares">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_precioDolares"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'cantidad':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Cantidad
                                          <input type="hidden" id="i_cantidad" name="campos" value="cantidad">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_cantidad"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'totalColones':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Total Colones (₡)
                                          <input type="hidden" id="i_totalColones" name="campos" value="totalColones">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_totalColones"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'totalDolares':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Total Dólares ($)
                                          <input type="hidden" id="i_totalDolares" name="campos" value="totalDolares">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_totalDolares"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;
                              case 'acciones':
                                  ?>
                                    <tr draggable="true">
                                        <td>
                                          <span class="glyphicon glyphicon-move"></span>
                                        </td>
                                        <td>
                                          Acciones
                                          <input type="hidden" id="i_acciones" name="campos" value="acciones">
                                        </td>
                                        <td>
                                          <input type="checkbox" id="c_acciones"  name="mostrar" <?php if($mostrar[$i]==1){?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                  <?php
                                  break;

                            }
                          }
                        ?>
                        

                  
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- *********************************************************************************************************-->
    <!-- ******************************  FIN Configuración   *****************************************************-->
    <!-- *********************************************************************************************************-->

    <!-- *********************************************************************************************************-->
    <!-- **********************************       Buscador   *****************************************************-->
    <!-- *********************************************************************************************************-->
    <div class="row">
      <div class="col col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">

            <strong>
              <a onclick="muestraOcultaConfiguracion('contenedor_Busqueda')">
                <span class="glyphicon glyphicon-search"></span>
              </a>
              <span> &nbsp; Buscar Producto</span>
            </strong>
              
              <!--a href="misCompras_print_excel.php"  class="btn btn-compras pull-right btn-sm" target="_blank" >Generar EXCEL</a-->
          </div>
          <div class="panel-body" style="display: none;" id="contenedor_Busqueda">
            <div class="row">
              
              <!-- ***************************************************-->
              <!-- ***************   Año          ********************-->
              <!-- ***************************************************-->
              <div class="col col-xs-12 col-lg-3">
                <div class="form-group">
                  <label for="comboCategorias" class="label_buscador">Año:</label>
                  <select 
                    class="selectpicker show-tick show-menu-arrow form-control" 
                    data-live-search="true" 
                    data-width="auto"
                    id="comboAnno" 
                    data-style="btn-primary"
                   
                    >
                    <?php
                      $annoInicio = 2020;
                      $annoActual = date('Y');
                      for($i = $annoInicio; $i<=$annoActual; $i++){
                    ?>
                      <option value="<?=$i?>" 
                      <?php
                        if($i == $anno){
                          echo "selected";
                        }
                      ?>
                      ><?=$i?></option>
                      
                    <?php
                      }
                    ?>
                  </select>
                </div>
              </div>
              <!-- ***************************************************-->
              <!-- ***************   Categoría    ********************-->
              <!-- ***************************************************-->
              <div class="col col-xs-12 col-lg-3">
                <div class="form-group">
                  <label for="comboCategorias"  class="label_buscador">Categoría:</label>
                  <select 
                    class="selectpicker show-tick show-menu-arrow form-control" 
                    data-live-search="true" 
                    data-width="300px"
                    id="comboCategorias" 
                    data-style="btn-primary"
            
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
              </div>

              <!-- ***************************************************-->
              <!-- ***************   Partida      ********************-->
              <!-- ***************************************************-->
              <div class="col col-xs-12 col-lg-3">
                <div class="form-group">
                  <label for="comboPartidas"  class="label_buscador">Partida: </label>
                  <select 
                    class="selectpicker show-tick show-menu-arrow form-control" 
                    data-live-search="true" 
                    data-width="auto"
                    id="comboPartidas" 
                    data-style="btn-primary"
                  
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
              <!-- ***************   Prioridad    ********************-->
              <!-- ***************************************************-->
              <div class="col col-xs-12 col-lg-3">
                <div class="form-group">
                  <label for="comboPartidas"  class="label_buscador">Prioridad: </label>
                  <select 
                    class="selectpicker show-tick show-menu-arrow form-control" 
                    data-live-search="true" 
                    data-width="auto"
                    id="comboPrioridad" 
                    data-style="btn-primary"
               
                    >
                    <option value="0"
                      <?php 
                        if($IdPrioridad == 0){
                          echo "selected";
                        }
                      ?>
                    >[Todas]</option>
                    <option value="1"
                      <?php 
                        if($IdPrioridad == 1){
                          echo "selected";
                        }
                      ?>
                    >Baja</option>
                    <option value="2"
                    <?php 
                      if($IdPrioridad == 2){
                        echo "selected";
                      }
                    ?>
                    >Normal</option>
                    <option value="3"
                    <?php 
                      if($IdPrioridad == 3){
                        echo "selected";
                      }
                    ?>
                    >Alta</option>

                  </select>
                </div>
              </div>


              <!-- ***************************************************-->
              <!-- ***************   Nombre       ********************-->
              <!-- ***************************************************-->
              <div class="col col-xs-12 col-lg-12">
                <div class="form-group">
                  <label for="exampleInputEmail1">Digite un criterio</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Buscar producto..." id="criterio" value="<?=$criterio?>">
                    <span class="input-group-btn">
                      <button class="btn btn-primary" type="button" id="btnBuscar">Buscar</button>
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
    </div> 
    <!-- *********************************************************************************************************-->
    <!-- **********************************   FIN Buscador   *****************************************************-->
    <!-- *********************************************************************************************************-->


    <!-- *********************************************************************************************************-->
    <!-- **********************************       Botones    *****************************************************-->
    <!-- *********************************************************************************************************-->
    <div class="btn-group pull-right">
      <button type="button" class="btn btn-compras btn-sm dropdown-toggle" data-toggle="dropdown">Exportar <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><a class="dataExport" data-type="csv">CSV</a></li>
        <li><a class="dataExport" data-type="excel">XLS</a></li>          
        <li><a class="dataExport" data-type="txt">TXT</a></li>              
      </ul>
    </div>
    <a href="agregar_compra.php"  class="btn btn-compras pull-right btn-sm"> Agregar Producto</a>
    <!-- *********************************************************************************************************-->
    <!-- **********************************   FIN Botones    *****************************************************-->
    <!-- *********************************************************************************************************-->

    <br />
    <span style="display: block; text-align:center;">Total de productos: <?=$cantidadProductos?></span>
    <br />

    <!-- *********************************************************************************************************-->
    <!-- **********************************       tabla      *****************************************************-->
    <!-- *********************************************************************************************************-->
    <table class="table table-bordered table-striped" id="tableMisCompras" style=" margin-left: auto; margin-right: auto;">
      <thead>
        <tr>
            <th class="text-center titulo_tabla imagen">Instancia solicitante</th>

          <?php 
            for($i=0; $i < count($campos); $i++){
              switch ($campos[$i]) {
                case 'imagen':
                    if($mostrar[$i]==1){
                    ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Imagen     *******************************************-->
                      <!-- ****************************************************************************************-->
                      <th class="text-center titulo_tabla imagen" style="width: 50px;">Imagen </th>
                    <?php
                    }
                    break;
                case 'nombre':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Nombre     *******************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla nombre" style="width: 100px;">
                      Nombre
                      <a onclick="recargar('p.nombre','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('p.nombre','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                  }
                    break;
                case 'descripcion':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Descripción ******************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla descripcion" style="width: 250px;">
                      Descripción
                      <a onclick="recargar('p.descripcion','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('p.descripcion','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'caracteristicas':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Caracteristicas **************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla descripcion" style="width: 250px;">
                      Caracteristicas
                      <a onclick="recargar('p.caracteristicas','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('p.caracteristicas','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'justificacion':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Justificación ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla justificacion" style="width: 250px;">
                      Justificación
                      <a onclick="recargar('c.justificacion','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('c.justificacion','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'prioridad':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Prioridad     ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla prioridad" style="width: 100px;">
                      Prioridad
                      <a onclick="recargar('c.prioridad','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('c.prioridad','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'categoria':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Categoría     ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla categoria" style="width: 200px;"> 
                      Categoría 
                      <a onclick="recargar('cat.id','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('cat.id','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'nombrePartida':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Partida       ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla partida" style="width: 200px;"> 
                      Partida 
                      <a onclick="recargar('p.idPar','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('p.idPar','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>

                    <?php
                    }
                    break;
                case 'nombreProveedor':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Proveedores   ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla proveedores"  style="width: 250px;"> 
                      Proveedores
                    </th>
                    <?php
                    }
                    break;
                case 'precioColones':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* precioColones ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla colones"  style="width: 200px;"> 
                      ₡ 
                      <a onclick="recargar('c.precioColones','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('c.precioColones','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'precioDolares':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* precioDolares ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla dolares"  style="width: 200px;"> 
                      $
                      <a onclick="recargar('c.precioDolares','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('c.precioDolares','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'cantidad':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Cantidad      ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla cantidad"  style="width: 200px;">
                      Cantidad
                      <a onclick="recargar('c.cantidadProducto','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('c.cantidadProducto','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'totalColones':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Total ₡       ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla totalcolones" style="width: 200px;">
                      Total Colones (₡)
                      <a onclick="recargar('c.totalColones','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('c.totalColones','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'totalDolares':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Total $       ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla totaldolares" style="width: 200px;">
                      Total $
                      <a onclick="recargar('c.totalDolares','ASC','')">
                        <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                      </a>
                      <a onclick="recargar('c.totalDolares','DESC','')">
                        <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                      </a>
                    </th>
                    <?php
                    }
                    break;
                case 'acciones':
                    if($mostrar[$i]==1){
                    ?>
                    <!-- ****************************************************************************************-->
                    <!-- ********************************* Acciones      ****************************************-->
                    <!-- ****************************************************************************************-->
                    <th class="text-center titulo_tabla" style="min-width: 80px;">Acciones</th>
                    <?php
                    }
                    break;

              }
            }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php 
          if(count($misCompras) == 0){
        ?>
          <tr>
            <td colspan="15" style="text-align: center;">
              No existen productos con estos criterios
            </td>
          </tr>

        <?php 
          }else{

        ?>
          
        <?php
            $i = 0;
            foreach ($misCompras as $compra):
              $i++;
              echo '<tr>';
              echo '<td style="text-align:center;">UGIT</td>';
              for($i=0; $i < count($campos); $i++){
                switch ($campos[$i]) {
                  case 'imagen':
                      if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* IMAGEN     *******************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="imagen">
                        <img src="uploads/products/<?=remove_junk($compra['imagen'])?>" alt="" style="width: 40px;">
                      </td>
                      <?php
                      }
                      break;
                  case 'nombre':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Nombre     *******************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="nombre"><?php echo html_entity_decode(remove_junk($compra['nombre'])); ?></td>

                      <?php
                      }
                      break;
                  case 'descripcion':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Descripción ******************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="descripcion">
                        <?php 
                          echo "<b>Nombre:</b>".html_entity_decode(remove_junk($compra['nombre']))."<br />";
                          echo "<b>Descripción:</b>".html_entity_decode(remove_junk($compra['descripcion']))."<br />"; 
                          echo "<b>Características:</b>"."<br />"; 
                        ?>

                          <ul>
                            <?php 


                            $caracteristicas =  html_entity_decode(remove_junk($compra['caracteristicas'])); 
                            $acaracteristicas =  explode("|", $caracteristicas);

                            foreach ($acaracteristicas  as &$valor) {
                                echo '<li>'.$valor.'</li>';
                            }
                            ?>
                          </ul>

                      </td>
                      <?php
                      }
                      break;
                  case 'caracteristicas':
                        if($mostrar[$i]==1){
                      ?>
                        <!-- ****************************************************************************************-->
                        <!-- ********************************* Caracteristicas **************************************-->
                        <!-- ****************************************************************************************-->
                        <td  class="caracteristicas">
                          <ul>
                            <?php 


                            $caracteristicas =  html_entity_decode(remove_junk($compra['caracteristicas'])); 
                            $acaracteristicas =  explode("|", $caracteristicas);

                            foreach ($acaracteristicas  as &$valor) {
                                echo '<li>'.$valor.'</li>';
                            }
                            ?>
                            </ul>
                        </td>

                      <?php
                      }
                      break;
                  case 'justificacion':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Justificación ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="justificacion"><?php echo html_entity_decode(remove_junk($compra['justificacion'])); ?></td>

                      <?php
                      }
                      break;
                  case 'prioridad':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Prioridad     ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="text-center prioridad" id="cmc7">
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
                      <?php
                      }
                      break;
                  case 'categoria':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Categoría     ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="categoria"><?php echo remove_junk($compra['categoria']); ?></td>

                      <?php
                      }
                      break;
                  case 'nombrePartida':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Partida       ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="partida"><?php echo remove_junk($compra['nombrePartida']); ?></td>
            
                      <?php
                      }
                      break;
                  case 'nombreProveedor':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Proveedores   ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="proveedores">
                        <?php
                          /*Obtener todos los proveedores del producto*/
                          $listaProveedoresXProductos = find_all_nombre_proveedores_x_producto($compra['idProd']);
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
                      <?php
                      }
                      break;
                  case 'precioColones':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* precioColones ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="text-center colones"><?php echo $compra['precioColones']; ?></td>

                      <?php
                      }
                      break;
                  case 'precioDolares':
                        if($mostrar[$i]==1){
                      ?>
                        <!-- ****************************************************************************************-->
                        <!-- ********************************* precioDolares ****************************************-->
                        <!-- ****************************************************************************************-->
                        <td class="text-center dolares"><?php echo $compra['precioDolares']; ?></td>
                      <?php
                      }
                      break;
                  case 'cantidad':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Cantidad      ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="text-center cantidad"><?php echo (int)$compra['cantidad']; ?></td>
                      <?php
                      }
                      break;
                  case 'totalColones':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Total ₡       ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="text-center totalcolones"><?php echo remove_junk($compra['totalColones']); ?></td>
                      <?php
                      }
                      break;
                  case 'totalDolares':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Total $       ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="text-center totaldolares"><?php echo remove_junk($compra['totalDolares']); ?></td>
                      <?php
                      }
                      break;
                  case 'acciones':
                        if($mostrar[$i]==1){
                      ?>
                      <!-- ****************************************************************************************-->
                      <!-- ********************************* Acciones      ****************************************-->
                      <!-- ****************************************************************************************-->
                      <td class="text-center" >
                        <div class="btn-group">
                          <a href="editar_compra.php?idCompra=<?php echo (int)$compra['idCompra'];?>" class="btn btn-warning btn-xs"  title="Edit" data-toggle="tooltip">
                            <span class="glyphicon glyphicon-edit"></span>
                          </a>
                          <a href="eliminar_compra.php?idCompra=<?php echo (int)$compra['idCompra'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                            <span class="glyphicon glyphicon-trash"></span>
                          </a>
                        </div>
                      </td>
                      <?php
                      }
                      break;

                }
              }
              echo "</tr>";
        ?>

          
        <?php 
            endforeach;
          }
        ?>
      </tbody>
    </table>
    <!-- *********************************************************************************************************-->
    <!-- **********************************   FIN tabla      *****************************************************-->
    <!-- *********************************************************************************************************-->



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
    document.querySelector("#comboPrioridad").addEventListener('change', (event) => {
      recargar("","","");
    });
    document.querySelector("#btnBuscar").addEventListener("click", function(){
      recargar("","","");
    }); 


    $(".dataExport").click(function() {
        var exportType = $(this).data('type');    
        $('#tableMisCompras').tableExport({
          type : exportType,  
          fileName: 'ComprasUGIT',  
          


        });   
      });



    };

function recargar(columna, ordenamiento,pagina){

  //orden
  var campos = document.querySelectorAll('input[name="campos"]');
  var lista_campos = "";
  campos.forEach((campo) => {
    lista_campos += campo.value+";";
  });

  


  //mostrarOcultar
  var mostrados = document.querySelectorAll('input[name="mostrar"]');
  var lista_mostrar = "";
  mostrados.forEach((campo) => {
    if (campo.checked == true){
      lista_mostrar += "1;";  
    } else {
      lista_mostrar += "0;";  
    }
    
  });  


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

  /*Obtener el id de prioridad*/
  var IdPrioridad= document.querySelector("#comboPrioridad").options[document.querySelector("#comboPrioridad").selectedIndex].value;

  /*Obtener el criterio de busqueda*/
  var criterio = document.querySelector("#criterio").value;

  window.location.href = "mis_compras.php?columna="+columna+"&ordenamiento="+ordenamiento+"&IdCategoria="+IdCategoria+"&criterio="+criterio+"&pagina="+pagina+"&IdPartida="+IdPartida+'&IdPrioridad='+IdPrioridad+"&campos="+lista_campos+"&mostrar="+lista_mostrar;
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

/*function mostarOcultarColumna(id){
  if(document.querySelector("#bmc"+id).className == 'badge badge-activa'){
    document.querySelector("#bmc"+id).className = 'badge badge-inactiva';
    document.querySelector("#mc"+id).style.display = 'none';
    document.querySelector("#cmc"+id).style.display = 'none';
  }else if(document.querySelector("#bmc"+id).className == 'badge badge-inactiva'){
    document.querySelector("#bmc"+id).className = 'badge badge-activa';
    document.querySelector("#mc"+id).removeAttribute("style","display:none");  
    document.querySelector("#cmc"+id).removeAttribute("style","display:none");  
  }

}*/
$("input:checkbox:not(:checked)").each(function() {
    var column = "table ." + $(this).attr("name");
    $(column).hide();
});

$("input:checkbox").click(function(){
    var column = "table ." + $(this).attr("name");
    $(column).toggle();
});


/*Ordenamiento de tabla*/
$('table tbody').sortable({
  handle: 'span'
});

function muestraOcultaConfiguracion(objeto){

  var actual = document.getElementById(objeto).style.display; 
  if(actual == 'none'){
    document.getElementById(objeto).style.display = 'block';
  }else if(actual == 'block'){
    document.getElementById(objeto).style.display = 'none';
  }
}

</script>

<!-- ************************************************************************************************* -->
<!-- *******************************PARA GUARDAR EL ARCHIVO ****************************************** -->
<!-- ************************************************************************************************* -->
<script type="text/javascript" src="tableExport.jquery.plugin/libs/FileSaver/FileSaver.min.js"></script>

<!-- ************************************************************************************************* -->
<!-- *******************************PARA EXPORTAR EN XLSX     **************************************** -->
<!-- ************************************************************************************************* -->
<script type="text/javascript" src="tableExport.jquery.plugin/libs/js-xlsx/xlsx.core.min.js"></script>

<!-- ************************************************************************************************* -->
<!-- **********************PARA EXPORTAR EN PDF (Sin soporte carecteres)     ************************* -->
<!-- ************************************************************************************************* -->
<script type="text/javascript" src="tableExport.jquery.plugin/libs/jsPDF/jspdf.umd.min.js"></script>

<!-- ************************************************************************************************* -->
<!-- **********************PARA EXPORTAR EN PDF (CON soporte carecteres)     ************************* -->
<!-- ************************************************************************************************* -->
<script type="text/javascript" src="tableExport.jquery.plugin/libs/pdfmake/pdfmake.min.js"></script>
<script type="text/javascript" src="tableExport.jquery.plugin/libs/pdfmake/vfs_fonts.js"></script>


<!-- ************************************************************************************************* -->
<!-- **********************            PARA EXPORTAR EN PNG                  ************************* -->
<!-- ************************************************************************************************* -->
<script type="text/javascript" src="tableExport.jquery.plugin/libs/es6-promise/es6-promise.auto.min.js"></script>
<script type="text/javascript" src="tableExport.jquery.plugin/libs/html2canvas/html2canvas.min.js"></script>

<!-- ************************************************************************************************* -->
<!-- *******************************REQUERIDO TableExport ******************************************** -->
<!-- ************************************************************************************************* -->
<script type="text/javascript" src="tableExport.jquery.plugin/tableExport.min.js"></script>