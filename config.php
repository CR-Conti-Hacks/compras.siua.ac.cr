<?php
	ob_start();
	date_default_timezone_set('America/Costa_Rica');
  	$page_title = 'Configuración sistema';
  	require_once('includes/load.php');
  	page_require_level(1);
  	include_once('layouts/header.php');	
  	$configuracion = find_all('configuracion');

?>

<?php

 if(isset($_POST['mod_config'])){

 	$req_fields = array('fechaInicioCompras','fechaFinCompras','tipoCambioDolar' );
 	validate_fields($req_fields);
 	if(empty($errors)){
 		$p_fechaInicioCompras  	= remove_junk($db->escape($_POST['fechaInicioCompras']));
 		$p_fechaFinCompras  	= remove_junk($db->escape($_POST['fechaFinCompras']));
 		$p_tipoCambioDolar  	= remove_junk($db->escape($_POST['tipoCambioDolar']));
 		$query = 'UPDATE configuracion SET ';
 		$query .= ' fechaInicioCompras = "'.$p_fechaInicioCompras.'",';
		$query .= ' fechaFinCompras = "'.$p_fechaFinCompras.'",';
		$query .= ' tipoCambioDolar = '.$p_tipoCambioDolar;
		$query .= ' WHERE id = 1';
		if($db->query($query)){
	       	$session->msg('s',"Configuración actualizada correctamente. ");
	       	redirect('config.php', false);

	    } else {
	       $session->msg('d','Ocurrió un error el actualizar la configuración.');
	       redirect('config.php', false);
	    }
 	}else{
 		echo "e3";
 		$session->msg("d", $errors);
     	redirect('config.php',false);
 	}

 }
?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<form method="post" action="config.php" class="clearfix">
	<div class="panel panel-default">
	  <div class="panel-heading"><i class="glyphicon glyphicon-cog"></i> &nbsp;Configuración del sistema</div>
	  <div class="panel-body">
	   	<div class="row">
			<div class="col col-xs-12 col-lg-6">
				<div class="form-group">
				    <label for="fechaInicio">Fecha de Inicio</label>
				    <input 
				    	type="text" 
				    	class="form-control" 
				    	aria-describedby="ayudaFechaInicio"
				    	placeholder="Fecha de inicio"

				    	id="fechaInicioCompras"   
				    	name="fechaInicioCompras"
				    	value="<?=$configuracion[0]['fechaInicioCompras']?>"
				    >
				    <small id="ayudaFechaInicio" class="form-text text-muted">Seleccione le fecha de inicio para solicitar compras.</small>
				</div>
			</div>
			<div class="col col-xs-12 col-lg-6">
				<div class="form-group">
				    <label for="fechaFin">Fecha de Fin</label>
				    <input 
				    	type="text" 
				    	class="form-control" 
						aria-describedby="ayudaFechaFin"
						placeholder="Fecha de fin"

				    	id="fechaFinCompras"   
				    	name="fechaFinCompras"
				    	value="<?=$configuracion[0]['fechaFinCompras']?>">
				    
				    <small id="ayudaFechaFin" class="form-text text-muted">Seleccione le fecha de fin para solicitar compras.</small>
				</div>
			</div>
			<div class="col col-xs-12 col-lg-6">
				<div class="form-group">
	    			<label for="tipoCambioDolar">Tipo de cambio dólar</label>
				    <input 
				    	type="text" 
				    	class="form-control" 
				    	id="tipoCambioDolar" 
				    	name="tipoCambioDolar" 
				    	placeholder="Tipo cambio dólar" 
				    	value="<?=$configuracion[0]['tipoCambioDolar']?>">
				</div>
			</div>
			<div class="col col-xs-12" style="text-align: center;">
				<button type="submit" name="mod_config" class="btn btn-primary">Guardar</button>
			</div>

	   	</div>
	  </div>
	</div>
</form>

<?php include_once('layouts/footer.php'); ?>
<script>
	$('#fechaInicioCompras').datepicker({
	    format: "yyyy-mm-dd",
	    autoclose: true,
	    clearBtn:true,
	    daysOfWeekDisabled: [0],
	    language: "es",

	});
	$('#fechaFinCompras').datepicker({
	    format: "yyyy-mm-dd",
	    autoclose: true,
	    clearBtn:true,
	    daysOfWeekDisabled: [0],
	    language: "es",

	});
</script>
<?php
ob_end_flush();
?>