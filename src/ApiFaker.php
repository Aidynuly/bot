<?php


namespace App;
use DateTime;

/**
 * Class ApiFaker
 * @package App
 */
class ApiFaker
{
    const VEHICLE_TYPE = [
        4       =>  "Легковые",
        5       =>  "Автобусы до 16 п.м.",
        6       =>  "Грузовые",
        7       =>  "Троллейбусы, трамваи",
        8       =>  "Мототранспорт",
        10      =>  "Прицепы (полуприцепы)",
        11      =>  "Автобусы > 16 п.м.",
        12      =>  "Воздушный",
        13      =>  "Морской",
        14      =>  "Железнодорожный",
        15      =>  "Спец.техника",
        17      =>  "Мотоциклы и мотороллеры",
        19      =>  "Прицеп к грузовой а/м"
    ];



    const VEHICLE_REGIONS = [
        1               =>  'Алматинская область',
        2               =>  'Южно-Казахстанская область',
        3               =>  'Восточно-Казахстанская область',
        4               =>  'Костанайская область',
        5               =>  'Карагандинская область',
        6               =>  'Северо-Казахстанская область',
        7               =>  'Акмолинская область',
        8               =>  'Павлодарская область',
        9               =>  'Жамбылская область',
        10              =>  'Актюбинская область',
        11              =>  'Западно-Казахстанская область',
        12              =>  'Кызылординская область',
        13              =>  'Атырауская область',
        14              =>  'Мангистауская область',
        15              =>  'г. Алматы',
        16              =>  'г. Астана',
        17              =>  'Временный въезд',
        18              =>  'Временная регистрация',
        19              =>  'г. Шымкент',
        20              =>  'Туркестанская область',
        21              =>  'Другое'
    ];




    /**
     * @param string $iin
     * @return int[]
     */
    public static function getClientData(string $iin) : array
    {
        $ch = curl_init();

        $postFields =[
            'iin' => $iin,
        ];
        $token = '$2y$10$DbnhBd/IwMavqbkOBxo.FOFA93hVO3M4Rc53zg1M/NO5/nY9bP7dG';
        $h = [];
        $h[] = 'Authorization: Bearer ' . $token;

        curl_setopt($ch, CURLOPT_URL, "https://agent.avtoadvokat.kz/driver/get-class");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $h);

        $rest = curl_exec($ch);

        $json = json_decode($rest);

        curl_close($ch);

        return [
            'bonus_malus'   => $json->classBM,
            'years_old'     => self::getAgeIin($iin)
        ];
    }

    /**
     * @param string $vehicleNumber
     * @return array
     */
    public static function getVehicleData(string $vehicleNumber) : array
    {
        $vehicleNumber = mb_strtoupper($vehicleNumber);
        $ch = curl_init();

        $postFields =[
            'number' => $vehicleNumber,
        ];
        $token = '$2y$10$DbnhBd/IwMavqbkOBxo.FOFA93hVO3M4Rc53zg1M/NO5/nY9bP7dG';
        $h = [];
        $h[] = 'Authorization: Bearer ' . $token;

        curl_setopt($ch, CURLOPT_URL, "https://agent.avtoadvokat.kz/driver/get-vehicle-info");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $h);

        $rest = curl_exec($ch);

        $json = json_decode($rest);

        curl_close($ch);

        return [
            'type'      => self::VEHICLE_TYPE[$json->type_id],
            'year'      => $json->year,
            'region'    => self::VEHICLE_REGIONS[$json->region_id],
        ];
    }


    public static function getAgeIin(string $i)
    {
        $time = date('Y-m-d');

        $first_time = substr($i, 0,2);
        $second_time = substr($i, 2,2);
        $third_time = substr($i, 4,2);

        if($first_time>21){
            $first_final = "19" . "" . $first_time . "-" . $second_time . "-" . $third_time;
            $origin = new DateTime($first_final);
            $tar = new DateTime($time);
            $ans = $origin->diff($tar);
            return $ans->format('%y');
        }
        else {
            $first_final = "20" . "" . $first_time . "-" . $second_time . "-" . $third_time;
            $origin = new DateTime($first_final);
            $tar = new DateTime($time);
            $ans = $origin->diff($tar);
            return $ans->format('%y');
        }

    }
}
