<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 17:41
 */
namespace BartDecorte\ImagickSvg;

use ImagickDraw;

class Polygon extends Shape
{
    protected function pointsAttributeValue(): ?string
    {
        return $this->attributeValue('points');
    }

    protected function coordinates(): array
    {
        if (! $this->pointsAttributeValue()) {
            return [];
        }

        $points = explode(' ', $this->pointsAttributeValue());

        $coordinates = [];
        for ($i = 0; $i < count($points); $i += 2) {
            $coordinates[] = [
                'x' => $points[$i],
                'y' => $points[$i + 1],
            ];
        }

        return $coordinates;
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);

        $draw->polygon($this->coordinates());

        return $draw;
    }
}
