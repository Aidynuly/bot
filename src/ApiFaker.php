<?php


namespace App;


/**
 * Class ApiFaker
 * @package App
 */
class ApiFaker
{
    /**
     * @param string $iin
     * @return int[]
     */
    public static function getClientData(string $iin) : array
    {
        return [
            'bonus_malus' => 6,
            'years_old' => 30
        ];
    }

    /**
     * @param string $vehicleNumber
     * @return array
     */
    public static function getVehicleData(string $vehicleNumber) : array
    {
        return [
            'type' => 'Р›РµРіРєРѕРІС‹Рµ',
            'year' => 2010,
            'region' => 'Рі. РђР»РјР°С‚С‹',
        ];
    }
}
