<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}


  // Auto suggetion
   $html = '';
   if(isset($_POST['nombre']) && strlen($_POST['nombre']))
   {
     $productos = find_product_by_title($_POST['nombre']);
     if($productos){
        foreach ($productos as $producto):
           $html .= "<li class=\"list-group-item\">";
           $html .= '<img src="uploads/products/'.$producto['file_name'].'" style="width: 50px;" /> ';
           $html .= "<span>".$producto['nombre']."</span>";
           $html .= "</li>";
         endforeach;
      } else {

        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'No encontrado';
        $html .= "</li>";

      }

      echo json_encode($html);
   }
 ?>
 <?php

 // find all product
  if(isset($_POST['producto']) && strlen($_POST['producto']))
  {
    $nombreProducto = remove_junk($db->escape($_POST['producto']));
    if($resultados = find_all_product_info_by_title($nombreProducto)){
        foreach ($resultados as $resultado) {

          //error_log(print_r($resultado,TRUE));

          $caracteristicas = explode("|", $resultado['caracteristicas']);

          $formateoCaractisticas = "";
          for ($i = 0; $i < count($caracteristicas); $i++) {
              $formateoCaractisticas .= '<li>'.$caracteristicas[$i].'</li>';
          }
    

          $html .= "<tr>";
          /*Detalle*/
          $html .= '<td style="text-align:center;">';
          $html .= '<a onclick="muestraFila(';
          $html .= "'";
          $html .= 'fila'.$resultado['id'];
          $html .= "'";
          $html .= ",";
          $html .= "'";
          $html .= 'fecha'.$resultado['id'];
          $html .= "'";
          $html .= ');">';
          $html .= '<span class="glyphicon glyphicon-chevron-down" aria-hidden="true" id="fecha'.$resultado['id'].'"></span>';
          $html .= '</a>';
          $html .= '</td>';
          
          /*imagen*/
          $html .= '<td><img id="imagenProducto" src="uploads/products/'.$resultado['imagen'].'" style="width:40px;"/></td>';

          /*nombre*/
          $html .= "<td id=\"nombreProducto\">".$resultado['nombre']."</td>";
          $html .= "<input type=\"hidden\" name=\"idProducto\" value=\"{$resultado['id']}\">";

          /*Descripción*/
          $html  .= "<td>";
          $html  .= "{$resultado['descripcion']}";
          $html  .= "</td>";

          /*Justificación*/
          $html  .= "<td>";
          $html  .= "<textarea class=\"form-control\" name=\"justificacionProducto\">{$resultado['justificacion']}</textarea>";
          $html  .= "</td>";

          /*Prioridad*/
          $html .=  "<td>";
          $html .=  "<select name=\"prioridad\">";
          $html .=  "<option value=\"1\">Baja</option>";
          $html .=  "<option value=\"2\">Normal</option>";
          $html .=  "<option value=\"3\">Alta</option>";
          $html .=  "<select>";
          $html .= "</td>";

          /*Precio Colones*/
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"precioColones\" id=\"precioColones\"  value=\"{$resultado['precioColones']}\">";
          $html  .= "</td>";

          /*Precio Dolares*/
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"precioDolares\"  id=\"precioDolares\" value=\"{$resultado['precioDolares']}\">";
          $html  .= "</td>";

          /*Cantidad solicitada*/
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"cantidadSolicitadaProducto\" id=\"cantidadSolicitadaProducto\" value=\"0\">";
          $html  .= "</td>";

          /*total colones*/
          $html  .= "<td>";
          $html  .= "<input readonly type=\"text\" class=\"form-control\" name=\"totalColones\" id=\"totalColones\" value=\"0.00\">";
          $html  .= "</td>";
           /*total doalres*/
          $html  .= "<td>";
          $html  .= "<input readonly  type=\"text\" class=\"form-control\" name=\"totalDolares\"  id=\"totalDolares\" value=\"0.00\">";
          $html  .= "</td>";


          $html  .= "<td>";
          $html  .= "<button type=\"submit\" name=\"agregarCompra\" class=\"btn btn-primary\">Agregar</button>";
          $html  .= "</td>";
          $html  .= "</tr>";


          /*Fila de detalle*/
          $html .= '<tr id="fila'.$resultado['id'].'" style="display:none;">';
          $html .= '<td colspan="9">';
          $html .= '<h3>Caracteristicas</h3>';
          $html .= '<ol>';
          $html .= $formateoCaractisticas;
          $html .= '</ol>';
          $html .= '</td>';
          $html .= '</tr>';



        }
    } else {
        $html ='<tr><td>El producto no se encuentra registrado en la base de datos</td></tr>';
    }

    echo json_encode($html);
  }
 ?>
