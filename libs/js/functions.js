
function sugerencia() {

     $('#busquedaProducto').keyup(function(e) {

         var formData = {
             'nombre' : $('#busquedaProducto').val()
         };

         if(formData['nombre'].length >= 1){

           // process the form
           $.ajax({
               type        : 'POST',
               url         : 'ajax.php',
               data        : formData,
               dataType    : 'json',
               encode      : true
           })
               .done(function(data) {
                   //console.log(data);
                   $('#result').html(data).fadeIn();
                   $('#result li span').click(function() {

                     $('#busquedaProducto').val($(this).text());
                     $('#result').fadeOut(500);

                   });

                   $("#busquedaProducto").blur(function(){
                     $("#result").fadeOut(500);
                   });

               });

         } else {

           $("#result").hide();

         };

         e.preventDefault();
     });

 }
  $('#formBusqueda').submit(function(e) {



      var formData = {
          'producto' : $('#busquedaProducto').val()
      };
        // process the form
        $.ajax({
            type        : 'POST',
            url         : 'ajax.php',
            data        : formData,
            dataType    : 'json',
            encode      : true
        })
            .done(function(data) {
                //console.log(data);
                $('#informacionProducto').html(data).show();
                


                window.precioColones = IMask(document.querySelector('#precioColones'), {
                  mask: Number,
                  scale: 2,
                  signed: false,
                  thousandsSeparator: '',
                  padFractionalZeros: true,
                  normalizeZeros: true,
                  radix: '.',
                  mapToRadix: ['.']
                });
                window.precioDolares = IMask(document.querySelector('#precioDolares'), {
                  mask: Number,
                  scale: 2,
                  signed: false,
                  thousandsSeparator: '',
                  padFractionalZeros: true,
                  normalizeZeros: true,
                  radix: '.',
                  mapToRadix: ['.']
                });

                window.totalColones = IMask(document.querySelector('#totalColones'), {
                  mask: Number,
                  scale: 2,
                  signed: false,
                  thousandsSeparator: '',
                  padFractionalZeros: true,
                  normalizeZeros: true,
                  radix: '.',
                  mapToRadix: ['.']
                });
                window.totalDolares = IMask(document.querySelector('#totalDolares'), {
                  mask: Number,
                  scale: 2,
                  signed: false,
                  thousandsSeparator: '',
                  padFractionalZeros: true,
                  normalizeZeros: true,
                  radix: '.',
                  mapToRadix: ['.']
                });

                /*Puede ser cualquera dolares o colones solo para calculo inicial*/
                calculaPrecios('precioColones');


                /*blur dolares*/
                document.querySelector("#precioDolares").addEventListener("blur", function( event ) {
                   calculaPrecios('precioDolares');
                }, true);

                /* blur colones */
                document.querySelector("#precioColones").addEventListener("blur", function( event ) {
                   calculaPrecios('precioColones');
                }, true);
                /* blur colones */
                document.querySelector("#cantidadSolicitadaProducto").addEventListener("blur", function( event ) {
                   calculaPrecios('cantidadSolicitadaProducto');
                }, true);

                //$('.datePicker').datepicker('update', new Date());

            }).fail(function() {
                $('#informacionProducto').html(data).show();
            });
      e.preventDefault();
  });

function calculaPrecios (tipo){

    var resultado = "";
    /*Precio Colones*/
    if(
        (document.querySelector("#precioColones").value == "" || document.querySelector("#precioColones").value == "0") ||
        (document.querySelector("#precioDolares").value == "" || document.querySelector("#precioDolares").value == "0") ||
        (document.querySelector("#cantidadSolicitadaProducto").value == "")
      ){
          document.querySelector("#precioDolares").value = "0.00";  
          document.querySelector("#precioColones").value = "0.00";  
          document.querySelector("#totalColones").value = "0.00"
          document.querySelector("#totalDolares").value = "0.00"

          window.precioDolares.updateValue();
          window.precioColones.updateValue();
          window.totalColones.updateValue();
          window.totalDolares.updateValue();
    }else if (tipo =="precioColones"){
      /*Calcular total colones*/
      resultado = parseFloat(document.querySelector("#precioColones").value) * parseFloat(document.querySelector("#cantidadSolicitadaProducto").value);
      document.querySelector("#totalColones").value = resultado.toFixed(2);
      window.totalColones.updateValue();

      /*Calcular precio dolares*/
      resultado = parseFloat(document.querySelector("#precioColones").value) / parseFloat(document.querySelector("#tipoCambioDolar").value);
      document.querySelector("#precioDolares").value = resultado.toFixed(2);
      window.precioDolares.updateValue(); 

      /*Calcular total dolares*/
      resultado = parseFloat(document.querySelector("#precioDolares").value) * parseFloat(document.querySelector("#cantidadSolicitadaProducto").value);
      document.querySelector("#totalDolares").value = resultado.toFixed(2);
      window.totalDolares.updateValue(); 

    }else if (tipo =="precioDolares"){
      
      /*Calcular total dolares*/
      resultado = parseFloat(document.querySelector("#precioDolares").value) * parseFloat(document.querySelector("#cantidadSolicitadaProducto").value);
      document.querySelector("#totalDolares").value = resultado.toFixed(2);
      window.totalDolares.updateValue(); 

      resultado = parseFloat(document.querySelector("#precioDolares").value) * parseFloat(document.querySelector("#tipoCambioDolar").value);
      document.querySelector("#precioColones").value = resultado.toFixed(2);
      window.precioColones.updateValue();

      /*Calcular total colones*/
      resultado = parseFloat(document.querySelector("#precioColones").value) * parseFloat(document.querySelector("#cantidadSolicitadaProducto").value);
      document.querySelector("#totalColones").value = resultado.toFixed(2);
      window.totalColones.updateValue();

    }else if (tipo =="cantidadSolicitadaProducto"){
      
      /*Calcular total dolares*/
      resultado = parseFloat(document.querySelector("#precioDolares").value) * parseFloat(document.querySelector("#cantidadSolicitadaProducto").value);
      document.querySelector("#totalDolares").value = resultado.toFixed(2);
      window.totalDolares.updateValue(); 

      /*Calcular total colones*/
      resultado = parseFloat(document.querySelector("#precioColones").value) * parseFloat(document.querySelector("#cantidadSolicitadaProducto").value);
      document.querySelector("#totalColones").value = resultado.toFixed(2);
      window.totalColones.updateValue();

    }

}

  $(document).ready(function() {

    //tooltip
    $('[data-toggle="tooltip"]').tooltip();

    $('.submenu-toggle').click(function () {
       $(this).parent().children('ul.submenu').toggle(200);
    });
    //sugerencia for finding product names
    sugerencia();
    // Callculate total ammont
 

  });
