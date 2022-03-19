<?php
/**
 * Created by Bart Decorte
 * Date: 18/03/2022
 * Time: 20:43
 */
namespace BartDecorte\ImagickSvg\Concerns;

use BartDecorte\ImagickSvg\Circle;
use BartDecorte\ImagickSvg\Ellipse;
use BartDecorte\ImagickSvg\Group;
use BartDecorte\ImagickSvg\Path;
use BartDecorte\ImagickSvg\Polygon;
use BartDecorte\ImagickSvg\Rectangle;
use BartDecorte\ImagickSvg\Shape;
use XMLReader;

trait GroupsElements
{
    protected array $shapes = [];

    protected function parse(): void
    {
        $shape = null;
        while ($this->reader->read()) {
            // $shape equaling NULL means we don't need to render that element. We have to keep traversing though.
            if ($shape !== null && $this->reader->nodeType === XMLReader::END_ELEMENT) {
                return;
            }

            if ($this->reader->nodeType !== XMLReader::ELEMENT) {
                continue;
            }

            if ($shape = $this->parseElement()) {
                $this->shapes[] = $shape;
            }
        }
    }

    protected function parseElement(): ?Shape
    {
        switch ($this->reader->name) {
            case 'g':
                $shape = new Group($this->reader);

                break;
            case 'path':
                $shape = new Path($this->reader);

                break;
            case 'rect':
                $shape = new Rectangle($this->reader);

                break;
            case 'circle':
                $shape = new Circle($this->reader);

                break;
            case 'ellipse':
                $shape = new Ellipse($this->reader);

                break;
            case 'polygon':
                $shape = new Polygon($this->reader);

                break;
            default:
                break;
        }

        return $shape ?? null;
    }

    public function shapes(): array
    {
        return $this->shapes;
    }
}
