<?php

include "config.php";
require_once "DBAccess.php";
use DB\DBAccess;

$paginaHTML = file_get_contents("contatti.html");
$paginaHTML = file_get_contents("chisiamo.html");
$paginaHTML = file_get_contents("privacy.html");
$listaGeneri ="";
$breadcrumbs = "";