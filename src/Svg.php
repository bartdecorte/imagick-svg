<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 12:59
 */

namespace BartDecorte\ImagickSvg;

use BartDecorte\ImagickSvg\Concerns\GroupsElements;
use BartDecorte\ImagickSvg\Exceptions\AssetNotFoundException;
use ImagickDraw;
use XMLReader;

class Svg
{
    use GroupsElements {
        parseElement as parseElementInGroup;
    }

    protected float $x1;
    protected float $y1;
    protected float $x2;
    protected float $y2;
    protected float $width;
    protected float $height;

    public function __construct(
        protected string $resource
    ) {
        $this->load();
        $this->parse();
    }

    protected function load(): void
    {
        $this->reader = new XMLReader();
        if ($this->reader->open($this->resource) === false) {
            throw new AssetNotFoundException();
        }
    }

    protected function parseElement(): ?Shape
    {
        if ($this->reader->name === 'svg') {
            $this->parseRoot();

            return null;
        }

        return $this->parseElementInGroup();
    }

    protected function parseRoot(): void
    {
        $boundingBox = explode(' ', $this->reader->getAttribute('viewBox'));
        $this->x1 = $boundingBox[0];
        $this->y1 = $boundingBox[1];
        $this->x2 = $boundingBox[2];
        $this->y2 = $boundingBox[3];

        $this->width = $this->x2 - $this->x1;
        $this->height = $this->y2 - $this->y1;
    }

    public function width(): float
    {
        return $this->width;
    }

    public function height(): float
    {
        return $this->height;
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        foreach ($this->shapes() as $shape) {
            $draw = $shape->draw($draw);
        }

        return $draw;
    }
}
