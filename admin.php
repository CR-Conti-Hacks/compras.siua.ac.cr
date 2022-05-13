<?php
  date_default_timezone_set('America/Costa_Rica');
  $page_title = 'Compras SIUA';
  require_once('includes/load.php');
  page_require_level(3);

?>
<?php
 $c_categorie     = count_by_id('categories');
 $c_product       = count_by_id('productos');
 $c_compras       = count_by_id('compras');
 $c_user          = count_by_id('users');
 $productosMasSolicitados   = find_higest_saleing_product('10');
 $recent_products = find_recent_product_added('5');
 $compras_recientes    = find_recent_sale_added('5');


?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?>
   </div>
</div>
  <div class="row">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2>
          <p class="text-muted">Usuarios</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-list"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">Categorías</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-blue">
          <i class="glyphicon glyphicon-hdd"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_product['total']; ?> </h2>
          <p class="text-muted">Productos</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="glyphicon glyphicon-print"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_compras['total']; ?></h2>
          <p class="text-muted">Compras</p>
        </div>
       </div>
    </div>
</div>

  <div class="row">
   <div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Productos más solicitados</span>
         </strong>
       </div>
       <div class="panel-body">
         <table class="table table-striped table-bordered table-condensed">
          <thead>
           <tr>
             <th>Título</th>
             <th>Total pedidos</th>
             <th>Cantidad total</th>
           <tr>
          </thead>
          <tbody>
            <?php foreach ($productosMasSolicitados as  $productoSolicitado): ?>
              <tr>
                <td><?php echo remove_junk(first_character($productoSolicitado['nombre'])); ?></td>
                <td><?php echo (int)$productoSolicitado['totalProductos']; ?></td>
                <td><?php echo (int)$productoSolicitado['cantidadSolicitada']; ?></td>
              </tr>
            <?php endforeach; ?>
          <tbody>
         </table>
       </div>
     </div>
   </div>
   <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>ÚLTIMAS SOLICITUDES</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-striped table-bordered table-condensed">
       <thead>
         <tr>
           <th class="text-center" style="width: 50px;">#</th>
           <th>Producto</th>
           <th>Fecha</th>
           <th>Costo total</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach ($compras_recientes as  $compraReciente): ?>
         <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td>
            <a href="edit_sale.php?id=<?php echo (int)$compraReciente['id']; ?>">
             <?php echo remove_junk(first_character($compraReciente['nombre'])); ?>
           </a>
           </td>
           <td><?php echo remove_junk(ucfirst($compraReciente['fecha'])); ?></td>
           <td>₡<?php echo remove_junk(first_character($compraReciente['precioColones'])); ?></td>
        </tr>

       <?php endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>ÚLTIMOS PRODUCTOS AGREGADOS</span>
        </strong>
      </div>
      <div class="panel-body">

        <div class="list-group">
      <?php foreach ($recent_products as  $recent_product): ?>
            <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo    (int)$recent_product['id'];?>">
                <h4 class="list-group-item-heading">
                 <?php if($recent_product['imagen'] === '0'): ?>
                    <img class="img-avatar img-circle" src="<?=dominio?>uploads/products/no_image.jpg" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="<?=dominio?>/uploads/products/<?php echo $recent_product['image'];?>" alt="" />
                <?php endif;?>
                <?php echo remove_junk(first_character($recent_product['nombre']));?>
                  <span class="label label-warning pull-right">
                 ₡<?php echo (int)$recent_product['precioColones']; ?>
                  </span>

                   <span class="label label-warning pull-right"  style="margin-right: 2px;">
                 $<?php echo (int)$recent_product['precioDolares']; ?>
                  </span>
                </h4>
                <span class="list-group-item-text pull-right">
                <?php echo remove_junk(first_character($recent_product['categorie'])); ?>
              </span>
          </a>
      <?php endforeach; ?>
    </div>
  </div>
 </div>
</div>
 </div>
  <div class="row">

  </div>



<?php include_once('layouts/footer.php'); ?>
