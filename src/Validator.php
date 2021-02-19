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
        if (is_numeric($iin)) {
            if (mb_strlen($iin) === 12) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * TODO: Look up all regex patterns and validate against them
     * @param string $number
     * @return bool
     */
    public static function validateVehicleNumber(string $number) : bool
    {
        $onlyDigits = preg_replace('/[^0-9]/', '', $number);
        if (strlen($number) > 8) {
            return false;
        }
        if (strlen($onlyDigits) > 5 || strlen($onlyDigits) <= 3) {
            return false;
        }
        return true;
    }
}