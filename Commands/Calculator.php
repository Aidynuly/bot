<?php
$TS_TER = [
    'Алматинская область'               =>  1.78,
    'Южно-Казахстанская область'        =>  1.01,
    'Восточно-Казахстанская область'    =>  1.96,
    'Костанайская область'              =>  1.95,
];
$TS_TYPE = [
    'Легковые' =>                2.09,
    'Грузовые' =>                3.98,
    'Троллейбусы, трамваи'=>     2.33,
];
$BONUS_MALUS = [
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
];



$iin = '011203550384';
$tsNum = 'kz679YMA16';
$typeCar = 'Легковые';
$dateDriverLicense = "19.07.2020";
$bonusMalus = 'Класс 3';

$MRP = 2778;
$age = intval(getAge($iin));
$territory = getRegion($tsNum);

$territoryCoef = $TS_TER[$territory];
$typeCoef = $TS_TYPE[$typeCar];
$driverLicenseCoef = 0;
$vehicleYearCoef = 0;
$bonusMalusCoef = $BONUS_MALUS[$bonusMalus];
$experience = getExperience($dateDriverLicense);

if ($age < 25) {
    if ($experience < 2) {
        $driverLicenseCoef = 1.10;
    } else {
        $driverLicenseCoef = 1.05;
    }
}
else {
    if ($experience < 2) {
        $driverLicenseCoef = 1.05;
    } else {
        $driverLicenseCoef = 1.00;
    }
}

$vehicleYear = 7;

if ($vehicleYear <= 7) {
    $vehicleYearCoef = 1.00;
} else {
    $vehicleYearCoef = 1.10;
}


function getRegion($t)
{
    $first = intval(substr($t,8,9));
    $region = "";
    switch ($first){
        case 1: 
            $region = "Алматинская область"; 
            break;
        case 2: 
            $region = "Костанайская область"; 
            break;
        case 3: 
            $region = "Южно-Казахстанская область"; 
            break;
        case 16: 
            $region = "Восточно-Казахстанская область"; 
            break;
        // Continue...
    }
    return $region;
}
function getAge($i)
{
    $time = date('Y-m-d');

    $firstTime = substr($i, 0, 2);
    $secondTime = substr($i, 2, 2);
    $thirdTime = substr($i, 4, 2);

    if ($firstTime > 21) {
        $firstFinal = "19" . "" . $firstTime . "-" . $secondTime . "-" . $thirdTime;
        $origin = new DateTime($firstFinal);
        $now = new DateTime($time);
        $answer = $origin->diff($now);
        return $answer->format('%y');
    } else {
        $firstFinal = "20" . "" . $firstTime . "-" . $secondTime . "-" . $thirdTime;
        $origin = new DateTime($firstFinal);
        $now = new DateTime($time);
        $answer = $origin->diff($now);
        return $answer->format('%y');
    }
}
function getExperience($i)
{
    $time = date('d.m.Y');
    $origin = new DateTime($time);
    $now = new DateTime($i);
    $answer = $now->diff($origin);
    return $answer->format('%y');
}


$answer = $MRP * $territoryCoef * $typeCoef * $driverLicenseCoef * $vehicleYearCoef * $bonusMalusCoef;

echo $answer;
