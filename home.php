<?php
  $page_title = 'Inicio';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
        <h1>Bienvenid@</h1>
        <p>Por favor dirijase a la sección de:</p>
        <ol class="lista_home">
          <li>Categorías: si desea agregar una nueva categoría.</li>
          <li>Productos: si desea agregar un nuevo producto.</li>
          <li>Reportes: si desea generar un reporte.</li>
        </ol>
      </div>
    </div>
 </div>
</div>
<?php include_once('layouts/footer.php'); ?>
