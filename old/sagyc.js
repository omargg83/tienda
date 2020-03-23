
$(window).on('hashchange',function(){
  loadContent(location.hash.slice(1));
});
var url=window.location.href;
var hash=url.substring(url.indexOf("#")+1);

if(hash===url || hash===''){
  hash='escritorio/dashboard';
}
function loadContent(hash){
  $("#cargando").addClass("is-active");
  var id=$(this).attr('id');
  if(hash===''){
    hash= 'escritorio/dashboard';
  }
  $('html, body').animate({strollTop:0},'600','swing');

  var destino=hash + '.php';
  $.ajax({
    url: destino,
    type: "POST",
    timeout:30000,
    beforeSend: function () {
      $("#contenido").html("<div class='container' style='background-color:white; width:300px'><center><img src='img/carga.gif' width='300px'></center></div>");
    },
    success:  function (response) {
      $("#contenido").html(response);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      if(textStatus==="timeout") {
        $("#contenido").html("<div class='container' style='background-color:white; width:300px'><center><img src='img/giphy.gif' width='300px'></center></div><br><center><div class='alert alert-danger' role='alert'>Ocurrio un error intente de nuevo en unos minutos, vuelva a entrar o presione ctrl + F5, para reintentar</div></center> ");
      }
    }
  });
  $("#cargando").removeClass("is-active");
}


$(document).on("click",".product-item",function(e){
  e.preventDefault();
  var id = $(this).data('id');
  window.location.href = "product.php?id="+id;
});
