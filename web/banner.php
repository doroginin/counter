<?php

require_once '../vendor/autoload.php';
require_once './.conf.php';

$storage = new \dd\Counter\Storage\PdoStorage(PDO_DSN, PDO_USER, PDO_PASSWORD);
//$storage = new \dd\Counter\Storage\FileStorage(FILE_PATH);

$count = (new \dd\Counter\Counter($storage))->process()->count();

// draw banner
$padding = 8;
$charW = 7;
$charH = 15;
$im = imagecreate($padding * 2 + $charW * strlen($count), $padding * 2 + $charH);
$bg = imagecolorallocate($im, 200, 200, 200);
$color = imagecolorallocate($im, 0, 0, 255);
imagestring($im, 3, $padding, $padding, $count, $color);
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);