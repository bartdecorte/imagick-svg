<?php
/**
 * Created by Bart Decorte
 * Date: 14/03/2022
 * Time: 18:09
 */
namespace BartDecorte\ImagickSvg\Exceptions;

use Exception;

class UnsupportedElementException extends Exception
{
    public function __construct(string $element)
    {
        parent::__construct("$element is not a supported element");
    }
}
