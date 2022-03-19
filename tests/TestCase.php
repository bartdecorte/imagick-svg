<?php
/**
 * Created by Bart Decorte
 * Date: 18/03/2022
 * Time: 19:59
 */

namespace BartDecorte\ImagickSvg\Tests;

use BartDecorte\ImagickSvg\Svg;
use Imagick;
use ImagickDraw;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function assertSvgEqualsPng(string $baseFilename)
    {
        $svg = new Svg(__DIR__ . "/input/$baseFilename.svg");

        $width = 800;
        $height = ceil($width * ($svg->height() / $svg->width()));
        $draw = new ImagickDraw();
        $draw->scale($width / $svg->width(), $height / $svg->height());
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);

        $draw = $svg->draw($draw);

        $generated = new Imagick();
        $generated->newImage($width, $height, '#00000000', 'png');
        $generated->drawImage($draw);

        $actual = new Imagick();
        $actual->readImageBlob($generated->getImageBlob());

        $expected = new Imagick();
        $expected->readImage(__DIR__ . "/expected/$baseFilename.png");

        $diff = $actual->compareImages($expected, Imagick::METRIC_ABSOLUTEERRORMETRIC);
        $this->assertEquals(0.0, $diff[1]);
    }
}
