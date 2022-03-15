<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 16:31
 */

namespace BartDecorte\ImagickSvg;

use BartDecorte\ImagickSvg\Exceptions\UnsupportedTransformException;
use BartDecorte\ImagickSvg\Util\Matrix;
use Closure;
use ImagickDraw;
use XMLReader;

abstract class Shape
{
    protected ?string $fill;
    protected ?string $transform;

    public function __construct(protected XMLReader $reader)
    {
        $this->fill = $this->reader->getAttribute('fill');
        $this->transform = $this->reader->getAttribute('transform');
    }

    protected function transformInstructions(): array
    {
        if (! $this->transform) {
            return [];
        }

        $matches = [];
        preg_match_all('/([a-zA-Z]+)\(([^)]+)\)/', $this->transform, $matches);

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

    protected function transformRotate(ImagickDraw $draw, float $rotation, bool $invert = false): ImagickDraw
    {
        if ($invert) {
            $rotation *= -1;
        }

        $draw->rotate($rotation);

        return $draw;
    }

    protected function transformSkewX(ImagickDraw $draw, float $rotation, bool $invert = false): ImagickDraw
    {
        if ($invert) {
            $rotation *= -1;
        }

        $draw->skewX($rotation);

        return $draw;
    }

    protected function transformSkewY(ImagickDraw $draw, float $rotation, bool $invert = false): ImagickDraw
    {
        if ($invert) {
            $rotation *= -1;
        }

        $draw->skewY($rotation);

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
                case 'rotate':
                    $draw = $this->transformRotate($draw, $arguments[0], $invert);

                    break;
                case 'skewX':
                    $draw = $this->transformSkewX($draw, $arguments[0], $invert);

                    break;
                case 'skewY':
                    $draw = $this->transformSkewY($draw, $arguments[0], $invert);

                    break;
                default:
                    throw new UnsupportedTransformException();
            }
        }

        return $draw;
    }

    protected function setFill(ImagickDraw $draw)
    {
        if (! $this->fill) {
            return;
        }

        $draw->setFillColor($this->fill);
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
