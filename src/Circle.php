<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 17:41
 */
namespace BartDecorte\ImagickSvg;

use ImagickDraw;

class Circle extends Shape
{
    protected function cxAttributeValue(): ?string
    {
        return $this->attributeValue('cx');
    }

    protected function cyAttributeValue(): ?string
    {
        return $this->attributeValue('cy');
    }

    protected function rAttributeValue(): ?string
    {
        return $this->attributeValue('r');
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);

        $x = $this->cxAttributeValue();
        $y = $this->cyAttributeValue();
        $r = $this->rAttributeValue();
        $draw->circle($x, $y, $x + $r, $y); // TODO fix jagged circle

        return $draw;
    }
}
