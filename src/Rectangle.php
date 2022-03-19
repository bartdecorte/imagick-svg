<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 16:38
 */

namespace BartDecorte\ImagickSvg;

use ImagickDraw;
use XMLReader;

class Rectangle extends Shape
{
    protected float $x;
    protected float $y;
    protected float $width;
    protected float $height;

    public function __construct(XMLReader $reader)
    {
        parent::__construct($reader);
        $this->x = $reader->getAttribute('x') ?? 0;
        $this->y = $reader->getAttribute('y') ?? 0;
        $this->width = $reader->getAttribute('width');
        $this->height = $reader->getAttribute('height');
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);

        return $this->whileTransformed($draw, function (ImagickDraw $draw) {
            $x1 = $this->x;
            $y1 = $this->y;
            $x2 = $x1 + $this->width;
            $y2 = $y1 + $this->height;

            $draw->rectangle($x1, $y1, $x2, $y2);
        });
    }
}
