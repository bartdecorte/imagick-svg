<?php
/**
 * Created by Bart Decorte
 * Date: 14/03/2022
 * Time: 19:51
 */

namespace BartDecorte\ImagickSvg\Util;

class Matrix
{
    public function __construct(
        public float $a,
        public float $b,
        public float $c,
        public float $d,
        public float $e,
        public float $f,
    ) {
        //
    }

    protected function asArray(): array
    {
        return [
            [$this->a, $this->c, $this->e],
            [$this->b, $this->d, $this->f],
            [0.0, 0.0, 1.0],
        ];
    }

    /**
     * https://gist.github.com/unix1/7510208
     */
    public function invert()
    {
        $matrix = $this->asArray();
        $size = count($matrix);

        $identity = $this->identity($size);
        for ($i = 0; $i < $size; ++$i) {
            $matrix[$i] = array_merge($matrix[$i], $identity[$i]);
        }

        for ($j = 0; $j < $size - 1; ++$j) {
            for ($i = $j + 1; $i < $size; ++$i) {
                if ($matrix[$i][$j] !== 0.0) {
                    $scalar = $matrix[$j][$j] / $matrix[$i][$j];
                    for ($k = $j; $k < $size * 2; ++$k) {
                        $matrix[$i][$k] *= $scalar;
                        $matrix[$i][$k] -= $matrix[$j][$k];
                    }
                }
            }
        }

        for ($j = $size - 1; $j > 0; --$j) {
            for ($i = $j - 1; $i >= 0; --$i) {
                if ($matrix[$i][$j] !== 0.0) {
                    $scalar = $matrix[$j][$j] / $matrix[$i][$j];
                    for ($k = $i; $k < $size * 2; ++$k) {
                        $matrix[$i][$k] *= $scalar;
                        $matrix[$i][$k] -= $matrix[$j][$k];
                    }
                }
            }
        }

        for ($j = 0; $j < $size; ++$j) {
            if ($matrix[$j][$j] !== 1.0) {
                $scalar = 1 / $matrix[$j][$j];
                for ($k = $j; $k < $size * 2; ++$k) {
                    $matrix[$j][$k] *= $scalar;
                }
            }
        }

        $inverse = [];
        for ($i = 0; $i < $size; ++$i) {
            $inverse[$i] = array_slice($matrix[$i], $size);
        }

        $this->a = $inverse[0][0];
        $this->b = $inverse[1][0];
        $this->c = $inverse[0][1];
        $this->d = $inverse[1][1];
        $this->e = $inverse[0][2];
        $this->f = $inverse[1][2];
    }

    /**
     * https://gist.github.com/unix1/7510208
     */
    protected function identity(int $size): array
    {
        $identity = [];
        for ($i = 0; $i < $size; ++$i) {
            for ($j = 0; $j < $size; ++$j) {
                $identity[$i][$j] = $i == $j ? 1 : 0;
            }
        }

        return $identity;
    }
}
