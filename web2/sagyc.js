
if (Cookies.get('ticshop_x')==undefined){
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data: {
      "ctrl":"control",
      "galleta":"",
      "function":"galleta"
    },
    success: function( response ) {
      Cookies.set('ticshop_x', response);
    }
  });
}
else{
  var galleta=Cookies.get('ticshop_x');
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
      window.location.href="index.php";
    }
  });
}
function carrito(id){
  $.ajax({
    data:  {
      "ctrl":"control",
      "id":id,
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
        window.location.href="cart.php";
      }
      if(datos.error==2){
        window.location.href="acceso.php";
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
      window.location.href="wish.php";
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
    url:   'control_db.php',
    type:  'post',
    timeout:3000,
    success:  function (response) {
      $("#wish_count").html(response);
    }
  });

}
function borra_wish(id){
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
      window.location.href="wish.php";
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
      window.location.href="cart.php";
    },
    error: function(jqXHR, textStatus, errorThrown) {

    }
  });
}

function buscar_prod(){
  window.location.href="busca.php?texto="+$("#bucar_text").val();
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
        window.location.href="cart.php";
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
  var nombre=document.getElementById("nombre").value;
  var apellido=document.getElementById("apellido").value;
  var correo=document.getElementById("correo").value;
  var pass=document.getElementById("pass").value;
  var pass2=document.getElementById("pass2").value;
  if(pass==pass2){
    $.ajax({
      url: "control_db.php",
      type: "POST",
      data: {
        "ctrl":"control",
        "function":"registro",
        "nombre":nombre,
        "apellido":apellido,
        "correo":correo,
        "pass":pass,
        "pass2":pass2
      },
      success: function( response ) {
        window.location.href="index.php";
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
  var direccion2=document.getElementById("direccion2").value;
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
      "direccion2":direccion2,
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
    url: "control_db.php",
    type: "POST",
    data:  dataString,
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