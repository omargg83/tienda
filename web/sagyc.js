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
      var datos = JSON.parse(response);
      if (datos.acceso==1){
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
function salir(){
  $.ajax({
    url: "control_db.php",
    type: "POST",
    data: {
      "ctrl":"control",
      "function":"salir"
    },
    success: function( response ) {
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
      console.log(response);
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
