<?php

namespace Redondeo\Implementacion;
use Faker\Factory;
use League\CLImate\CLImate;


abstract class Filas{


    protected $filas = [];

    public function builderFilas($filasTotales = 5){

        $filas = [];
        $faker = Factory::create();
        for ($i=0; $i<= $filasTotales; $i++){
            $this->filas[] = ['cantidad'=>$faker->numberBetween(1,15), 'precio' =>$faker->randomFloat(4,1,15), 'impuesto' => 0.07, 
            'descuento' =>(string) $faker->randomElement($array = array (20,22)) / 100];
        }
        $this->filas =  collect($this->filas);
    }

    public function mostrarTabla($filas){
        $climate = new CLImate();
        $items = $filas->all();
        $climate->table($items);
        $climate->bold()->backgroundBlue()->border();
    }

    public function mostarValores($filas){
        $climate = new CLImate();
        $padding = $climate->padding(28)->char('-');

        $resultados = $filas;
        // por fila
        $subtotal = $resultados->sum('subTotal'); // 2 decimales
        $descuento = $resultados->sum('descuento_total');
        $impuesto = $resultados->sum('impuesto_total');
        $retenido = $resultados->sum('retenido');

        $subTotal = round($subtotal,2, PHP_ROUND_HALF_UP);
        $descuentos = round($descuento,2, PHP_ROUND_HALF_UP);
        $impuestos = round($impuesto ,2, PHP_ROUND_HALF_UP);
        $retenidos = round($retenido ,2, PHP_ROUND_HALF_UP);
        $retenido_impar = round($retenido ,2, PHP_ROUND_HALF_ODD);
        $retenido_par = round($retenido ,2, PHP_ROUND_HALF_EVEN);
        $Totales = round(($subtotal - $descuento + $impuesto) ,2, PHP_ROUND_HALF_UP);
        

        // por totales
        $des_total = $this->descuentos($filas);
        //$des_total = 0;
        $imp_total = round((($subTotal - $des_total) * 0.07),2,PHP_ROUND_HALF_UP);
        $rete_total =  round(($imp_total / 2),2,PHP_ROUND_HALF_UP);
        $rete_totales = $this->retenidos($filas);
        $saldos = $Totales - $rete_total; 

        $padding->label('SubTotal')->result($this->moneyFormat($subTotal));
        
        $padding->label('Descuentos')->result($this->moneyFormat($descuentos));
        $padding->label('Descuentos Total')->result('<light_green>'.$des_total.'</light_green>');
        $climate->border('-*-');
        $padding->label('Impuestos')->result($this->moneyFormat($impuestos));
        $padding->label('Impuestos Total')->result('<light_green>'.$imp_total.'</light_green>');
        $climate->border('-*-');
        $padding->label('Retenidos linea')->result($this->moneyFormat($retenidos));
        $padding->label('Retenidos impar')->result($retenido_impar);
        $padding->label('Retenidos par')->result($retenido_par);
        
        $color_retenido = strnatcmp($retenidos,$rete_total) === 0?'light_green':'light_red';

        $padding->label('Retenido Total')->result("<{$color_retenido}>".$rete_total."</{$color_retenido}>");
        $padding->label('Retenido Totales')->result("<{$color_retenido}>".$rete_totales."</{$color_retenido}>");
        $climate->border('-*-');
        $padding->label('Totales')->result('<light_green>'.$this->moneyFormat($Totales).'</light_green>');
        $padding->label('Saldo')->result('<light_yellow>'.$saldos.'<light_yellow>');
        
       
        
    }

    public function moneyFormat($number) :string{

        //return money_format('%.2n', (string)$number);
        return (string)$number;

    }

    function descuentos($filas){
        
        $descuentos = $filas->map(function($item){ return array_merge($item, ['descuento' => (string)$item['descuento'] ]);})
        ->groupBy('descuento')
        ->map(function($des,$key){
            return $des->sum('subTotal')* $key;
        })->sum();

        return round($descuentos, 2, PHP_ROUND_HALF_UP);
        
    }

    function retenidos($filas){
        $retenidos = $filas->map(function($item){ return array_merge($item, ['impuesto' => (string)$item['impuesto'] ]);})
        ->groupBy('impuesto')
        ->map(function($des,$key){
            $imp_total = round((($des->sum('subTotal') - $des->sum('descuento_total')) * $key),2,PHP_ROUND_HALF_UP);
            
            return  bcmul($imp_total ,0.50,  4);
        })->sum();

        return round($retenidos, 2, PHP_ROUND_HALF_UP);
    }

    abstract protected function hacerCalculos();
    
}