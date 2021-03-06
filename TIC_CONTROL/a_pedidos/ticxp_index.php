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
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod2' data-lugar='a_pedidos/lista2'><i class="fas fa-list"></i><span>Pedidos CT</span></a></li>

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
  function factura_datos(){
    if($("#factura").val()=="1"){
      $( "#factura_div" ).show();
    }
    else{
      $( "#factura_div" ).hide();
    }
  }
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
    var cantidad=$("#cantidad_"+id).val();
    $.confirm({
      title: 'Producto',
      content: '¿Desea agregar el producto seleccionado?',
      buttons: {
        Aceptar: function () {
          $.ajax({
            data:  {
              "id":id,
              "idpedido":idpedido,
              "cantidad":cantidad,
              "function":"producto_add"
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
  function cupon_agrega(idcupon,idpedido){
    $.ajax({
      url:  "a_pedidos/db_.php",
      type: "POST",
      data: {
        "idcupon":idcupon,
        "idpedido":idpedido,
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

          $.ajax({
            data:  {
              "id":idpedido
            },
            url:   'a_pedidos/editar.php',
            type:  'post',
            success:  function (response) {
              $("#trabajo").html(response);
            }
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
  function elimina_cuadmin(id,idpedido){
    $.confirm({
        title: 'Cupon',
        content: '¿Desea eliminar el cupón?',
        buttons: {
          Eliminar: function () {
            $.ajax({
              data:  {
                "id":id,
                "idpedido":idpedido,
                "function":"elimina_cupon"
              },
              url:   'a_pedidos/db_.php',
              type:  'post',
              timeout:3000,
              beforeSend: function () {

              },
              success:  function (response) {
                $("#trabajo").load("a_pedidos/editar.php?id="+idpedido);
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
  function confirmar_web(pedido_web,idpedido){
    $.confirm({
      title: 'Cliente',
      content: '¿Desea confirmar el pedido a CT?',
      buttons: {
        Aceptar: function () {
          $.ajax({
            data:  {
              "pedido_web":pedido_web,
              "idpedido":idpedido,
              "function":"confirmar_web"
            },
            url:   "a_pedidos/db_.php",
            type:  'post',
            success:  function (response) {
              console.log(response);
              var datos = JSON.parse(response);
              if (datos.error==0){
                $.ajax({
                  data:  {
                    "id":idpedido
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
                $.ajax({
                  data:  {
                    "id":idpedido
                  },
                  url:   'a_pedidos/editar.php',
                  type:  'post',
                  success:  function (response) {
                    $("#trabajo").html(response);
                  }
                });
                Swal.fire({
                  type: 'error',
                  title: datos.terror,
                  showConfirmButton: false,
                  timer: 1000
                });
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
  function solicitar_ct(id){
    $.confirm({
      title: 'Cliente',
      content: '¿Desea procesar el pedido?, (envio de productos a CT, descuento de inventario)',
      buttons: {
        Aceptar: function () {
          $.ajax({
      			data:  {
      				"id":id,
      				"function":"pedir_ct"
      			},
      			url:   "a_pedidos/db_.php",
      			type:  'post',
      			beforeSend: function () {
              $("#cargando").addClass("is-active");
      			},
      			success:  function (response) {
              var datos = JSON.parse(response);
              if (datos.error==0){
                Swal.fire({
                  type: 'success',
                  title: "Pedido procesado correctamente",
                  showConfirmButton: false,
                  timer: 1000
                });
                $.ajax({
                  data:  {
                    "id":id
                  },
                  url:   'a_pedidos/editar.php',
                  type:  'post',
                  success:  function (response) {
                    $("#trabajo").html(response);
                  }
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
              $("#cargando").removeClass("is-active");
      			}
      		});
        },
        Cancelar: function () {

        }
      }
    });
  }
  function select_factdir(){
    var dir_fin=$("#dir_tipo").val();
    var idcliente=$("#idcliente").val();

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
        url: "a_pedidos/db_.php",
        type: "POST",
        data:  {
          "dir_fin":dir_fin,
          "idcliente":idcliente,
          "function":"dir_update"
        },
        success: function( response ) {
          console.log(response);
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
</script>
