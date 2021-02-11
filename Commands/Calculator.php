<?php
$TS_TER = array(
    'Алматинская область'               =>  1.78,
    'Южно-Казахстанская область'        =>  1.01,
    'Восточно-Казахстанская область'    =>  1.96,
    'Костанайская область'              =>  1.95,
);
$TS_TYPE = array(
    'Легковые' =>                2.09,
    'Грузовые' =>                3.98,
    'Троллейбусы, трамваи'=>     2.33,
);
$BONUS_MALUS = array(
    'Класс М' => 2.45,
    'Класс 0' => 2.30,
    'Класс 1' => 1.55,
    'Класс 2' => 1.40,
    'Класс 3' => 1.00,
    'Класс 4' => 0.95,
    'Класс 5' => 0.90,
    'Класс 6' => 0.85,
    'Класс 7' => 0.80,
    'Класс 8' => 0.75,
    'Класс 9' => 0.70,
    'Класс 10' => 0.65,
    'Класс 11' => 0.60,
    'Класс 12' => 0.55,
    'Класс 13' => 0.50
);



$iin = '011203550384';
$tsNum = 'kz679YMA16';
$typeCar = 'Легковые';
$dateDriverLicense = "19.07.2020";
$bonusMalus = 'Класс 3';

$MRP = 2778;
$age = intval(getAge($iin));
$territory = getRegion($tsNum);

$territory_coef = $TS_TER[$territory];
$type_coef = $TS_TYPE[$typeCar];
$driverLicense_coef = 0;
$vehicleYear_coef = 0;
$bonusMalus_coef = $BONUS_MALUS[$bonusMalus];
$experience = getExperience($dateDriverLicense);

if ($age < 25) {
    if ($experience < 2) $driverLicense_coef = 1.10;
    else $driverLicense_coef = 1.05;
}
else {
    if ($experience < 2) $driverLicense_coef = 1.05;
    else $driverLicense_coef = 1.00;
}

$vehicleYear = 7;

if ($vehicleYear <= 7) {
    $vehicleYear_coef = 1.00;
}else $vehicleYear_coef = 1.10;


function getRegion($t)
{
    $first = intval(substr($t,8,9));
    $region = "";
    switch ($first){
        case 1: $region = "Алматинская область"; break;
        case 2: $region = "Костанайская область"; break;
        case 3: $region = "Южно-Казахстанская область"; break;
        case 16: $region = "Восточно-Казахстанская область"; break;
        // Continue...
    }
    return $region;
}
function getAge($i)
{
    $time = date('Y-m-d');

    $first_time = substr($i, 0, 2);
    $second_time = substr($i, 2, 2);
    $third_time = substr($i, 4, 2);

    if ($first_time > 21) {
        $first_final = "19" . "" . $first_time . "-" . $second_time . "-" . $third_time;
        $origin = new DateTime($first_final);
        $tar = new DateTime($time);
        $ans = $origin->diff($tar);
        return $ans->format('%y');
    } else {
        $first_final = "20" . "" . $first_time . "-" . $second_time . "-" . $third_time;
        $origin = new DateTime($first_final);
        $tar = new DateTime($time);
        $ans = $origin->diff($tar);
        return $ans->format('%y');
    }
}
function getExperience($i)
{
    $time = date('d.m.Y');
    $origin = new DateTime($time);
    $tar = new DateTime($i);
    $ans = $tar->diff($origin);
    return $ans->format('%y');
}


$ans = $MRP * $territory_coef * $type_coef * $driverLicense_coef * $vehicleYear_coef * $bonusMalus_coef;

echo $ans;