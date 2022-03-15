<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 17:41
 */
namespace BartDecorte\ImagickSvg;

use ImagickDraw;
use XMLReader;

class Polygon extends Shape
{
    protected string $points;

    public function __construct(XMLReader $reader)
    {
        parent::__construct($reader);
        $this->points = $reader->getAttribute('points');
    }

    protected function coordinates(): array
    {
        $points = explode(' ', $this->points);

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
        return $this->whileTransformed($draw, function (ImagickDraw $draw) {
            $draw->polygon($this->coordinates());
        });
    }
}
