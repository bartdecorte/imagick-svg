<?php
/**
 * Created by Bart Decorte
 * Date: 15/03/2022
 * Time: 19:51
 */
namespace BartDecorte\ImagickSvg;

use BartDecorte\ImagickSvg\Exceptions\UnsupportedElementException;
use XMLReader;
use ImagickDraw;

class Group extends Shape
{
    protected XMLReader $reader;
    protected int $depth;
    protected array $shapes = [];

    public function __construct(XMLReader $reader)
    {
        parent::__construct($reader);
        $this->depth = $reader->depth;
        $this->parse();
    }

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

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        return $this->whileTransformed($draw, function (ImagickDraw $draw) {
            foreach ($this->shapes() as $shape) {
                $draw = $shape->draw($draw);
            }
        });
    }
}
