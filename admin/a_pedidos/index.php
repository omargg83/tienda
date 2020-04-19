<?php
  require_once("db_.php");
  $_SESSION['nivel_captura']=1;
 ?>

 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fas fa-shopping-basket"></i>Pedidos</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
          <div class="input-group  mr-sm-2">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  id='buscar' onkeyup='Javascript: if (event.keyCode==13) buscar_pedido()'>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick='buscar_pedido()'><i class='fas fa-search'></i></button>
            </div>
          </div>
				</div>

 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_pedidos/editar'><i class="fas fa-folder-plus"></i><span>Nuevo</span></a></li>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod' data-lugar='a_pedidos/lista'><i class="fas fa-list"></i><span>Lista</span></a></li>

      </li>

 			</ul>
 		</div>
 	  </div>
 	</nav>

<?php

   echo "<div id='trabajo' style='margin-top:5px;'>";
    include 'lista.php';
   echo "</div>";

 ?>
<script type="text/javascript">

  function buscar_pedido(){
    var buscar = $("#buscar").val();
    $.ajax({
      data:  {
        "buscar":buscar
      },
      url:   'a_pedidos/lista.php',
      type:  'post',
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
  function buscar_cliente(){
  	var texto=$("#prod_venta").val();
  	var idcliente=$("#idcliente").val();
  	var idpedido=$("#idpedido").val();
  	if(texto.length>=-1){
  		$.ajax({
  			data:  {
  				"texto":texto,
  				"idcliente":idcliente,
  				"idpedido":idpedido,
  				"function":"busca_cliente"
  			},
  			url:   "a_pedidos/db_.php",
  			type:  'post',
  			beforeSend: function () {
  				$("#resultadosx").html("buscando...");
  			},
  			success:  function (response) {
  				$("#resultadosx").html(response);
  				$("#prod_venta").val();
  			}
  		});
  	}
  }
  function buscar_prodpedido(){
  	var texto=$("#prod_venta").val();
  	var idproducto=$("#idproducto").val();
  	var idpedido=$("#idpedido").val();
  	if(texto.length>=-1){
  		$.ajax({
  			data:  {
  				"texto":texto,
  				"idproducto":idproducto,
  				"idpedido":idpedido,
  				"function":"busca_producto"
  			},
  			url:   "a_pedidos/db_.php",
  			type:  'post',
  			beforeSend: function () {
  				$("#resultadosx").html("buscando...");
  			},
  			success:  function (response) {
  				$("#resultadosx").html(response);
  				$("#prod_venta").val();
  			}
  		});
  	}
  }
  function cliente_add(idcliente,idpedido){
    $.confirm({
      title: 'Cliente',
      content: '¿Desea agregar el cliente seleccionado?',
      buttons: {
        Aceptar: function () {
          $.ajax({
            data:  {
              "idcliente":idcliente,
              "idpedido":idpedido,
              "function":"agrega_cliente"
            },
            url:   "a_pedidos/db_.php",
            type:  'post',
            success:  function (response) {
              console.log(response);
              var datos = JSON.parse(response);
              if (datos.error==0){
                $.ajax({
                  data:  {
                    "id":datos.id
                  },
                  url:   'a_pedidos/editar.php',
                  type:  'post',
                  success:  function (response) {
                    $("#trabajo").html(response);
                  }
                });
                Swal.fire({
                  type: 'success',
                  title: "Se agregó correctamente",
                  showConfirmButton: false,
                  timer: 1000
                });
                $('#myModal').modal('hide');
              }
              else{
                $.alert(datos.terror);
              }
            }
          });
        },
        Cancelar: function () {
          $.alert('Canceled!');
        }
      }
    });
  }
  function prod_add(id,idpedido){
    $.ajax({
      data:  {
        "id":id,
        "idpedido":idpedido
      },
      url:   'a_pedidos/form_prodver.php',
      type:  'post',
      beforeSend: function () {
        $("#cargando").addClass("is-active");
      },
      success:  function (response) {
        $("#resultadosx").html(response);
        $("#cargando").removeClass("is-active");
      }
    });
  }

  function buscar_cupon(){
    var texto=$("#prod_venta").val();
  	var idpedido=$("#idpedido").val();
  	if(texto.length>=-1){
  		$.ajax({
  			data:  {
  				"texto":texto,
  				"idpedido":idpedido,
  				"function":"busca_cupon"
  			},
  			url:   "a_pedidos/db_.php",
  			type:  'post',
  			beforeSend: function () {
  				$("#resultadosx").html("buscando...");
  			},
  			success:  function (response) {
  				$("#resultadosx").html(response);
  				$("#prod_venta").val();
  			}
  		});
  	}
  }
</script>
