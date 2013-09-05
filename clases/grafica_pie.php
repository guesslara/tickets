<?php
class grafica_pie{
	var $alto;
	var $ancho;
	
	var $valores;
	var $colores;
	
	function __construct($valores,$ancho,$alto){
		$this->valores=$valores;
		$this->ancho=$ancho;
		$this->alto=$alto;
		$this->colores=array('yellow','green','blue','pink','white','silver','gold','red','orange');
		/*
		echo "<br>Ancho: ".$this->ancho;
		echo "<br>Alto: ".$this->alto;
		echo "<br>"; print_r($this->valores);
		echo "<br>"; print_r($this->colores);
		*/
		$centerx = $this->ancho / 2; // centre of the pie chart
		$centery = $this->alto / 2;
		$radius = min($centerx,$centery) - 10; // radius of the pie chart
		if ($radius < 5) {
			die("El tamaño de la grafica es demasiado pequeño.");
			exit;
		}
		
		header('Content-type: image/svg+xml');
		$codigo_xml='';
		
		$codigo_xml.='<?xml version="1.0" encoding="UTF-8" standalone="no"?>'; 
		$codigo_xml.=' <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.0" width="$width" height="$height" id="svg2">';
			$codigo_xml.=$this->piechart($this->valores,$this->ancho,$this->alto,$radius);
		$codigo_xml.='\n</svg>\n';
		print $codigo_xml;				
	}
	
	function piechart($data, $cx, $cy, $radius) {
		$chartelem = "";
		$max = count($data);
		$colours = $this->colores;
		
		//$sum = array_sum($colours);
		
		
		foreach ($data as $key=>$val) {
			$sum += $val;
		}
		
		$deg = $sum/360; // one degree
		$jung = $sum/2; // necessary to test for arc type
		
		/* Data for grid, circle, and slices */ 
		
		$dx = $radius; // Starting point: 
		$dy = 0; // first slice starts in the East
		$oldangle = 0;
		
		/* Loop through the slices */
		for ($i = 0; $i<$max; $i++) {
			$angle = $oldangle + $data[$i]/$deg; // cumulative angle
			$x = cos(deg2rad($angle)) * $radius; // x of arc's end point
			$y = sin(deg2rad($angle)) * $radius; // y of arc's end point
		
			$colour = $colours[$i];
		
			if ($data[$i] > $jung) {
				// arc spans more than 180 degrees
				$laf = 1;
			}
			else {
				$laf = 0;
			}
		
			$ax = $cx + $x; // absolute $x
			$ay = $cy + $y; // absolute $y
			$adx = $cx + $dx; // absolute $dx
			$ady = $cy + $dy; // absolute $dy
			$chartelem .= "\n";
			$chartelem .= "<path d=\"M$cx,$cy "; // move cursor to center
			$chartelem .= " L$adx,$ady "; // draw line away away from cursor
			$chartelem .= " A$radius,$radius 0 $laf,1 $ax,$ay "; // draw arc
			$chartelem .= " z\" "; // z = close path
			$chartelem .= " fill=\"$colour\" stroke=\"black\" stroke-width=\"2\" ";
			$chartelem .= " fill-opacity=\"0.5\" stroke-linejoin=\"round\" />";
			$dx = $x; // old end points become new starting point
			$dy = $y; // id.
			$oldangle = $angle;
		}
		$chartelem .= "\n<circle cx=\"210\" cy=\"210\" r=\"200\" stroke=\"black\" stroke-width=\"2\" fill=\"red\"/>";
		return $chartelem; 
	}	
}
$m_valores=array(12,8,50,30);
$g1=new grafica_pie($m_valores,200,200);
?>
