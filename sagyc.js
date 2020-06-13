if (Cookies.get('ticshop_x')==undefined){
  var galleta="";
  galletax(galleta);
}
else{
  var galleta=Cookies.get('ticshop_x');
  galletax(galleta);
}

$(function(){


});

function galletax(galleta){
  $.ajax({
    url: "/control_db.php",
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
    url: "/control_db.php",
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
    url:   '/control_db.php',
    type:  'post',
    timeout:30000,
    beforeSend: function () {

    },
    success:  function (response) {
      var datos = JSON.parse(response);
      if (datos.error==0){
        window.location.href="/carrito/";
      }
      if(datos.error==2){
        window.location.href="/acceso";
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
    url:   '/control_db.php',
    type:  'post',
    timeout:3000,
    beforeSend: function () {

    },
    success:  function (response) {
      window.location.href="/carrito/";
    },
    error: function(jqXHR, textStatus, errorThrown) {

    }
  });
}
function wish(id){
  $.ajax({
    data:  {
      "ctrl":"control",
      "id":id,
      "function":"wish"
    },
    url:   '/control_db.php',
    type:  'post',
    timeout:3000,
    success:  function (response) {
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
            url:   '/control_db.php',
            type:  'post',
            timeout:3000,
            beforeSend: function () {

            },
            success:  function (response) {
              window.location.href="/deseos/";
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
              url:   '/control_db.php',
              type:  'post',
              timeout:3000,
              beforeSend: function () {

              },
              success:  function (response) {
                window.location.href="/carrito/";
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
function estrella(idproducto, clave){
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
            url:   '/control_db.php',
            type:  'post',
            timeout:3000,
            beforeSend: function () {

            },
            success:  function (response) {
              window.location.href="/producto/"+clave;
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
    url: "/control_db.php",
    type: "POST",
    data: {
      "cupon":cupon,
      "idpedido":pedido,
      "ctrl":"control",
      "function":"cupon_busca"
    },
    success: function( response ) {
      var datos = JSON.parse(response);
      if (datos.error==0){
        Swal.fire({
            type: 'success',
            title: "Se agregó correctamente",
            showConfirmButton: false,
            timer: 1000
        });
        window.location.href="/pago/"+pedido;
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
            url:   '/control_db.php',
            type:  'post',
            timeout:3000,
            beforeSend: function () {

            },
            success:  function (response) {
              window.location.href="/pago/"+idpedido;
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
  if(dir_fin=='nueva'){
    $("#direccion1").val("");
    $("#entrecalles").val("");
    $("#numero").val("");
    $("#colonia").val("");
    $("#ciudad").val("");
    $("#cp").val("");
    $("#pais").val("");
    $("#estado").val("");
  }
  else{
    $.ajax({
      url: "/control_db.php",
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

}
function select_factdir(){
  var dir_fin=$("#dir_factfin").val();

  if(dir_fin=='0'){
    $("#fact_direccion1").val("");
    $("#fact_entrecalles").val("");
    $("#fact_numero").val("");
    $("#fact_colonia").val("");
    $("#fact_ciudad").val("");
    $("#fact_cp").val("");
    $("#fact_pais").val("");
    $("#fact_estado").val("");
    $("#dirfactura_div").hide();

    $("#fact_direccion1").removeAttr("required");
    $("#fact_colonia").removeAttr("required");
    $("#fact_ciudad").removeAttr("required");
    $("#fact_cp").removeAttr("required");
    $("#fact_pais").removeAttr("required");
    $("#fact_estado").removeAttr("required");
  }
  else if(dir_fin=='nueva'){

    $("#fact_direccion1").prop("required", "true");
    $("#fact_colonia").prop("required", "true");
    $("#fact_ciudad").prop("required", "true");
    $("#fact_cp").prop("required", "true");
    $("#fact_pais").prop("required", "true");
    $("#fact_estado").prop("required", "true");

    $("#fact_direccion1").val("");
    $("#fact_entrecalles").val("");
    $("#fact_numero").val("");
    $("#fact_colonia").val("");
    $("#fact_ciudad").val("");
    $("#fact_cp").val("");
    $("#fact_pais").val("");
    $("#fact_estado").val("");
    $("#dirfactura_div").show();
  }
  else{
    $.ajax({
      url: "/control_db.php",
      type: "POST",
      data:  {
        "dir_fin":dir_fin,
        "ctrl":"control",
        "function":"dir_update"
      },
      success: function( response ) {
        var datos = JSON.parse(response);
        if(datos.error==0){
          $("#fact_direccion1").prop("required", "true");
          $("#fact_colonia").prop("required", "true");
          $("#fact_ciudad").prop("required", "true");
          $("#fact_cp").prop("required", "true");
          $("#fact_pais").prop("required", "true");
          $("#fact_estado").prop("required", "true");

          $("#fact_direccion1").val(datos.direccion1);
          $("#fact_entrecalles").val(datos.entrecalles);
          $("#fact_numero").val(datos.numero);
          $("#fact_colonia").val(datos.colonia);
          $("#fact_ciudad").val(datos.ciudad);
          $("#fact_cp").val(datos.cp);
          $("#fact_pais").val(datos.pais);
          $("#fact_estado").val(datos.estado);
          $("#dirfactura_div").show();
        }
      }
    });
  }

}

$(document).on('submit','#logintix',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=acceso&ctrl=control";
  console.log(dataString);
});


$(document).on('submit','#registro',function(e){
  e.preventDefault();

  var pass=document.getElementById("pass").value;
  var pass2=document.getElementById("pass2").value;
  var dataString = $(this).serialize()+"&function=registro&ctrl=control";
  if(pass==pass2){
    $.ajax({
      url: "/control_db.php",
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
      url: "/control_db.php",
      type: "POST",
      data:  dataString,
      success: function( response ) {
        var datos = JSON.parse(response);
        if (datos.error==0){
          Swal.fire({
              type: 'success',
              title: 'Se notificó correctamente',
              showConfirmButton: false,
              timer: 2000
            }).then((result) => {
            window.location.href="/acceso/";
          });
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
    url: "/control_db.php",
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
    url: "/control_db.php",
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
$(document).on('submit','#pedido_form',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=pedido_generar&ctrl=control";
  $.confirm({
      title: 'Realizar pedido',
      content: '¿Esta seguro que todos sus datos son correctos?',
      buttons: {
          Aceptar: function () {
            $.ajax({
              url: "/control_db.php",
              type: "POST",
              data:  dataString,
              beforeSend: function () {
                Swal.fire({
                    type: 'info',
                    title: 'Procesando',
                    showConfirmButton: false
                });
              },
              success: function( response ) {
                var datos = JSON.parse(response);
                if (datos.error==0){
                  window.location.href="/pago/"+datos.id;
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
        },
        cancelar: function () {

        }
      }
    });
});
$(document).on('submit','#contact_form',function(e){
  e.preventDefault();
  var dataString = $(this).serialize()+"&function=contacto&ctrl=control";
  $.ajax({
    url: "/control_db.php",
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
    url:   '/control_db.php',
    type:  'post',
    timeout:3000,
    beforeSend: function () {

    },
    success:  function (response) {
      var datos = JSON.parse(response);
      if (datos.error==0){
        window.location.href="/carrito/";
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
        window.location.href="/acceso/";
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {

    }
  });

});
$('.input-number').on('input', function () {
    this.value = this.value.replace(/[^0-9]/g,'');
});
