<?php

namespace Redondeo;
use Redondeo\Implementacion\Filas;

class CalculoFilas extends Filas{
   

    public function hacerCalculos(){
        
        $resultados = $this->filas->map(function($item){
            $precio = $item['cantidad'] * $item['precio'];
            $item['subTotal'] = round($precio, 2, PHP_ROUND_HALF_UP);
            $descuento = round(($item['subTotal'] * $item['descuento']),4, PHP_ROUND_HALF_EVEN);
            $item['descuento_total'] = $descuento;
            //$impuesto = ($item['subTotal'] -  $descuento) * $item['impuesto'];
            $impuesto = bcmul(bcsub ($item['subTotal'], $descuento, 4),$item['impuesto'],4);
            $super_impuesto = ($precio -($precio * 0.05)) * 0.07; 

            $item['impuesto_total'] = round($impuesto,4,PHP_ROUND_HALF_UP);
            $imp = round($item['impuesto_total'],4,PHP_ROUND_HALF_EVEN);
            $item['retenido'] = round(bcmul($item['impuesto_total'] , 0.5, 4), 2, PHP_ROUND_HALF_UP);

            
            return $item;
        });

        return $resultados;
    }

    

    
}
