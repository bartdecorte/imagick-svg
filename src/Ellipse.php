<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 17:41
 */
namespace BartDecorte\ImagickSvg;

use ImagickDraw;

class Ellipse extends Shape
{
    protected function cxAttributeValue(): ?string
    {
        return $this->attributeValue('cx');
    }

    protected function cyAttributeValue(): ?string
    {
        return $this->attributeValue('cy');
    }

    protected function rxAttributeValue(): ?string
    {
        return $this->attributeValue('rx');
    }

    protected function ryAttributeValue(): ?string
    {
        return $this->attributeValue('ry');
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);

        $x = $this->cxAttributeValue();
        $y = $this->cyAttributeValue();
        $rx = $this->rxAttributeValue();
        $ry = $this->ryAttributeValue();
        $draw->ellipse($x, $y, $rx, $ry, 0, 360);

        return $draw;
    }
}
