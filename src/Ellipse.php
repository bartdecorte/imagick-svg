<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 17:41
 */

namespace BartDecorte\ImagickSvg;

use ImagickDraw;
use XMLReader;

class Ellipse extends Shape
{
    protected float $cx;
    protected float $cy;
    protected float $rx;
    protected float $ry;

    public function __construct(XMLReader $reader)
    {
        parent::__construct($reader);
        $this->cx = $reader->getAttribute('cx') ?? 0;
        $this->cy = $reader->getAttribute('cy') ?? 0;
        $this->rx = $reader->getAttribute('rx');
        $this->ry = $reader->getAttribute('ry');
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);

        return $this->whileTransformed($draw, function (ImagickDraw $draw) {
            $draw->ellipse($this->cx, $this->cy, $this->rx, $this->ry, 0, 360);
        });
    }
}
