<?php
  date_default_timezone_set('America/Costa_Rica');
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user($columna,$tipoOrdenamiento,$criterio){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ";
      if($criterio != ""){
        $sql .= "WHERE u.name LIKE '%".$criterio."%' OR u.username LIKE '%".$criterio."%' ";
      }
      if($columna == "nombre"){
        $columna = "u.name";
      }
      if($columna == "usuario"){
        $columna = "u.username";
      }
      if($columna == "rol"){
        $columna = "g.group_name";
      }
      if($columna == "estado"){
        $columna = "u.status";
      }
      $sql .= "ORDER BY ".$columna." ".$tipoOrdenamiento;
      //error_log($sql);
      $result = find_by_sql($sql);
      return $result;
  }




  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";

    $result = $db->query($sql);
    return($db->num_rows($result) == 0 ? false : true);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_name, group_level, group_status FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    if( $db->num_rows($result) == 0 ){
      return false;
    }else{
      $resultado = find_by_sql($sql);
      $res = array('group_name' => $resultado[0]['group_name'],'group_level' => $resultado[0]['group_level'],'group_status' => $resultado[0]['group_status'], );
      return $res;
    }
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
  function page_require_level($require_level){
    global $session;

    $current_user = current_user();
    $login_level = find_by_groupLevel($current_user['user_level']);
    //if user not login
    if (!$session->isUserLoggedIn(true)):
      $session->msg('d','Por favor Iniciar sesión...');
      redirect('index.php', false);
    //if Group status Deactive
    elseif($login_level['group_status'] === '0'):
      $session->msg('d','Este nivel de usuario esta inactivo!');
      redirect('home.php',false);
    //cheackin log in User level and Require level is Less than or equal to
    elseif($current_user['user_level'] <= (int)$require_level):
      return true;
    else:
      $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
      redirect('home.php', false);
      endif;
  }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
      global $db;
      $sql  =" SELECT p.id,p.nombre, p.precioDolares,p.precioColones,p.imagen,p.fecha,c.name";
      $sql  .=" AS categorie,m.file_name AS image,p.descripcion,p.caracteristicas,p.justificacion";
      $sql  .=" FROM productos p";
      $sql  .=" LEFT JOIN categories c ON c.id = p.idCat";
      $sql  .=" LEFT JOIN media m ON m.id = p.imagen";
      $sql  .=" ORDER BY p.id ASC LIMIT 20";

    return find_by_sql($sql);

   }

   /*--------------------------------------------------------------*/
   /* Function for Finding all product name por idcategori y criterio ugit
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table_busqueda($categoria,$criterio,$inicio,$cantidad,$columna,$ordenamiento,$partida){
      $criterio = strtolower($criterio);
      global $db;
      $sql  =" SELECT p.id,p.nombre,p.precioDolares,p.precioColones,p.imagen,p.fecha,c.name";
      $sql  .=" AS categorie,m.file_name AS image,p.descripcion,p.caracteristicas,p.justificacion, par.numero AS numeroPartida, par.nombre AS nombrePartida";
      $sql  .=" FROM productos p";
      $sql  .=" LEFT JOIN categories c ON c.id = p.idCat";
      $sql  .=" LEFT JOIN media m ON m.id = p.imagen";
      $sql  .=" LEFT JOIN partidas par ON par.id = p.idPar";

      /*Determinar si hay un where */     
      if( ($categoria !=0) || ($partida!=0) || ($criterio!="") ){
        $sql .= " WHERE ";

        if($categoria !=0){
          if($partida !=0){
            if($criterio !=""){
              $sql .= " (c.id = ".$categoria.") AND (par.id=".$partida.") AND ("." lower(p.nombre) like '%".$criterio."%' OR lower(precioDolares) like '%".$criterio."%' OR lower(precioColones) like '%".$criterio."%' OR CONVERT(descripcion USING latin1) like '%".$criterio."%' OR CONVERT(justificacion USING latin1) like '%".$criterio."%' ) ";
            }else{
              $sql .= " (c.id = ".$categoria.") AND (par.id=".$partida.") ";
            }
          }else{
            $sql .= " c.id = ".$categoria;
          }
        }else if($partida !=0){
          if($criterio!=""){
            $sql .= " (par.id=".$partida.") AND ("." lower(p.nombre like) '%".$criterio."%' OR lower(precioDolares) like '%".$criterio."%' OR lower(precioColones) like '%".$criterio."%' OR CONVERT(descripcion USING latin1) like '%".$criterio."%' OR CONVERT(justificacion USING latin1) like '%".$criterio."%' ) ";
          }else{
            $sql .= " par.id=".$partida;
          }
        }else if($criterio !=""){
          $sql .= " lower(p.nombre) like '%".$criterio."%' OR lower(precioDolares) like '%".$criterio."%' OR lower(precioColones) like '%".$criterio."%' OR CONVERT(descripcion USING latin1) like '%".$criterio."%' OR CONVERT(justificacion USING latin1) like '%".$criterio."%' ";
        }


      }


      /*ordenamiento por categoria*/
      if($columna =="categoria"){
        $columna = "c.name";
      }
      /*ordenamiento por partida*/
      if($columna =="partida"){
        $columna = "nombrePartida";
      }
      $sql  .=" ORDER BY ".$columna." ".$ordenamiento." LIMIT ".$inicio.",".$cantidad;
      //error_log($sql);
    return find_by_sql($sql);

   }

   function obtenerCantidadProductosXCaterogiaYCriterio($categoria,$criterio,$partida){
      global $db;
      $sql  =" SELECT p.id,p.nombre,p.precioDolares,p.precioColones,p.imagen,p.fecha,c.name";
      $sql  .=" AS categorie,m.file_name AS image,p.descripcion,p.caracteristicas,p.justificacion, par.numero AS numeroPartida, par.nombre AS nombrePartida";
      $sql  .=" FROM productos p";
      $sql  .=" LEFT JOIN categories c ON c.id = p.idCat";
      $sql  .=" LEFT JOIN media m ON m.id = p.imagen";
      $sql  .=" LEFT JOIN partidas par ON par.id = p.idPar";
     

      /*Determinar si hay un where */     
      if( ($categoria !=0) || ($partida!=0) || ($criterio!="") ){
        $sql .= " WHERE ";

        if($categoria !=0){
          if($partida !=0){
            if($criterio !=""){
              $sql .= " (c.id = ".$categoria.") AND (par.id=".$partida.") AND ("." lower(p.nombre) like '%".$criterio."%' OR lower(precioDolares) like '%".$criterio."%' OR lower(precioColones) like '%".$criterio."%' OR CONVERT(descripcion USING latin1) like '%".$criterio."%' OR CONVERT(justificacion USING latin1) like '%".$criterio."%' ) ";
            }else{
              $sql .= " (c.id = ".$categoria.") AND (par.id=".$partida.") ";
            }
          }else{
            $sql .= " c.id = ".$categoria;
          }
        }else if($partida !=0){
          if($criterio!=""){
            $sql .= " (par.id=".$partida.") AND ("." lower(p.nombre like) '%".$criterio."%' OR lower(precioDolares) like '%".$criterio."%' OR lower(precioColones) like '%".$criterio."%' OR CONVERT(descripcion USING latin1) like '%".$criterio."%' OR CONVERT(justificacion USING latin1) like '%".$criterio."%' ) ";
          }else{
            $sql .= " par.id=".$partida;
          }
        }else if($criterio !=""){
          $sql .= " lower(p.nombre) like '%".$criterio."%' OR lower(precioDolares) like '%".$criterio."%' OR lower(precioColones) like '%".$criterio."%' OR CONVERT(descripcion USING latin1) like '%".$criterio."%' OR CONVERT(justificacion USING latin1) like '%".$criterio."%' ";
        }


      }

    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     //ugit: traer tambien la imagen
     $sql = "SELECT nombre, imagen, file_name FROM productos INNER JOIN media ON (media.id = productos.imagen ) WHERE nombre like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  =   "SELECT 
                productos.id,  
                productos.idCat,
                productos.idPar,
                productos.nombre,
                productos.precioColones,
                productos.precioDolares,
                productos.imagen,
                productos.fecha,
                productos.descripcion,
                productos.caracteristicas,
                productos.justificacion,
                media.file_name as imagen

              FROM productos 
              INNER JOIN media ON (media.id = productos.imagen )";
    $sql .= " WHERE nombre ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  /*function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE productos SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }*/
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.nombre,p.precioDolares,p.precioColones, p.imagen,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM productos p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.idCat";
   $sql  .= " LEFT JOIN media m ON m.id = p.imagen";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.nombre AS nombre, COUNT(c.idProd) AS totalProductos, cantidadProducto As cantidadSolicitada";
   $sql .= " FROM compras c";
   $sql .= " LEFT JOIN productos p ON p.id = c.idProd ";
   $sql .= " GROUP BY c.idProd";
   $sql .= " ORDER BY p.nombre DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT c.id, c.precioColones, c.precioDolares,c.fecha,p.nombre,p.descripcion, p.caracteristicas as caracteristicas, p.justificacion as justificacion, p.precioDolares as precioDolares, p.precioColones as precioColones, p.imagen as idImagen, m.file_name as imagen";
   $sql .= " FROM compras c";
   $sql .= " LEFT JOIN productos p ON c.idProd = p.id";
   $sql .= " JOIN media m ON p.imagen = m.id";
   $sql .= " ORDER BY c.fecha DESC";
  // error_log($sql);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT c.id AS id ,c.precioColones AS precioColones,c.fecha AS fecha ,p.nombre AS nombre";
  $sql .= " FROM compras c";
  $sql .= " LEFT JOIN productos p ON c.idProd = p.id";
  $sql .= " ORDER BY c.fecha DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.nombre,p.precioColones,p.precioDolares,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.nombre";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.nombre,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.nombre,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar partidas
/*--------------------------------------------------------------*/
function  find_all_partidas($columna,$tipoOrdenamiento,$criterio){
   global $db;
   $sql ="SELECT * FROM partidas ";
   if($criterio != ""){
    $sql .= "WHERE numero LIKE '%".$criterio."%' OR nombre LIKE '%".$criterio."%' ";
   }
   $sql .= "ORDER BY ".$columna." ".$tipoOrdenamiento;
   
   return find_by_sql($sql);
   
}

/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar grupos
/*--------------------------------------------------------------*/
function  find_all_grupos($columna,$tipoOrdenamiento,$criterio){
   global $db;
   $sql ="SELECT * FROM user_groups ";
   if($criterio != ""){
    $sql .= "WHERE group_name LIKE '%".$criterio."%' ";
   }
   $sql .= "ORDER BY ".$columna." ".$tipoOrdenamiento;
   
   return find_by_sql($sql);
   
}


/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar categorías
/*--------------------------------------------------------------*/
function  find_all_categorias($columna,$tipoOrdenamiento,$criterio){
   global $db;
   $sql ="SELECT * FROM categories ";
   if($criterio != ""){
    $sql .= "WHERE name LIKE '%".$criterio."%' ";
   }
   $sql .= "ORDER BY ".$columna." ".$tipoOrdenamiento;
   
   return find_by_sql($sql);
   
}


/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar proveedores
/*--------------------------------------------------------------*/
function  find_all_proveedores($columna,$tipoOrdenamiento,$criterio){
   global $db;
   $sql ="SELECT * FROM proveedores ";
   if($criterio != ""){
    $sql .= "WHERE nombre LIKE '%".$criterio."%' OR telefono LIKE '%".$criterio."%' OR correo LIKE '%".$criterio."%' ";
   }
   $sql .= "ORDER BY ".$columna." ".$tipoOrdenamiento;
   
   return find_by_sql($sql);
   
}


/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar proveedores
/*--------------------------------------------------------------*/
function  find_all_proveedores_x_producto($producto){
    global $db;
    $sql ="SELECT IdProv, IdProd
          FROM proveedores_x_productos pxp 
          WHERE IdProd = ".$producto;
    
    $sql .= " ORDER BY IdProv";
    //error_log($sql);
    return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar proveedores
/*--------------------------------------------------------------*/
function  find_all_nombre_proveedores_x_producto($producto){
    global $db;
    $sql ="SELECT pxp.IdProv, pxp.IdProd, p.nombre AS nombre
          FROM proveedores_x_productos pxp 
          INNER JOIN proveedores p ON p.id = pxp.IdProv
          WHERE IdProd = ".$producto;
    
    $sql .= " ORDER BY IdProv";
    //error_log($sql);
    return find_by_sql($sql);
}


/*--------------------------------------------------------------*/
/* Función para actualizar proveedores x producto
/*--------------------------------------------------------------*/
function  actualizaProveedor_x_producto($marcado,$IdProv,$IdProd){
    
    if($marcado == 1){
    
      $sql = "INSERT INTO proveedores_x_productos (IdProv,IdProd) VALUES ('{$IdProv}','{$IdProd}')";
      $res = 1;
    }else if($marcado == 0){
    
      $sql = "DELETE FROM proveedores_x_productos WHERE IdProv = ".$IdProv." AND IdProd=".$IdProd;
      $res = 2;
    }
   
    global $db;
    $result = $db->query($sql);
    if($result && $db->affected_rows() === 1){
      return $res;
    }else{
      return $res = 3;
    }
   
}


/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar proveedores
/*--------------------------------------------------------------*/
function  buscarCompraXProdUsuAnnno($idProd,$idUsu,$Anno){
    global $db;
    $sql ="SELECT id FROM compras WHERE idProd = ".$idProd." AND IdUsu = ".$idUsu." AND YEAR(fecha) = '".$Anno."' AND estado = '1'";
    //error_log($sql);
    return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function obtenerMisCompras($idUsu,$anno,$categoria,$criterio,$inicio,$cantidad,$columna,$ordenamiento,$partida,$prioridad,$campos){



    $criterio = strtolower($criterio);

    global $db;

    $sql  = "SELECT 
              c.id AS idCompra, 
              c.idProd AS idProd,
              c.idUsu AS idUsu,
              c.precioColones AS precioColones, 
              c.precioDolares AS precioDolares,
              c.totalColones AS totalColones, 
              c.totalDolares AS totalDolares,
              c.fecha AS fecha,
              c.justificacion AS justificacion,
              c.tipoCambioDolar AS tipoCambio,
              c.cantidadProducto AS cantidad,
              c.estado AS estado,
              c.prioridad AS prioridad,

              p.nombre AS nombre,
              p.descripcion AS descripcion, 
              p.caracteristicas AS caracteristicas, 
              p.imagen AS idImagen,

              par.nombre AS nombrePartida,

              cat.name AS categoria,

              m.file_name AS imagen

              ";
   $sql .= " FROM compras c";
   $sql .= " LEFT JOIN productos p ON c.idProd = p.id";
   $sql .= " LEFT JOIN media m ON p.imagen = m.id";
   $sql .= " LEFT JOIN categories cat ON cat.id = p.idCat";
   $sql .= " LEFT JOIN partidas par ON par.id = p.idPar";
   $sql .= " WHERE (c.idUsu = ".$idUsu.") AND (YEAR(c.fecha) = '".$anno."') ";


    /*Categoria*/
    if($categoria !=0){
      $sql .= " AND (cat.id = ".$categoria.") ";
    }
    /*Partida*/
    if($partida!=0){
      $sql .= " AND (par.id=".$partida.") ";
    }
   
    /*Prioridad*/
    if($prioridad!=0){
      $sql .= " AND (c.prioridad = ".$prioridad.") ";
    }
    
    /*Criterio */
    if($criterio!=""){
      $sql .= " 
                AND 
                  (
                    (lower(p.nombre) like '%".$criterio."%') 
                    OR 
                    (lower(c.precioDolares) like '%".$criterio."%') 
                    OR 
                    (lower(c.precioColones) like '%".$criterio."%') 
                    OR 
                    (CONVERT(p.descripcion USING latin1) like '%".$criterio."%') 
                    OR 
                    (CONVERT(c.justificacion USING latin1) like '%".$criterio."%')
                  )  
                     ";
    }

    $sql  .=" ORDER BY ".$columna." ".$ordenamiento." LIMIT ".$inicio.",".$cantidad;

    return find_by_sql($sql);
}

 function obtenerTodasMisCompras($idUsu,$anno,$categoria,$criterio,$partida,$prioridad){
    $criterio = strtolower($criterio);
   global $db;
   $sql  = "SELECT 
              c.id AS idCompra, 
              c.idProd AS idProd,
              c.idUsu AS idUsu,
              c.precioColones AS precioColones, 
              c.precioDolares AS precioDolares,
              c.totalColones AS totalColones, 
              c.totalDolares AS totalDolares,
              c.fecha AS fecha,
              c.justificacion AS justificacion,
              c.tipoCambioDolar AS tipoCambio,
              c.cantidadProducto AS cantidad,
              c.estado AS estado,
              c.prioridad AS prioridad,

              p.nombre AS nombre,
              p.descripcion AS descripcion, 
              p.caracteristicas AS caracteristicas, 
              p.imagen AS idImagen,

              par.nombre AS nombrePartida,

              cat.name AS categoria,

              m.file_name AS imagen

              ";
   $sql .= " FROM compras c";
   $sql .= " LEFT JOIN productos p ON c.idProd = p.id";
   $sql .= " LEFT JOIN media m ON p.imagen = m.id";
   $sql .= " LEFT JOIN categories cat ON cat.id = p.idCat";
   $sql .= " LEFT JOIN partidas par ON par.id = p.idPar";
   $sql .= " WHERE (c.idUsu = ".$idUsu.") AND (YEAR(c.fecha) = '".$anno."') ";


    /*Categoria*/
    if($categoria !=0){
      $sql .= " AND (cat.id = ".$categoria.") ";
    }
    /*Partida*/
    if($partida!=0){
      $sql .= " AND (par.id=".$partida.") ";
    }
   
    /*Prioridad*/
    if($prioridad!=0){
      $sql .= " AND (c.prioridad = ".$prioridad.") ";
    }
    
    /*Criterio */
    if($criterio!=""){
      $sql .= " 
                AND 
                  (
                    (lower(p.nombre) like '%".$criterio."%') 
                    OR 
                    (lower(c.precioDolares) like '%".$criterio."%') 
                    OR 
                    (lower(c.precioColones) like '%".$criterio."%') 
                    OR 
                    (CONVERT(p.descripcion USING latin1) like '%".$criterio."%') 
                    OR 
                    (CONVERT(c.justificacion USING latin1) like '%".$criterio."%')
                  )  
                     ";
    }

    $sql  .=" ORDER BY prioridad DESC, cat.name ASC";
    error_log($sql);
    return find_by_sql($sql);
}


function obtenerCantidadMisComprasXCategoriaPartidaCriterio($idUsu,$anno,$categoria,$criterio,$partida,$prioridad){
      $criterio = strtolower($criterio);
      global $db;
      $sql  = "SELECT 
                  c.id AS idCompra, 
                  c.idProd AS idProd,
                  c.idUsu AS idUsu,
                  c.precioColones AS precioColones, 
                  c.precioDolares AS precioDolares,
                  c.totalColones AS totalColones, 
                  c.totalDolares AS totalDolares,
                  c.fecha AS fecha,
                  c.justificacion AS justificacion,
                  c.tipoCambioDolar AS tipoCambio,
                  c.cantidadProducto AS cantidad,
                  c.estado AS estado,
                  c.prioridad AS prioridad,

                  p.nombre AS nombre,
                  p.descripcion AS descripcion, 
                  p.caracteristicas AS caracteristicas, 
                  p.imagen AS idImagen,


                  par.nombre AS nombrePartida,

                  cat.name AS categoria,

                  m.file_name AS imagen

                  ";
      $sql .= " FROM compras c";
      $sql .= " LEFT JOIN productos p ON c.idProd = p.id";
      $sql .= " LEFT JOIN media m ON p.imagen = m.id";
      $sql .= " LEFT JOIN categories cat ON cat.id = p.idCat";
      $sql .= " LEFT JOIN partidas par ON par.id = p.idPar";
      $sql .= " WHERE (c.idUsu = ".$idUsu.") AND (YEAR(c.fecha) = '".$anno."') ";
     

      /*Categoria*/
      if($categoria !=0){
        $sql .= " AND (cat.id = ".$categoria.") ";
      }
      /*Partida*/
      if($partida!=0){
        $sql .= " AND (par.id=".$partida.") ";
      }
     
      /*Prioridad*/
      if($prioridad!=0){
        $sql .= " AND (c.prioridad = ".$prioridad.") ";
      }
      
      /*Criterio */
      if($criterio!=""){
        $sql .= " 
                AND 
                  (
                    (lower(p.nombre) like '%".$criterio."%') 
                    OR 
                    (lower(c.precioDolares) like '%".$criterio."%') 
                    OR 
                    (lower(c.precioColones) like '%".$criterio."%') 
                    OR 
                    (CONVERT(p.descripcion USING latin1) like '%".$criterio."%') 
                    OR 
                    (CONVERT(c.justificacion USING latin1) like '%".$criterio."%')
                  )  
                     ";
      }

    //error_log($sql);
    return find_by_sql($sql);

   }

function eliminarProveedorXProducto($idProd,$idProv){
  global $db;
  $sql = "DELETE FROM proveedores_x_productos WHERE idProd=".$idProd." AND IdProv=".$idProv."  LIMIT 1";
  $result = $db->query($sql);
  if($result && $db->affected_rows() === 1){
    return true;
  }else{
    return false;
  }

}


function actualizarDefectoPartidaProducto($idPar){
  global $db;
  $sql = "UPDATE productos SET idPar= 1 WHERE idPar=".$idPar;
  $result = $db->query($sql);
  if($result){
    return true;
  }else{
    return false;
  }  
}

function eliminarProveedoresXProductoXidProv($idProv){
  global $db;
  $sql = "DELETE FROM proveedores_x_productos WHERE IdProv=".$idProv;
  $result = $db->query($sql);
  if($result){
    return true;
  }else{
    return false;
  }
}

/*--------------------------------------------------------------*/
/* Función para obtener ordenar y buscar imagenes
/*--------------------------------------------------------------*/
function  find_all_imagenes($columna,$tipoOrdenamiento,$criterio){
   global $db;
   $sql ="SELECT * FROM media ";
   if($criterio != ""){
    $sql .= "WHERE file_name LIKE '%".$criterio."%' OR file_type LIKE '%".$criterio."%'";
   }
   $sql .= "ORDER BY ".$columna." ".$tipoOrdenamiento;
   
   return find_by_sql($sql);
   
}



?>


