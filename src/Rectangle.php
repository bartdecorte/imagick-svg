<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 16:38
 */
namespace BartDecorte\ImagickSvg;

use ImagickDraw;

class Rectangle extends Shape
{
    protected function xAttributeValue(): ?string
    {
        return $this->attributeValue('x');
    }

    protected function yAttributeValue(): ?string
    {
        return $this->attributeValue('y');
    }

    protected function widthAttributeValue(): ?string
    {
        return $this->attributeValue('width');
    }

    protected function heightAttributeValue(): ?string
    {
        return $this->attributeValue('height');
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);

        return $this->whileTransformed($draw, function (ImagickDraw $draw) {
            $x1 = $this->xAttributeValue();
            $y1 = $this->yAttributeValue();
            $x2 = $x1 + $this->widthAttributeValue();
            $y2 = $y1 + $this->heightAttributeValue();

            $draw->rectangle($x1, $y1, $x2, $y2);
        });
    }
}
