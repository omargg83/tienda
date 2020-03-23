<?php
  require_once("db_.php");
 ?>
 <nav class='navbar navbar-expand-lg navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fas fa-ticket-alt"></i>Cupones</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
					<input class='form-control mr-sm-2' type='search' placeholder='Buscar' aria-label='Search' name='buscar' id='buscar'  onkeyup='Javascript: if (event.keyCode==13) buscar_cupon()'>
          <div class='btn-group'>
            <button type='button' class='btn btn-outline-warning btn-sm' onclick='buscar_cupon()'><i class='fas fa-search'></i>Buscar</button>
          </div>
				</div>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_cupones/editar'><i class="fas fa-folder-plus"></i><span>Nuevo</span></a></li>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod' data-lugar='a_cupones/lista'><i class="fas fa-list"></i><span>Lista</span></a></li>
      </li>
 			</ul>
 		</div>
 	  </div>
 	</nav>

<?php
  echo "<div class='container'>";
   echo "<div id='trabajo' style='margin-top:5px;'>";
    include 'lista.php';
   echo "</div>";
  echo "</div>";
 ?>

 <script type="text/javascript">
  function buscar_cupon(){
    var buscar = $("#buscar").val();
    $.ajax({
      data:  {
        "buscar":buscar
      },
      url:   'a_cupones/lista.php',
      type:  'post',
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
  function buscar_prodcup(){
  	var texto=$("#prod_venta").val();
  	var idcupon=$("#idcupon").val();
  	var tipo_C=$("#tipo_C").val();
  	if(texto.length>=-1){
  		$.ajax({
  			data:  {
  				"texto":texto,
  				"idcupon":idcupon,
  				"tipo_C":tipo_C,
  				"function":"busca_producto"
  			},
  			url:   "a_cupones/db_.php",
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
  function producto_cupon(idProducto,idcupon,tipo){
    $.ajax({
      data:  {
        "function":"producto_cupon",
        "idProducto":idProducto,
        "idcupon":idcupon,
        "tipo":tipo
      },
      url:   'a_cupones/db_.php',
      type:  'post',
      success:  function (response) {
        var datos = JSON.parse(response);
        if (datos.error==0){
          $.ajax({
            data:  {
              "id":idcupon
            },
            url:   'a_cupones/editar.php',
            type:  'post',
            success:  function (response) {
              $("#trabajo").html(response);
            }
          });
          Swal.fire({
						  type: 'success',
						  title: 'Se agregó correctamente',
						  showConfirmButton: false,
						  timer: 1000
					})
        }
        else{
          $.alert(datos.terror);
        }
      }
    });
  }
  function buscar_catcup(){
  	var texto=$("#prod_venta").val();
  	var idcupon=$("#idcupon").val();
  	var tipo_C=$("#tipo_C").val();
  	if(texto.length>=-1){
  		$.ajax({
  			data:  {
  				"texto":texto,
  				"idcupon":idcupon,
  				"tipo_C":tipo_C,
  				"function":"busca_cat"
  			},
  			url:   "a_cupones/db_.php",
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
  function categoria_cupon(idcategoria,idcupon,tipo){
    $.ajax({
      data:  {
        "function":"categoria_cupon",
        "idcategoria":idcategoria,
        "idcupon":idcupon,
        "tipo":tipo
      },
      url:   'a_cupones/db_.php',
      type:  'post',
      success:  function (response) {
        var datos = JSON.parse(response);
        if (datos.error==0){
          $.ajax({
            data:  {
              "id":idcupon
            },
            url:   'a_cupones/editar.php',
            type:  'post',
            success:  function (response) {
              $("#trabajo").html(response);
            }
          });
          Swal.fire({
						  type: 'success',
						  title: 'Se agregó correctamente',
						  showConfirmButton: false,
						  timer: 1000
					})
        }
        else{
          $.alert(datos.terror);
        }
      }
    });
  }
  function agregar_correo(){
  	var correo=$("#correox").val();
  	var idcupon=$("#idcupon").val();
  	if(correo.length>=-1){
  		$.ajax({
  			data:  {
  				"correo":correo,
  				"idcupon":idcupon,
  				"function":"agregar_correo"
  			},
  			url:   "a_cupones/db_.php",
  			type:  'post',
  			success:  function (response) {
          var datos = JSON.parse(response);
          if (datos.error==0){
            $.ajax({
              data:  {
                "id":idcupon
              },
              url:   'a_cupones/editar.php',
              type:  'post',
              success:  function (response) {
                $("#trabajo").html(response);
              }
            });
            Swal.fire({
  						  type: 'success',
  						  title: 'Se agregó correctamente',
  						  showConfirmButton: false,
  						  timer: 1000
  					})
          }
          else{
            $.alert(datos.terror);
          }
  			}
  		});
  	}
  }


  </script>
