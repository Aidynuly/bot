<?php


namespace App;

/**
 * Class Validator
 * @package App
 */
class Validator
{
    /**
     * TODO: MUST be 12 chars long
     * TODO: MUST contain only digits
     * @param string $iin
     * @return bool
     */
    public static function validateIIN(string $iin) : bool
    {
        return mb_strlen($iin) === 12;
    }

    /**
     * TODO: Look up all regex patterns and validate against them
     * @param string $number
     * @return bool
     */
    public static function validateVehicleNumber(string $number) : bool
    {
        return true;
    }
}