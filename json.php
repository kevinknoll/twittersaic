<?php
$img = imagecreatefrompng('logo.png');

$w = imagesx($img);
$h = imagesy($img);

$pixels = array();

echo '{"w":' . $w . ',"h":' . $h . ',"pixels":[';
$tmp = '';

for($y = 0; $y < $h; ++$y)
{
  for($x = 0; $x < $w; ++$x)
  {
    $pixel = imagecolorat($img, $x, $y);
    $colors = imagecolorsforindex($img, $pixel);

    $a = (127 - $colors['alpha']) / 127;
    if ($a > 0) {
      echo $tmp;
      $tmp = json_encode(array('x' => $x, 'y' => $y, 'r' => $colors['red'], 'g' => $colors['green'], 'b' => $colors['blue'], 'a' => $a));
      $tmp .= ',';
    }
  }
}

echo substr($tmp, 0, -1);
echo ']}';
?>