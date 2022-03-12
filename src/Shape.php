<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 16:31
 */
namespace BartDecorte\ImagickSvg;

use ImagickDraw;

abstract class Shape
{
    public function __construct(protected string $contents)
    {
        //
    }

    protected function attributeValue(string $name): ?string
    {
        $matches = [];
        preg_match("/<[^\/]*$name=\"([^\"]+)\"[^\/]*\/>/sm", $this->contents, $matches);

        return $matches[1] ?? null;
    }

    protected function fillAttributeValue(): ?string
    {
        return $this->attributeValue('fill');
    }

    protected function transformAttributeValue(): ?string
    {
        return $this->attributeValue('transform');
    }

    protected function transformInstructions(): array
    {
        if (! $this->transformAttributeValue()) {
            return [];
        }

        $matches = [];
        preg_match_all('/([a-zA-Z]+)\(([^)]+)\)/', $this->transformAttributeValue(), $matches);

        $instructions = [];
        foreach ($matches[1] as $i => $command) {
            $arguments = array_map(
                'trim',
                explode(',', $matches[2][$i])
            );
            $instructions[] = compact(
                'command',
                'arguments',
            );
        }

        return $instructions;
    }

    protected function transform(ImagickDraw $draw): ImagickDraw
    {
        foreach ($this->transformInstructions() as ['command' => $command, 'arguments' => $arguments]) {
            switch ($command) {
                case 'matrix':
                    $draw->affine([
                        'sx' => $arguments[0],
                        'sy' => $arguments[3],
                        'rx' => $arguments[1],
                        'ry' => $arguments[2],
                        'tx' => $arguments[4],
                        'ty' => $arguments[5],
                    ]);
                    break;
                default:
                    break;
            }
        }

        return $draw;
    }

    protected function setFill(ImagickDraw $draw)
    {
        if (! $fill = $this->fillAttributeValue()) {
            return;
        }

        $draw->setFillColor($fill);
    }

    public function draw(ImagickDraw $draw)
    {
        $this->setFill($draw);
        $this->transform($draw);
    }
}
