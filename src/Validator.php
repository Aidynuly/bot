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
        if (self::matchOldVRP($number)) {
            return self::matchOldVRP($number);
        } else if (self::matchNewVrp($number)) {
            return self::matchNewVrp($number);
        } else if (self::matchNewMotorcycleVrp($number)) {
            return self::matchNewMotorcycleVrp($number);
        } else if (self::matchOldMotorcycleVrp($number)) {
            return self::matchOldMotorcycleVrp($number);
        } else if (self::matchTruckVrpNew($number)) {
            return self::matchTruckVrpNew($number);
        } else if (self::matchTruckVrpOld($number)) {
            return self::matchTruckVrpOld($number);
        } else if (self::matchNewNonResidentVrp($number)) {
            return self::matchNewNonResidentVrp($number);
        } else if (self::matchOldNonResidentsVrp($number)) {
            return self::matchOldNonResidentsVrp($number);
        } else if (self::matchDiplomaticVrp($number)) {
            return self::matchDiplomaticVrp($number);
        } else if (self::matchNewMVD($number)) {
            return self::matchNewMVD($number);
        } else if (self::matchOldMVD($number)) {
            return self::matchOldMVD($number);
        }

        return false;
    }


    public static function matchOldVRP(string $vrp)
    {
        $pattern = '/^[a-zA-Z]{1}[0-9]{3}[a-zA-Z]{2,3}$/';

        return preg_match($pattern, $vrp);
    }

    public static function matchNewVrp(string $vrp)
    {
        $pattern = '/^[0-9]{3}[a-zA-Z]{2,3}[0-9]{2}$/';

        return preg_match($pattern, $vrp);
    }

    public static function matchTruckVrpOld(string $vrp)
    {
        $pattern = '/^[0-9]{4}[a-zA-Z]{2}$/';

        return preg_match($pattern, $vrp);
    }

    public static function matchTruckVrpNew(string $vrp)
    {
        $pattern = '/^[0-9]{2}[a-zA-Z]{3}[0-9]{2}$/';

        return preg_match($pattern, $vrp);
    }

    public static function matchOldMotorcycleVrp(string $vrp)
    {
        $pattern = '/^[0-9]{4}[a-zA-Z]{2}$/';

        return preg_match($pattern, $vrp);
    }

    public static function matchNewMotorcycleVrp(string $vrp)
    {
        $pattern = '/^[0-9]{2}[a-zA-Z]{2}[0-9]{2}$/';

        return preg_match($pattern, $vrp);
    }

    public static function matchOldNonResidentsVrp(string $vrp)
    {
        $pattern = '/^[K,M,H,P]{1}[0-9]{6}$/';

        return preg_match($pattern, $vrp);
    }

    /**
     * Example: [F or C] 0001 02. Last two digits show region
     * TODO: Add logic for H to distinguish from old version of VRP
     *
     * @param string $vrp
     * @return int
     */
    public static function matchNewNonResidentVrp(string $vrp)
    {
        $pattern = '/^[F,C,K,M,D]{1}[0-9]{6}$/';

        return preg_match($pattern, $vrp);
    }

    public static function matchOldMVD(string $vrp)
    {
        $pattern = '/^[a-zA-Z]{1}[0-9]{3}[kK,pP]{2}$/';

        return preg_match($pattern,$vrp);
    }

    public static function matchNewMVD(string $vrp)
    {
        $pattern = '/^[0-9]{6}$/';

        return preg_match($pattern,$vrp);
    }

    public static function matchDiplomaticVrp(string $vrp)
    {
        $pattern = '/^[H]{1}[C]{1}[0-9]{4}$/';

        return preg_match($pattern,$vrp);
    }

}