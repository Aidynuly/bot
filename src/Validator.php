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
        return false;

    }

    /**
     * TODO: Look up all regex patterns and validate against them
     * @param string $number
     * @return bool
     */
    public static function validateVehicleNumber(string $number) : bool
    {
        $num = strtoupper($number);
        $onlyDigits = preg_replace('/[^0-9]/', '', $num);
        $onlyLetters = preg_replace('/[^A-Z]/', '', $num);

        //Образ 1993
        if ( strlen($num) <= 7 && $onlyDigits === 3 ) {return true;}
        if ( strlen($onlyDigits) === 3 && $onlyLetters === "AV") { return true;}
        if ( strlen($onlyDigits) === 3 && $onlyLetters === "ADM") { return true;}
        if ( strlen($onlyDigits) === 3 && $onlyLetters === "AST") { return true;}
        if ( strlen($onlyDigits) === 3 && $onlyLetters === "UD") { return true;}
        if ( strlen($onlyDigits) === 2 && $onlyLetters === "SK") { return true;}
        if ( strlen($num <= 6) && $onlyLetters[1] === "N" && $onlyLetters[2] === "NS") { return true;}
        if ( strlen($num  === 5) && $onlyLetters[1] === "K" && $onlyLetters[2] === "P") {return true;}
        if ( (strlen($onlyDigits) === 4 && strlen($onlyLetters) === 2) || (strlen($onlyDigits) === 6 && strlen($onlyLetters) === 1)) { return true;}
        if( $onlyLetters === "CMD" && strlen($onlyDigits) === 4 ) {return true;}
        //Образ 2012
        if( strlen($onlyDigits) === 5 && (strlen($onlyLetters) === 2 || strlen($onlyLetters) === 3)) {return true;}
        if( strlen($onlyDigits) === 6) { return true;}
        if( strlen($onlyDigits) === 3 && strlen($onlyLetters) === 4 ) return true;
        return false;
    }
}