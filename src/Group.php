<?php
/**
 * Created by Bart Decorte
 * Date: 15/03/2022
 * Time: 19:51
 */

namespace BartDecorte\ImagickSvg;

use BartDecorte\ImagickSvg\Concerns\GroupsElements;
use ImagickDraw;
use XMLReader;

class Group extends Shape
{
    use GroupsElements;

    protected XMLReader $reader;
    protected int $depth;

    public function __construct(XMLReader $reader)
    {
        parent::__construct($reader);
        $this->depth = $reader->depth;
        $this->parse();
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
