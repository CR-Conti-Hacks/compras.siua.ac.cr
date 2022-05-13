<?php
  /*Time zone*/
  date_default_timezone_set('America/Costa_Rica');
  
  /*Titulo de la pagina*/
  $page_title = 'Lista de grupos';

  /*funciones*/
  require_once('includes/load.php');

  /*Derecho de pagina*/
  page_require_level(1);

  /*ordenamiento y busqueda*/
  $columna = (isset($_GET['columna'])) ? $_GET['columna'] : 'group_name';
  $ordenamiento = (isset($_GET['ordenamiento'])) ? $_GET['ordenamiento'] : 'ASC';
  $criterio = (isset($_GET['criterio'])) ? $_GET['criterio'] : '';

  /*obtener datos*/
  $all_groups = find_all_grupos($columna,$ordenamiento,$criterio);

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
        <span>Grupos</span>
     </strong>
       <a href="add_group.php" class="btn btn-compras pull-right btn-sm"> Agregar grupo</a>
    </div>
     <div class="panel-body">
      <div class="input-group">
          <input type="text" class="form-control" placeholder="Buscar..." value="<?=$criterio?>" id="criterio" name="criterio">
          <span class="input-group-btn">
            <button class="btn btn-primary" type="button" id="btnBuscar" name="btnBuscar">Buscar</button>
          </span>
      </div>
      <br />
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center titulo_tabla" style="width: 50px;">#</th>
            <th class="titulo_tabla">
              Nombre del grupo
              <a onclick="recargar('group_name','ASC')">
                <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
              </a>
              <a onclick="recargar('group_name','DESC')">
                <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
              </a>
            </th>
            <th class="text-center titulo_tabla" style="width: 20%;">
              Nivel del grupo
              <a onclick="recargar('group_level','ASC')">
                <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
              </a>
              <a onclick="recargar('group_level','DESC')">
                <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
              </a>
            </th>
            <th class="text-center titulo_tabla" style="width: 15%;">
              Estado
              <a onclick="recargar('group_status','ASC')">
                <span class="glyphicon glyphicon-arrow-up iconoOrdenar" aria-hidden="true"></span>
              </a>
              <a onclick="recargar('group_status','DESC')">
                <span class="glyphicon glyphicon-arrow-down iconoOrdenar" aria-hidden="true"></span>
              </a>
            </th>
            <th class="text-center titulo_tabla" style="width: 100px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_groups as $a_group): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_group['group_name']))?></td>
           <td class="text-center">
             <?php echo remove_junk(ucwords($a_group['group_level']))?>
           </td>
           <td class="text-center">
           <?php if($a_group['group_status'] === '1'): ?>
            <span class="label label-success"><?php echo "Activo"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Inactivo"; ?></span>
          <?php endif;?>
           </td>
           <td class="text-center">
             <div class="btn-group">
                <a href="edit_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <a href="delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
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
    var pagina ="group.php";
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