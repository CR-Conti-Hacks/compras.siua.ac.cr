<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Administración de imágenes';

  /*funciones*/  
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(3);

  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'file_name';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';
  $criterio = (isset($_GET['criterio'])) ? $_GET['criterio'] : '';


  $media_files = find_all_imagenes($columna,$ordenamiento,$criterio);



  if(isset($_POST['submit'])) {
  $photo = new Media();
  $photo->upload($_FILES['file_upload']);
    if($photo->process_media()){
        $session->msg('s','Imagen subida correctamente.');
        redirect('media.php');
    } else{
      $session->msg('d',join($photo->errors));
      redirect('media.php');
    }

  }

?>
<?php include_once('layouts/header.php'); ?>
<input type="hidden" id="columna" name="columna" value="<?=$columna?>">
<input type="hidden" id="ordenamiento" name="ordenamiento" value="<?=$ordenamiento?>">
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-picture"></span>
        <span>Administración de imagenes</span>
        <div class="pull-right">
          <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
              <div class="input-group">

                <span class="input-group-btn">
                  <input type="file" name="file_upload" multiple="multiple" class="btn btn-primary form-control-file"  />
                </span>
                <button type="submit" name="submit" class="btn btn-default">Subir</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="panel-body">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar..." value="<?=$criterio?>" id="criterio" name="criterio">
            <span class="input-group-btn">
              <button class="btn btn-compras" type="button" id="btnBuscar" name="btnBuscar">Buscar</button>
            </span>
        </div>
        <br />
        <table class="table">
          <thead>
            <tr>
              <th class="text-center titulo_tabla" style="width: 50px;">#</th>
              <th class="text-center titulo_tabla">Imagen</th>
              <th class="text-center titulo_tabla">
                Nombre
                <a onclick="recargar('file_name','ASC')">
                  <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                </a>
                <a onclick="recargar('file_name','DESC')">
                  <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                </a>
              </th>
              <th class="text-center titulo_tabla" style="width: 20%;">
                Tipo
                <a onclick="recargar('file_type','ASC')">
                  <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
                </a>
                <a onclick="recargar('file_type','DESC')">
                  <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
                </a>
              </th>
              <th class="text-center titulo_tabla" style="width: 50px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($media_files as $media_file): ?>
              <tr class="list-inline">
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center">
                  <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-thumbnail" />
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_name'];?>
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_type'];?>
                </td>
                <td class="text-center">
                  <a href="delete_media.php?id=<?php echo (int) $media_file['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
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
    var pagina ="media.php";
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