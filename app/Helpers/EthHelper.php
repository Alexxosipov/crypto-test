<?php


namespace App\Helpers;


use BCMathExtended\BC;
use EthereumPHP\Types\Wei;
use http\Exception\InvalidArgumentException;

class EthHelper
{
    public static function getWeiFromHexDec(string $hexDec) :string
    {
        try {
            $wei = new Wei(BC::hexdec($hexDec));
            return $wei->toEther();
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid value");
        }

    }
}
