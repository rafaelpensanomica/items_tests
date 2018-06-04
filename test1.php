<?php 
require_once 'vendor/autoload.php';
use Redondeo\RedondeoV1;

$calculo = new RedondeoV1();
$filas = $calculo->builderFilas(5);
$tabla = $calculo->hacerCalculos();

$calculo->mostrarTabla($tabla);
$calculo->mostarValores($tabla);