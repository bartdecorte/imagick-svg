# Imagick SVG
This package parses SVG files and executes those operations on an ImagickDraw instance.

## Installation

```
$ composer require bartdecorte/imagick-svg
```

## Supported SVG features
This package does not support the full SVG standard (yet). However, through adapting SVG files before feeding them in,
most SVGs should be rendered correctly.

Some pointers:
- Convert internal CSS & inline styles to presentation attributes
- Ungroup all elements
- Expand all elements (including strokes)

I'm looking to extend support in the near feature, feel free to open issues to request features, or better still, make
a pull request.

### Elements
- rect
- circle
- ellipse
- polygon
- path

### Attributes
- transform ("matrix" only)
- fill

### Internal CSS & inline style
Currently unsupported, use presentation attributes instead

## Usage

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use BartDecorte\ImagickSvg\Svg;

// local path or URL
$svg = new Svg(__DIR__ . '/esign-logo.svg');
$draw = new ImagickDraw();

$width = 1000;
$height = $width * ($svg->height() / $svg->width());
$draw->scale($width / $svg->width(), $height / $svg->height());
$draw->setStrokeAntialias(true);
$draw->setTextAntialias(true);

$draw = $svg->draw($draw);

$imagick = new Imagick();
$imagick->newImage($width, $height, '#00000000', 'png');
$imagick->drawImage($draw);

@ob_clean();
ob_start();
header('Content-Type: image/png');
print $imagick->getImageBlob();
header('Content-Length: '. ob_get_length());
ob_end_flush();
```
