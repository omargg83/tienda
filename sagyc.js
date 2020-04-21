if (Cookies.get('ticshop_x')==undefined){
  var galleta="";
  galletax(galleta);
}
else{
  var galleta=Cookies.get('ticshop_x');
  galletax(galleta);
}

function galletax(galleta){
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data: {
      "ctrl":"control",
      "galleta":galleta,
      "function":"galleta"
    },
    success: function( response ) {
      Cookies.set('ticshop_x', response);
    }
  });
}

function salir(){
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data: {
      "ctrl":"control",
      "function":"salir"
    },
    success: function( response ) {
      Cookies.remove('ticshop_x');
      var galleta="";
      galletax(galleta);
      window.location.href="/";
    }
  });
}
function carrito(id,cantidad){
  $.ajax({
    data:  {
      "ctrl":"control",
      "id":id,
      "cantidad":cantidad,
      "function":"carrito"
    },
    url:   'control_db.php',
    type:  'post',
    timeout:30000,
    beforeSend: function () {

    },
    success:  function (response) {
      var datos = JSON.parse(response);
      if (datos.error==0){
        window.location.href="/cart.php";
      }
      if(datos.error==2){
        window.location.href="/acceso.php";
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {

    }
  });
}
function borra_carrito(id){
  $.ajax({
    data:  {
      "ctrl":"control",
      "id":id,
      "function":"borra_carrito"
    },
    url:   'control_db.php',
    type:  'post',
    timeout:3000,
    beforeSend: function () {

    },
    success:  function (response) {
      window.location.href="/wish.php";
    },
    error: function(jqXHR, textStatus, errorThrown) {

    }
  });
}
function wish(id){
  console.log(id);
  $.ajax({
    data:  {
      "ctrl":"control",
      "id":id,
      "function":"wish"
    },
    url:   'control_db.php',
    type:  'post',
    timeout:3000,
    success:  function (response) {
      console.log(response);
      $("#wish_count").html(response);
    }
  });

}
function borra_wish(id){
  $.confirm({
      title: 'Lista de deseos!',
      content: '¿Desea quitar de la lista de deseos?',
      buttons: {
          Quitar: function () {
          $.ajax({
            data:  {
              "ctrl":"control",
              "id":id,
              "function":"borra_wish"
            },
            url:   'control_db.php',
            type:  'post',
            timeout:3000,
            beforeSend: function () {

            },
            success:  function (response) {
              window.location.href="/wish.php";
            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
          });
        },
        cancel: function () {

        }
      }
    });
}
function borra_carrito(id){
  $.confirm({
      title: 'Carrito de compras!',
      content: '¿Desea quitar del carrito de compras el producto seleccionado?',
      buttons: {
          Quitar: function () {
            $.ajax({
              data:  {
                "ctrl":"control",
                "id":id,
                "function":"borra_carrito"
              },
              url:   'control_db.php',
              type:  'post',
              timeout:3000,
              beforeSend: function () {

              },
              success:  function (response) {
                window.location.href="/cart.php";
              },
              error: function(jqXHR, textStatus, errorThrown) {

              }
            });
          },
          cancel: function () {

          }
      }
  });


}
function buscar_prod(){
  window.location.href="/buscar/"+$("#bucar_text").val();
}
function buscar_prod2(){
  window.location.href="/buscar/"+$("#bucar_textm").val();
}
function estrella(idproducto){
  $.confirm({
      title: 'Calificación',
      content: '¿Desea enviar sus comentarios?',
      buttons: {
        Enviar: function () {
          var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
          if (ratingValue){
            estrella=ratingValue;
          }
          else{
            estrella=0;
          }
          var texto=$("#texto").val();
          $.ajax({
            data:  {
              "ctrl":"control",
              "estrella":estrella,
              "texto":texto,
              "idproducto":idproducto,
              "function":"estrella"
            },
            url:   'control_db.php',
            type:  'post',
            timeout:3000,
            beforeSend: function () {

            },
            success:  function (response) {
              window.location.href="/product.php?id="+idproducto;
            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
          });

        },
        Cancelar: function () {

        }
      }
    });

}
function factura_act(){
  if($("#factura").is(':checked')){
    $( "#factura_div" ).show();
  }
  else{
    $( "#factura_div" ).hide();
  }

}
function cupon_agrega(pedido){
  var cupon=$("#cupon").val();
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data: {
      "cupon":cupon,
      "idpedido":pedido,
      "ctrl":"control",
      "function":"cupon_busca"
    },
    success: function( response ) {
      console.log(response);
      var datos = JSON.parse(response);
      if (datos.error==0){
        Swal.fire({
            type: 'success',
            title: "Se agregó correctamente",
            showConfirmButton: false,
            timer: 1000
        });
        window.location.href="/pago.php?idpedido="+pedido;
      }
      else{
        Swal.fire({
            type: 'error',
            title: datos.terror,
            showConfirmButton: false,
            timer: 1000
        });
      }
    }
  });
}
function elimina_cupon(id,idpedido){
  $.confirm({
      title: 'Cupon',
      content: '¿Desea eliminar el cupón?',
      buttons: {
        Eliminar: function () {
          $.ajax({
            data:  {
              "ctrl":"control",
              "id":id,
              "idpedido":idpedido,
              "function":"elimina_cupon"
            },
            url:   'control_db.php',
            type:  'post',
            timeout:3000,
            beforeSend: function () {

            },
            success:  function (response) {
              window.location.href="/pago.php?idpedido="+idpedido;
            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
          });

        },
        Cancelar: function () {

        }
      }
    });
}

function correctCaptcha(){
  $("#submit_rec").prop('disabled', false);
 }
function captcha_fail(){
  $("#submit_rec").prop('disabled', true);
}
function select_dir(){
  var dir_fin=$("#dir_fin").val();
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data:  {
      "dir_fin":dir_fin,
      "ctrl":"control",
      "function":"dir_update"
    },
    success: function( response ) {
      var datos = JSON.parse(response);
      if(datos.error==0){
        $("#direccion1").val(datos.direccion1);
        $("#entrecalles").val(datos.entrecalles);
        $("#numero").val(datos.numero);
        $("#colonia").val(datos.colonia);
        $("#ciudad").val(datos.ciudad);
        $("#cp").val(datos.cp);
        $("#pais").val(datos.pais);
        $("#estado").val(datos.estado);
      }
    }
  });
}

$(document).on('submit','#acceso',function(e){
  e.preventDefault();
  var userAcceso=document.getElementById("userAcceso").value;
  var passAcceso=$.md5(document.getElementById("passAcceso").value);
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data: {
      "ctrl":"control",
      "function":"acceso",
      "userAcceso":userAcceso,
      "passAcceso":passAcceso
    },
    success: function( response ) {
      console.log(response);
      var datos = JSON.parse(response);
      if (datos.acceso==1){
        Cookies.set('ticshop_x', datos.galleta);
        window.location.href="/";
      }
      else{
        Swal.fire({
            type: 'error',
            title: 'Usuario o contraseña incorrecta',
            showConfirmButton: false,
            timer: 1000
        });
      }
    }
  });
});
$(document).on('submit','#registro',function(e){
  e.preventDefault();

  var pass=document.getElementById("pass").value;
  var pass2=document.getElementById("pass2").value;
  var dataString = $(this).serialize()+"&function=registro&ctrl=control";
  if(pass==pass2){
    $.ajax({
      url: "control_db.php",
      type: "POST",
      data:  dataString,
      success: function( response ) {
        var datos = JSON.parse(response);
        if (datos.error==0){
          Swal.fire({
              type: 'success',
              title: 'Se registro correctamente',
              showConfirmButton: false,
              timer: 2000
          });
          window.location.href="/";
        }
        else{
          Swal.fire({
              type: 'error',
              title: datos.terror,
              showConfirmButton: false,
              timer: 1000
          });
        }
      }
    });
  }
  else{
    Swal.fire({
        type: 'error',
        title: 'Contraseña no coincide',
        showConfirmButton: false,
        timer: 1000
    });
  }
});
$(document).on('submit','#recuperar',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=recuperar&ctrl=control";
  var mail=document.getElementById("mail").value;
  if(mail.length>0){
    $.ajax({
      url: "control_db.php",
      type: "POST",
      data:  dataString,
      success: function( response ) {
        console.log(response);
        var datos = JSON.parse(response);
        if (datos.error==0){
          Swal.fire({
              type: 'success',
              title: 'Se nofiticó correctamente',
              showConfirmButton: false,
              timer: 2000
          });
          //window.location.href="index.php";
        }
        else{
          Swal.fire({
              type: 'error',
              title: datos.terror,
              showConfirmButton: false,
              timer: 1000
          });
        }
      }
    });
  }
  else{
    Swal.fire({
        type: 'error',
        title: 'Contraseña no coincide',
        showConfirmButton: false,
        timer: 1000
    });
  }
});
$(document).on('submit','#datos',function(e){
  e.preventDefault();
  var nombre=document.getElementById("nombre").value;
  var apellido=document.getElementById("apellido").value;
  var rfc=document.getElementById("rfc").value;
  var cfdi=document.getElementById("cfdi").value;
  var direccion1=document.getElementById("direccion1").value;
  var entrecalles=document.getElementById("entrecalles").value;
  var numero=document.getElementById("numero").value;
  var colonia=document.getElementById("colonia").value;
  var ciudad=document.getElementById("ciudad").value;
  var cp=document.getElementById("cp").value;
  var pais=document.getElementById("pais").value;
  var estado=document.getElementById("estado").value;
  var telefono=document.getElementById("telefono").value;

  $.ajax({
    url: "control_db.php",
    type: "POST",
    data: {
      "ctrl":"control",
      "function":"datos_update",
      "nombre":nombre,
      "apellido":apellido,
      "rfc":rfc,
      "cfdi":cfdi,
      "direccion1":direccion1,
      "entrecalles":entrecalles,
      "numero":numero,
      "colonia":colonia,
      "ciudad":ciudad,
      "cp":cp,
      "pais":pais,
      "estado":estado,
      "telefono":telefono
    },
    success: function( response ) {
      console.log(response);
      var datos = JSON.parse(response);
      if (datos.error==0){
        Swal.fire({
            type: 'success',
            title: 'Se actualizó correctamente',
            showConfirmButton: false,
            timer: 1000
        });
      }
      else{
        Swal.fire({
            type: 'error',
            title: 'error'+datos.terror,
            showConfirmButton: false,
            timer: 1000
        });
      }
    }
  });
});
$(document).on('submit','#direccion',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=guardar_direccion&ctrl=control";

  $.ajax({
    url: "control_db.php",
    type: "POST",
    data:  dataString,
    success: function( response ) {
      console.log(response);
      var datos = JSON.parse(response);
      if (datos.error==0){
        Swal.fire({
            type: 'success',
            title: 'Se actualizó correctamente',
            showConfirmButton: false,
            timer: 1000
        });
      }
      else{
        Swal.fire({
            type: 'error',
            title: 'error'+datos.terror,
            showConfirmButton: false,
            timer: 1000
        });
      }
    }
  });
});
$(document).on('submit','#pedido',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=pedido_generar&ctrl=control";
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data:  dataString,
    success: function( response ) {
      console.log(response);
      var datos = JSON.parse(response);
      if (datos.error==0){
        window.location.href="/pago.php?idpedido="+datos.id;
        Swal.fire({
            type: 'success',
            title: 'Se generó el pedido correctamente',
            showConfirmButton: false,
            timer: 1000
        });
      }
      else{
        Swal.fire({
            type: 'error',
            title: 'Error:'+datos.terror,
            showConfirmButton: false,
            timer: 1000
        });
      }
    }
  });
});
$(document).on('submit','#contact_form',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=contacto&ctrl=control";
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data:  dataString,
    success: function( response ) {
      var datos = JSON.parse(response);
      if (datos.error==0){
        Swal.fire({
            type: 'success',
            title: 'Se envío correctamente el mensaje',
            showConfirmButton: false,
            timer: 1000
        });
      }
      else{
        Swal.fire({
            type: 'error',
            title: 'error'+datos.terror,
            showConfirmButton: false,
            timer: 1000
        });
      }
    }
  });
});
$(document).on('submit','#cotizacion_form',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=mayoreo&ctrl=control";
  $.ajax({
    url: "/control_db.php",
    type: "POST",
    data:  dataString,
    success: function( response ) {
      console.log(response);
      var datos = JSON.parse(response);
      if (datos.error==0){
        Swal.fire({
            type: 'success',
            title: 'Se envío correctamente el mensaje',
            showConfirmButton: false,
            timer: 1000
        });
      }
      else{
        Swal.fire({
            type: 'error',
            title: 'error'+datos.terror,
            showConfirmButton: false,
            timer: 1000
        });
      }
      $('#exampleModalCenter').modal('hide');
    }
  });
});
$(document).on('submit','#carrito_form',function(e){
  e.preventDefault();

  var cantidad=$("#quantity_input").val();
  var id=$("#id").val();
  $.ajax({
    data:  {
      "ctrl":"control",
      "id":id,
      "cantidad":cantidad,
      "function":"carrito"
    },
    url:   'control_db.php',
    type:  'post',
    timeout:3000,
    beforeSend: function () {

    },
    success:  function (response) {
      var datos = JSON.parse(response);
      if (datos.error==0){
        window.location.href="/cart.php";
      }
      if (datos.error==1){
        Swal.fire({
            type: 'error',
            title: datos.terror,
            showConfirmButton: false,
            timer: 1000
        });
      }
      if(datos.error==2){
        window.location.href="/acceso.php";
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {

    }
  });

});


    function initPriceSlider() {
    	if($("#slider-range").length){

    	$("#slider-range").slider({
				range: true,
				min: 0,
				max: 1000,
				values: [ 0, 580 ],
				slide: function( event, ui )
				{
          alert("entra");
					$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
				}
			});

			$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) + " - $" + $( "#slider-range" ).slider( "values", 1 ) );

			$('.ui-slider-handle').on('mouseup', function(){
				$('.product_grid').isotope({
		            filter: function()
		            {
		            	var priceRange = $('#amount').val();
			        	var priceMin = parseFloat(priceRange.split('-')[0].replace('$', ''));
			        	var priceMax = parseFloat(priceRange.split('-')[1].replace('$', ''));
			        	var itemPrice = $(this).find('.product_price').clone().children().remove().end().text().replace( '$', '' );

			        	return (itemPrice > priceMin) && (itemPrice < priceMax);
		            },
		            animationOptions: {
		                duration: 750,
		                easing: 'linear',
		                queue: false
		            }
		        });
			});


    	}
    }
