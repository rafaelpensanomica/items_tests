<?php

namespace Redondeo;
use Redondeo\Implementacion\Filas;

class CalculoFilas extends Filas{
   

    public function hacerCalculos(){
        
        $resultados = $this->filas->map(function($item){
            //calculo del subtotal
            $precio = $item['cantidad'] * $item['precio'];
            //redondeo del subtotal
            $item['subTotal'] = round($precio, 2, PHP_ROUND_HALF_UP);
            //calculo del descuento y redondeo a 4 decimales
            $descuento = round(($item['subTotal'] * $item['descuento']),4, PHP_ROUND_HALF_UP);
            $item['descuento_total'] = $descuento;
            //calculo del impuesto
            $descuento_total = round($descuento, 2, PHP_ROUND_HALF_UP);
            $subtotal1 =  round($item['subTotal'] -  $descuento_total, 2, PHP_ROUND_HALF_UP);
            $impuesto =  $subtotal1 * $item['impuesto'];
            //redondeo del impuesto
            $item['impuesto_total'] = round($impuesto,3,PHP_ROUND_HALF_ODD);
            //este calculo del retenido tiene errores
            $item['retenido'] = round(bcmul($item['impuesto_total'] , 0.5, 4), 2, PHP_ROUND_HALF_DOWN);
            return $item;
        });

        return $resultados;
    }

    

    
}
