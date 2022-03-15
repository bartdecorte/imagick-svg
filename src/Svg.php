<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 12:59
 */
namespace BartDecorte\ImagickSvg;

use BartDecorte\ImagickSvg\Exceptions\AssetNotFoundException;
use BartDecorte\ImagickSvg\Exceptions\UnsupportedElementException;
use ImagickDraw;
use XMLReader;

class Svg
{
    protected ?XMLReader $reader = null;
    protected array $shapes = [];

    protected float $x1;
    protected float $y1;
    protected float $x2;
    protected float $y2;
    protected float $width;
    protected float $height;

    public function __construct(protected string $resource)
    {
        $this->load();
        $this->parse();
    }

    protected function load(): void
    {
        if ($this->reader) {
            return;
        }

        $this->reader = new XMLReader();
        if ($this->reader->open($this->resource) === false) {
            throw new AssetNotFoundException();
        }
    }

    protected function parse(): void
    {
        while ($this->reader->read()) {
            if ($this->reader->nodeType !== XMLReader::ELEMENT) {
                continue;
            }

            $shape = null;
            switch ($this->reader->name) {
                case 'svg':
                    $this->parseRoot();
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
                    throw new UnsupportedElementException($this->reader->name);
            }

            if ($shape) {
                $this->shapes[] = $shape;
            }
        }
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

    public function shapes(): array
    {
        return $this->shapes;
    }

    public function width(): float
    {
        return $this->width;
    }

    public function height(): float
    {
        return $this->height;
    }

    public function draw($draw): ImagickDraw
    {
        foreach ($this->shapes() as $shape) {
            $draw = $shape->draw($draw);
        }

        return $draw;
    }
}
