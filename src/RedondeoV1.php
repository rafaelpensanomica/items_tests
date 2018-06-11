<?php 

namespace Redondeo;
use Redondeo\Implementacion\Filas;

class RedondeoV1 extends Filas{
   

    public function hacerCalculos(){
        
        $decimales = 1000; // hace referencia a la cantidad de decimales ejemplo a 4 decimales

        $resultados = $this->filas->map(function($item) use($decimales){

            $precio = $item['cantidad'] * $item['precio'] ;
            $item['subTotal'] = round($precio, 2,PHP_ROUND_HALF_UP);
            $descuento = $precio * $item['descuento'];
            $tem_desc = round($descuento * $decimales, 2, PHP_ROUND_HALF_UP);
            $item['descuento_total'] = round(($tem_desc/$decimales),4,PHP_ROUND_HALF_UP);
            $impuesto = ($item['subTotal'] -  $item['descuento_total']) * $item['impuesto'];
            $temp_imp =  round(($impuesto * $decimales), 4, PHP_ROUND_HALF_UP);
            $item['impuesto_total'] = round(($temp_imp / $decimales),4,PHP_ROUND_HALF_UP);
            $item['retenido'] = round(($item['impuesto_total'] * 0.50), 4, PHP_ROUND_HALF_UP);
           
            return $item;
        });

        return $resultados;
    }

}