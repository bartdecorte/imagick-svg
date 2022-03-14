<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 16:31
 */
namespace BartDecorte\ImagickSvg;

use BartDecorte\ImagickSvg\Exceptions\UnsupportedTransformException;
use BartDecorte\ImagickSvg\Util\Matrix;
use ImagickDraw;
use Closure;

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
                function ($argument) {
                    return floatval(trim($argument));
                },
                explode(',', str_replace(' ', ',', $matches[2][$i]))
            );
            $instructions[] = compact(
                'command',
                'arguments',
            );
        }

        return $instructions;
    }

    protected function transformMatrix(ImagickDraw $draw, array $arguments, bool $invert = false): ImagickDraw
    {
        $matrix = new Matrix(
            $arguments[0],
            $arguments[1],
            $arguments[2],
            $arguments[3],
            $arguments[4],
            $arguments[5],
        );

        if ($invert) {
            $matrix->invert();
        }

        $draw->affine([
            'sx' => $matrix->a,
            'sy' => $matrix->d,
            'rx' => $matrix->b,
            'ry' => $matrix->c,
            'tx' => $matrix->e,
            'ty' => $matrix->f,
        ]);

        return $draw;
    }

    protected function transformTranslate(ImagickDraw $draw, array $arguments, bool $invert = false): ImagickDraw
    {
        if (count($arguments) < 2) {
            $arguments[] = 0;
        }

        if ($invert) {
            $arguments = array_map(
                function ($argument) {
                    return $argument * -1;
                },
                $arguments,
            );
        }

        $draw->translate(...$arguments);
        return $draw;
    }

    protected function transformScale(ImagickDraw $draw, array $arguments, bool $invert = false): ImagickDraw
    {
        if (count($arguments) < 2) {
            $arguments[] = 0;
        }

        if ($invert) {
            $arguments = array_map(
                function ($argument) {
                    return 1 / $argument;
                },
                $arguments,
            );
        }

        $draw->scale(...$arguments);
        return $draw;
    }

    protected function transform(ImagickDraw $draw, bool $invert = false): ImagickDraw
    {
        $instructions = $this->transformInstructions();

        if ($invert) {
            $instructions = array_reverse($instructions);
        }

        foreach ($instructions as ['command' => $command, 'arguments' => $arguments]) {
            switch ($command) {
                case 'matrix':
                    $draw = $this->transformMatrix($draw, $arguments, $invert);
                    break;
                case 'translate':
                    $draw = $this->transformTranslate($draw, $arguments, $invert);
                    break;
                case 'scale':
                    $draw = $this->transformScale($draw, $arguments, $invert);
                    break;
                default:
                    throw new UnsupportedTransformException();
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

    protected function whileTransformed(ImagickDraw $draw, Closure $operations): ImagickDraw
    {
        $this->setFill($draw);
        $this->transform($draw);
        $operations($draw);
        $this->transform($draw, true);

        return $draw;
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        $this->setFill($draw);
        return $draw;
    }
}
