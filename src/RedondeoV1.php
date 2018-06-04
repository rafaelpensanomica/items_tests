<?php 

namespace Redondeo;
use Redondeo\Implementacion\Filas;

class RedondeoV1 extends Filas{
   

    public function hacerCalculos(){
        
        $decimales = 1000; // hace referencia a la cantidad de decimales ejemplo a 4 decimales

        $resultados = $this->filas->map(function($item) use($decimales){

            $precio = $item['cantidad'] * $item['precio'] ;
            $item['subTotal'] = $precio;
            $descuento = $precio * $item['descuento'];
            $tem_desc = round($descuento * $decimales);
            $item['descuento_total'] = round(($tem_desc/$decimales),4,PHP_ROUND_HALF_UP);
            $impuesto = ($precio -  $item['descuento_total']) * $item['impuesto'];
            $temp_imp =  round($impuesto * $decimales);
            $item['impuesto_total'] = round(($temp_imp / $decimales),2,PHP_ROUND_HALF_UP);
            $item['retenido'] = round(($item['impuesto_total'] / 2), 2, PHP_ROUND_HALF_UP);
           
            return $item;
        });

        return $resultados;
    }

}