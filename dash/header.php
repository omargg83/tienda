<?php
  require_once("db_.php");
?>
<nav class='navbar navbar-expand-sm navbar-dark fixed-top bg-dark' style='opacity:1;'>

  <a class='navbar-brand' href='#escritorio/dashboard'>Tic Shop</a>

  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#principal' aria-controls='principal' aria-expanded='false' aria-label='Toggle navigation'>
    <i class='fab fa-rocketchat'></i>
  </button>

  <div class='collapse navbar-collapse' id='principal'>
    <ul class='navbar-nav mr-auto'>
      <li class='nav-item dropdown'>
        <a class='nav-link navbar-brand' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></a>
      </li>
    </ul>
    <ul class='nav navbar-nav navbar-right' id='notificaciones'></ul>

    <ul class='nav navbar-nav navbar-right' id='fondo'></ul>
    <ul class='nav navbar-nav navbar-right'>
      <li class='nav-item dropdown'>
        <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
          <i class='fas fa-user-check'></i>
          <?php echo $_SESSION['nombre']; ?>
        </a>

        <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
          <a class='dropdown-item' id='winmodal_pass' data-id='<?php echo $_SESSION['idpersona']; ?>' data-lugar='a_usuarios/form_pass' title='Cambiar contraseña' ><i class='fas fa-key'></i>Contraseña</a>
        </div>
      </li>
    </ul>
    <ul class='nav navbar-nav navbar-right'>
      <li class='nav-item'>
        <a class='nav-link pull-left' onclick='salir()'>
          <i class='fas fa-door-open' style='color:red;'></i>Salir
        </a>
      </li>
    </ul>
  </div>
</nav>
