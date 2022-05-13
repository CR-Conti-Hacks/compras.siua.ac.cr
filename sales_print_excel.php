<?php
  header('Content-Type: charset=utf-8');
  header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
  header('Content-Disposition: attachment; filename=nombre_archivo.xls');
  date_default_timezone_set ('America/Costa_Rica');
  $page_title = 'Lista de ventas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);

  $sales = find_all_sale();


?>

<table border="1" cellpadding="2" cellspacing="0">
     <tr>
        <td style="background-color: #248; color: #FFF;">#</td>
        <td style="background-color: #248; color: #FFF;"> Imagen </td>
        <td style="background-color: #248; color: #FFF;"> Nombre del producto </td>
        <td style="background-color: #248; color: #FFF;"> Descripción </td>
        <td style="background-color: #248; color: #FFF;"> Caracteristicas </td>
        <td style="background-color: #248; color: #FFF;"> Justificación </td>
        <td style="background-color: #248; color: #FFF;"> Precio Dólares </td>
        <td style="background-color: #248; color: #FFF;"> Precio Colones </td>
        <td style="background-color: #248; color: #FFF;"> Cantidad</td>
        <td style="background-color: #248; color: #FFF;"> Total </td>
        <td style="background-color: #248; color: #FFF;"> Acciones </td>
    </tr>
   <tbody>
     <?php foreach ($sales as $sale):?>
     <tr>
       <td class="text-center"><?php echo count_id();?></td>
       <td>
          <img src="uploads/products/<?=remove_junk($sale['imagen'])?>" alt="" style="width: 120px;">
         
       </td>
       <td><?php echo remove_junk($sale['name']); ?></td>
       <td><?php echo remove_junk($sale['description']); ?></td>
       <td>
          <ol>
          <?php 
            
            $caracteristicas =  html_entity_decode(remove_junk($sale['caracteristicas'])); 
            $acaracteristicas =  explode("|", $caracteristicas);

            foreach ($acaracteristicas  as &$valor) {
                echo '<li>'.$valor.'</li>';
            }
          ?>
          </ol>
       </td>
       <td><?php echo remove_junk($sale['justificacion']); ?></td>

       <td class="text-center"><?php echo $sale['precioDolares']; ?></td>
       <td class="text-center"><?php echo $sale['precioColones']; ?></td>
       <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
       <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
       <td class="text-center">
          <div class="btn-group">
             <a href="edit_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-warning btn-xs"  title="Edit" data-toggle="tooltip">
               <span class="glyphicon glyphicon-edit"></span>
             </a>
             <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
               <span class="glyphicon glyphicon-trash"></span>
             </a>
          </div>
       </td>
     </tr>
     <?php endforeach;?>
   </tbody>
 </table>