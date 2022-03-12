<?php
/**
 * Created by Bart Decorte
 * Date: 12/03/2022
 * Time: 15:04
 */
namespace BartDecorte\ImagickSvg;

use ImagickDraw;

class Path extends Shape
{
    protected function dAttributeValue(): string
    {
        return preg_replace('/<path[^\/]*d="([^"]+)"[^\/]*\/>/', '$1', $this->contents);
    }

    protected function instructions()
    {
        $instructions = preg_replace('/([a-zA-Z])/', '|\1', $this->dAttributeValue());
        $instructions = explode('|', $instructions);
        array_shift($instructions);
        return array_map(function ($instruction) {
            $argument_string = substr($instruction, 1);
            $arguments = [];
            $i = 0;
            while (strlen($argument_string)) {
                $pattern = '/^,?(-?[0-9]*\.?[0-9]*)(.*)/';
                $arguments[] = floatval(preg_replace($pattern, '\1', $argument_string));
                $argument_string = preg_replace($pattern, '\2', $argument_string);
                $i++;
            }
            return [
                'command' => substr($instruction, 0, 1),
                'arguments' => $arguments,
            ];
        }, $instructions);
    }

    protected function pathMoveToAbsolute(ImagickDraw $draw, ...$arguments)
    {
        $moveToArguments = array_slice($arguments, 0, 2);
        $draw->pathMoveToAbsolute(...$moveToArguments);

        if (count($arguments) > 2) {
            $this->pathLineToAbsolute($draw, ...array_slice($arguments, 2));
        }
    }

    protected function pathMoveToRelative(ImagickDraw $draw, ...$arguments)
    {
        $moveToArguments = array_slice($arguments, 0, 2);
        $draw->pathMoveToRelative(...$moveToArguments);

        if (count($arguments) > 2) {
            $this->pathLineToRelative($draw, ...array_slice($arguments, 2));
        }
    }

    protected function pathLineToRelative(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 2) {
            $draw->pathLineToRelative($arguments[$i], $arguments[$i + 1]);
        }
    }

    protected function pathLineToAbsolute(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 2) {
            $draw->pathLineToAbsolute($arguments[$i], $arguments[$i + 1]);
        }
    }

    protected function pathLineToHorizontalAbsolute(ImagickDraw $draw, ...$arguments)
    {
        foreach ($arguments as $argument) {
            $draw->pathLineToHorizontalAbsolute($argument);
        }
    }

    protected function pathLineToHorizontalRelative(ImagickDraw $draw, ...$arguments)
    {
        foreach ($arguments as $argument) {
            $draw->pathLineToHorizontalRelative($argument);
        }
    }

    protected function pathLineToVerticalAbsolute(ImagickDraw $draw, ...$arguments)
    {
        foreach ($arguments as $argument) {
            $draw->pathLineToVerticalAbsolute($argument);
        }
    }

    protected function pathLineToVerticalRelative(ImagickDraw $draw, ...$arguments)
    {
        foreach ($arguments as $argument) {
            $draw->pathLineToVerticalRelative($argument);
        }
    }

    protected function pathEllipticArcAbsolute(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 7) {
            $draw->pathEllipticArcAbsolute(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
                $arguments[$i + 4],
                $arguments[$i + 5],
                $arguments[$i + 6],
            );
        }
    }

    protected function pathEllipticArcRelative(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 7) {
            $draw->pathEllipticArcRelative(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
                $arguments[$i + 4],
                $arguments[$i + 5],
                $arguments[$i + 6],
            );
        }
    }

    protected function pathCurveToAbsolute(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 6) {
            $draw->pathCurveToAbsolute(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
                $arguments[$i + 4],
                $arguments[$i + 5],
            );
        }
    }

    protected function pathCurveToRelative(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 6) {
            $draw->pathCurveToRelative(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
                $arguments[$i + 4],
                $arguments[$i + 5],
            );
        }
    }

    protected function pathCurveToSmoothAbsolute(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 4) {
            $draw->pathCurveToSmoothAbsolute(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
            );
        }
    }

    protected function pathCurveToSmoothRelative(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 4) {
            $draw->pathCurveToSmoothRelative(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
            );
        }
    }

    protected function pathCurveToQuadraticBezierAbsolute(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 4) {
            $draw->pathCurveToQuadraticBezierAbsolute(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
            );
        }
    }

    protected function pathCurveToQuadraticBezierRelative(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 4) {
            $draw->pathCurveToQuadraticBezierRelative(
                $arguments[$i],
                $arguments[$i + 1],
                $arguments[$i + 2],
                $arguments[$i + 3],
            );
        }
    }

    protected function pathCurveToQuadraticBezierSmoothAbsolute(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 2) {
            $draw->pathCurveToQuadraticBezierSmoothAbsolute(
                $arguments[$i],
                $arguments[$i + 1],
            );
        }
    }

    protected function pathCurveToQuadraticBezierSmoothRelative(ImagickDraw $draw, ...$arguments)
    {
        for ($i = 0; $i < count($arguments); $i += 2) {
            $draw->pathCurveToQuadraticBezierSmoothRelative(
                $arguments[$i],
                $arguments[$i + 1],
            );
        }
    }

    protected function pathClose($draw)
    {
        $draw->pathClose();
    }

    public function draw(ImagickDraw $draw): ImagickDraw
    {
        parent::draw($draw);
        $draw->pathStart();

        foreach ($this->instructions() as $instruction) {

            list(
                'command' => $command,
                'arguments' => $arguments,
                ) = $instruction;

            switch ($command) {
                case 'M';
                    $this->pathMoveToAbsolute($draw, ...$arguments);
                    break;
                case 'm';
                    $this->pathMoveToRelative($draw, ...$arguments);
                    break;
                case 'L':
                    $this->pathLineToAbsolute($draw, ...$arguments);
                    break;
                case 'l':
                    $this->pathLineToRelative($draw, ...$arguments);
                    break;
                case 'H':
                    $this->pathLineToHorizontalAbsolute($draw, ...$arguments);
                    break;
                case 'h':
                    $this->pathLineToHorizontalRelative($draw, ...$arguments);
                    break;
                case 'V':
                    $this->pathLineToVerticalAbsolute($draw, ...$arguments);
                    break;
                case 'v':
                    $this->pathLineToVerticalRelative($draw, ...$arguments);
                    break;
                case 'A':
                    $this->pathEllipticArcAbsolute($draw, ...$arguments);
                    break;
                case 'a':
                    $this->pathEllipticArcRelative($draw, ...$arguments);
                    break;
                case 'C':
                    $this->pathCurveToAbsolute($draw, ...$arguments);
                    break;
                case 'c':
                    $this->pathCurveToRelative($draw, ...$arguments);
                    break;
                case 'S':
                    $this->pathCurveToSmoothAbsolute($draw, ...$arguments);
                    break;
                case 's':
                    $this->pathCurveToSmoothRelative($draw, ...$arguments);
                    break;
                case 'Q':
                    $this->pathCurveToQuadraticBezierAbsolute($draw, ...$arguments);
                    break;
                case 'q':
                    $this->pathCurveToQuadraticBezierRelative($draw, ...$arguments);
                    break;
                case 'T':
                    $this->pathCurveToQuadraticBezierSmoothAbsolute($draw, ...$arguments);
                    break;
                case 't':
                    $this->pathCurveToQuadraticBezierSmoothRelative($draw, ...$arguments);
                    break;
                case 'Z':
                case 'z':
                    $this->pathClose($draw);
                    break;

            }
        }
        $draw->pathFinish();
        return $draw;
    }
}
