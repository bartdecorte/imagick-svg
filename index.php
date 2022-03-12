<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 14:53
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use BartDecorte\ImagickSvg\Svg;

$svg = new Svg(__DIR__ . '/welbi-logo.svg');

$last_modified = gmdate("D, d M Y H:i:s T", time());
$expires = gmdate("D, d M Y H:i:s T", time() + 365 * 24 * 60 * 60); // Cache for a year

// $svg->draw(); exit;

@ob_clean();
ob_start();
header('Content-Type: image/png');
header('Cache-Control: public');
header('Last-Modified: ' . $last_modified);
header('Expires: ' . $expires);
print $svg->draw()->getImageBlob();
header('Content-Length: '. ob_get_length());
ob_end_flush();
