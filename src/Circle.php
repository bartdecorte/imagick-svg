<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 17:41
 */

namespace BartDecorte\ImagickSvg;

use ImagickDraw;
use XMLReader;

class Circle extends Shape
{
    protected float $cx;
    protected float $cy;
    protected float $r;

    public function __construct(XMLReader $reader)
    {
        parent::__construct($reader);
        $this->cx = $reader->getAttribute('cx') ?? 0;
        $this->cy = $reader->getAttribute('cy') ?? 0;
        $this->r = $reader->getAttribute('r');
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);

        return $this->whileTransformed($draw, function (ImagickDraw $draw) {
            $draw->ellipse($this->cx, $this->cy, $this->r, $this->r, 0, 360);
        });
    }
}
