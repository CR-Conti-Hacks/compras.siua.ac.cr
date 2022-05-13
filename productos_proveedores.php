<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Proveedores X Producto';

  /*funciones*/
  require_once('includes/load.php');


  /*Derecho de pagina*/
  page_require_level(3);
  
  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'id';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';
  $criterio = (isset($_GET['criterio'])) ? $_GET['criterio'] : '';


  /*Obtenemos el id producto pasado como parametro*/
  $IdProd = (int)$_GET['IdProd'];
  
  /*Obtenemos la información delproducto*/
  $producto = find_by_id('productos',$IdProd);
  
  /*Obtener todos los proveedores del producto*/
  $listaProveedoresXProductos = find_all_proveedores_x_producto($IdProd);

  $listaProveedores = find_all_proveedores($columna,$ordenamiento,$criterio);  

?>
<?php include_once('layouts/header.php'); ?>
<input type="hidden" id="columna" name="columna" value="<?=$columna?>">
<input type="hidden" id="ordenamiento" name="ordenamiento" value="<?=$ordenamiento?>">
<input type="hidden" id="IdProd" name="IdProd" value="<?=$IdProd?>">
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-plane"></span>
          <span>Proveedores de producto: <span class="colorProductoProveedor"><?=$producto['nombre']?></span> </span>
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
              <th class="text-center titulo_tabla" style="width: 130px;">¿Es Proveedor?</th>
            </tr>
          </thead>
          <tbody>
            <?php
                foreach ($listaProveedores as $proveedor):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk($proveedor['nombre']); ?></td>
                <td><?php echo remove_junk($proveedor['telefono']); ?></td>
                <td><?php echo remove_junk($proveedor['correo']); ?></td>
                <td class="text-center">

                  <label class="toggleButton" style="margin: 0 auto;">
                    <input type="checkbox" id="cb_<?=$proveedor['id']?>" onclick="actualizaProveedorxProducto('<?=$proveedor["id"]?>','<?=$producto['id']?>');" 
                      <?php
                          foreach ($listaProveedoresXProductos as $PXP) {
                            if($proveedor['id'] == $PXP['IdProv']){
                              echo "checked";
                            }
                          }
                      ?>

                    >
                      <div style="margin: 0 auto;">
                          <svg viewBox="0 0 44 44">
                              <path d="M14,24 L21,31 L39.7428882,11.5937758 C35.2809627,6.53125861 30.0333333,4 24,4 C12.95,4 4,12.95 4,24 C4,35.05 12.95,44 24,44 C35.05,44 44,35.05 44,24 C44,19.3 42.5809627,15.1645919 39.7428882,11.5937758" transform="translate(-2.000000, -2.000000)"></path>
                          </svg>
                      </div>
                      
                </label>

                </td>
              </tr>
            <?php 
                endforeach; 
   
            ?>
          </tbody>
        </table>
        <div class="row">
          <div class="col-md-12" style="text-align: center;">
            <button type="button" class="btn btn-compras" onclick="location.href='product.php'"> Regresar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
    var pagina ="productos_proveedores.php";
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

    /*Obtener IdProd*/
    var IdProd = document.querySelector("#IdProd").value;

    location.href= pagina+"?"+"columna="+columna+"&ordenamiento="+ordenamiento+"&criterio="+criterio+"&IdProd="+IdProd;
    
  }

  function actualizaProveedorxProducto(IdProv,IdProd){
    
    var marcado = "";
    if(document.querySelector("#cb_"+IdProv).checked == true){
      marcado = "1";
    }else{
      marcado = "0";
    }

    var formData = new FormData();
    formData.append('IdProv', IdProv);
    formData.append('IdProd', IdProd);
    formData.append('marcado', marcado);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'productos_proveedores_guardar_ajax.php', true);
    xhr.onload = function(e) { 
      if (this.status == 200) {
        console.log(this.response);

      }
    };

    xhr.send(formData);
    
  }

</script>