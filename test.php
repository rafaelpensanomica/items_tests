<?php 
require_once 'vendor/autoload.php';
use Redondeo\CalculoFilas;

$calculo = new CalculoFilas();
$filas = $calculo->builderFilas(6);
$tabla = $calculo->hacerCalculos();
$calculo->mostrarTabla($tabla);
//$calculo->descuentos($tabla);

$calculo->mostarValores($tabla);