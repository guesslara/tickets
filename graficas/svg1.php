<?
header("Content-type: image/svg+xml");
print('<?xml version="1.0" encoding="iso-8859-1"?>');
$svgwidth=500;
$svgheight=400;
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/SVG/DTD/svg10.dtd">
<svg width="<?=$svgwidth;?>px" height="<?=$svgheight;?>px" xmlns="http://www.w3.org/2000/svg">
<desc>This is a php-random rectangle test</desc>
<?
//initalizing random generator
srand((double) microtime() * 1000000);
for ($i = 0; $i < 20; $i+=1) {
	//avoid getting a range 0..0 for rand function
	$x = floor(rand(0,$svgwidth-1));
	$y = floor(rand(0,$svgheight-1));
                         //avoid getting rect outside of viewBox
	$width = floor(rand(0,$svgwidth-$x));
	$height = floor(rand(0,$svgheight-$y));
	$red = floor(rand(0,255));
	$blue = floor(rand(0,255));
	$green = floor(rand(0,255));
	$color = "rgb(".$red.",".$green.",".$blue.")";
	print "\t<rect x=\"$x\" y=\"$y\" width=\"$width\" height=\"$height\" style=\"fill:$color;\"/>\n";
}
?>
<text x="<?=$svgwidth/2;?>px" y="300" style="font-size:15;" text-anchor="middle">The servers Date and Time is: <? print (strftime("%Y-%m-%d, %H:%M:%S")); ?></text>
<text x="<?=$svgwidth/2;?>px" y="340" style="font-size:15;" text-anchor="middle">You are running:</text>
<text x="<?=$svgwidth/2;?>px" y="360" style="font-size:15;" text-anchor="middle">
<? print $HTTP_USER_AGENT; ?></text>
</svg>
