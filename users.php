<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');

  /*Titulo de la pagina*/
  $page_title = 'Lista de usuarios';

  /*funciones*/
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(1);

  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'name';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';
  $criterio = (isset($_GET['criterio'])) ? $_GET['criterio'] : '';

  /*obtener datos*/
  $all_users = find_all_user($columna,$ordenamiento,$criterio);


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
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Usuarios</span>
       </strong>
         <a href="add_user.php" class="btn btn-compras pull-right">Agregar usuario</a>
      </div>
     <div class="panel-body">
      <div class="input-group">
          <input type="text" class="form-control" placeholder="Buscar..." value="<?=$criterio?>" id="criterio" name="criterio">
          <span class="input-group-btn">
            <button class="btn btn-primary" type="button" id="btnBuscar" name="btnBuscar">Buscar</button>
          </span>
      </div>
      <br />
      <table class="table table-bordered table-striped">
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
              Usuario
              <a onclick="recargar('usuario','ASC')">
                <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
              </a>
              <a onclick="recargar('usuario','DESC')">
                <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
              </a>
            </th>
            <th class="text-center titulo_tabla" style="width: 15%;">
              Rol de usuario
              <a onclick="recargar('rol','ASC')">
                <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
              </a>
              <a onclick="recargar('rol','DESC')">
                <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
              </a>
            </th>
            <th class="text-center titulo_tabla" style="width: 10%;">
              Estado
              <a onclick="recargar('estado','ASC')">
                <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
              </a>
              <a onclick="recargar('estado','DESC')">
                <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
              </a>
            </th>
            <th class="titulo_tabla" style="width: 20%;">Último login</th>
            <th class="text-center titulo_tabla" style="width: 100px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_user['name']))?></td>
           <td><?php echo remove_junk(ucwords($a_user['username']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_user['group_name']))?></td>
           <td class="text-center">
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Activo"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Inactivo"; ?></span>
          <?php endif;?>
           </td>
           <td><?php echo read_date($a_user['last_login'])?></td>
           <td class="text-center">
             <div class="btn-group">
                <a href="edit_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <a href="delete_user.php?id=<?php echo (int)$a_user['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
                </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
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
    var pagina ="users.php";
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
