<?php
ob_start();
session_start();

require 'header.php';

?>
<!-- Contenido -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title" id="titulo">

      </div>
    </div>

    <div class="clearfix"></div>
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
        <h1>Aquí va el contenido de la pagina</h1>
        </div>
      </div>
    </div>
    

  </div>
</div>
<!-- /Fin Contenido -->

<?php
require 'footer.php';
?>
<script type="text/javascript" src="scripts/inicio.js"></script>
<?php

ob_end_flush();
?>