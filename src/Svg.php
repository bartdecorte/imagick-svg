<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 12:59
 */
namespace BartDecorte\ImagickSvg;

use BartDecorte\ImagickSvg\Exceptions\AssetNotFoundException;
use ImagickDraw;

class Svg
{
    protected ?string $contents = null;
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
        if ($this->contents) {
            return;
        }

        // Might be a local path...
        if (@file_exists($this->resource)) {
            $this->contents = file_get_contents($this->resource);
            return;
        }

        // ... or a remote URL
        $handle = curl_init($this->resource);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        $this->contents = curl_exec($handle); // Needed to get the http code below
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
            throw new AssetNotFoundException();
        }
    }

    protected function parse(): void
    {
        $this->parseRoot();
        $this->parseShapes();
    }

    protected function parseShapes(): void
    {
        $matches = [];
        preg_match_all('/<(path|rect|circle|ellipse|polygon)[^\/]+\/>/', $this->contents, $matches);

        foreach ($matches[0] ?? [] as $match) {
            $type = preg_replace('/<([^\s]+).*/', '$1', $match);
            switch ($type) {
                case 'path':
                    $shape = new Path($match);
                    break;
                case 'rect':
                    $shape = new Rectangle($match);
                    break;
                case 'circle':
                    $shape = new Circle($match);
                    break;
                case 'ellipse':
                    $shape = new Ellipse($match);
                    break;
                case 'polygon':
                    $shape = new Polygon($match);
                    break;
                default:
                    break;
            }
            $this->shapes[] = $shape;
        }
    }

    protected function parseRoot(): void
    {
        $root = preg_replace('/^.*(<svg[^>]*>).*$/sm', '$1', $this->contents);
        $viewBoxValue = preg_replace('/.*viewBox="([^"]*)".*/sm', '$1', $root);
        $boundingBox = explode(' ', $viewBoxValue);
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
        foreach ($this->shapes() as $path) {
            $draw = $path->draw($draw);
        }

        return $draw;
    }
}
