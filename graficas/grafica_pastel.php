<?php
$accion=$_GET["accion"];
$titulo=$_GET["titulo"];
$areas=$_GET["areas"];
$valores=$_GET["valores"];
//print_r($valores);
$values=explode(',',$valores);
$mareas=explode(',',$areas); 

//$values=$valores; //array(20,20,30,10,5,5);
$areas = array('sistemas','servicios generales','logistica');
$colours = array('yellow','red','blue');
$width = 400; // canvas size
$height = 500; 
$centerx = $width / 2; // centre of the pie chart
$centery = $height / 2;
$radius = min($centerx,$centery) - 10; // radius of the pie chart
if ($radius < 5) {
	die("La grafica es demasiado pequeña.");
} 

/* Draw and output the SVG file. */

header('Content-type: image/svg+xml');
$xml_imagen='';
echo <<<END
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.0" width="700" height="$height" id="svg2">
END;
echo '<title>'.$titulo.'</title>';

print '	<rect x="10" y="10" rx="10" ry="10" width="680" height="450" style="fill:beige;stroke:black;stroke-width:2;opacity:1"/>';
print '<text  x="20" y="40" dx="0,0,0,0,20" dy="0,0,0,0,8" font-size="27" >'.$titulo.'</text>';
print piechart($mareas,$values,200,200,190);

print "\n</svg>\n";


/* 
	The piechart function
	
	Arguments are an aray of values, the centre coordinates x and y, and 
	the radius of the piechart.	
*/

function piechart($areas,$data, $cx, $cy, $radius) {
	$cx=$cx+10;
	$cy=$cy+50;
	
	$chartelem = "";
	//$chartelem .= "\n<circle cx=\"200\" cy=\"200\" r=\"190\" stroke=\"black\" stroke-width=\"2\" fill=\"red\"/>";
	$max = count($data);
	//$colours = array('yellow','green','blue','pink','white','silver','gold','red','orange');
	//$areas = array('sistemas','servicios generales','logistica');
	$colours = array('green','red','blue','yellow','pink','white','silver','red','orange','silver','beige');
	$sum = 0;
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
		$chartelem .= " fill-opacity=\"0.7\" stroke-linejoin=\"round\" />";
		$dx = $x; // old end points become new starting point
		$dy = $y; // id.
		$oldangle = $angle;
	}
	
	$suma_valores=array_sum($data);
	for($ix=0;$ix<count($areas);$ix++){
		$coordenada_y=$ix*20+30;
		$chartelem .= '\n<circle cx="420" cy="'.$coordenada_y.'" r="9" stroke="black" stroke-width="1" fill="'.$colours[$ix].'" fill-opacity="0.7" />';	
		$chartelem .='<text  x="435" y="'.($coordenada_y+5).'" dx="0,0,0,0,20" dy="0,0,0,0,8" font-size="17" >'.round(($data[$ix]/$suma_valores*100)).'%</text>';
		$chartelem .='<text  x="475" y="'.($coordenada_y+5).'" dx="0,0,0,0,20" dy="0,0,0,0,8" font-size="17" >'.$areas[$ix].'</text>';
	}
	return $chartelem; 
}
?>
