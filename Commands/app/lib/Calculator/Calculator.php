<?php
namespace app\Calculator;
use DateTime;
class Calculator
{
    protected $operands = array();
    private $iin;
    private $tsNum;
    private $typeCar;
    private $dateDriverLicense;
    private $bonusMalus;
    private $territory;
    private $carYear;

    # For the reciprocal result, return the multiplication of these coefficients
    const MRP = 2778;

    private $TS_TER = array(
        'Алматинская область'               =>  1.78,
        'Южно-Казахстанская область'        =>  1.01,
        'Восточно-Казахстанская область'    =>  1.96,
        'Костанайская область'              =>  1.95,
        'Карагандинская область'            =>  1.39,
        'Северо-Казахстанская область'      =>  1.33,
        'Акмолинская область'               =>  1.32,
        'Павлодарская область'              =>  1.63,
        'Жамбылская область'                =>  1.00,
        'Актюбинская область'               =>  1.35,
        'Западно-Казахстанская область'     =>  1.17,
        'Кызылординская область'            =>  1.09,
        'Атырауская область'                =>  2,69,
        'Мангистауская область'             =>  1.15,
        'Алматы'                            =>  2.96,
        'Астана'                            =>  2.2
    );

    private $TS_TYPE = array(
        'Легковые' =>                2.09,
        'Грузовые' =>                3.98,
        'Троллейбусы, трамваи'=>     2.33,
    );

    private $BONUS_MALUS = array(
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

    public function __construct($iin,$tsNum,$typeCar,$dateDriverLicense,$bonusMalus, $carYear)
    {
        $this->iin = $iin;
        $this->tsNum = $tsNum;
        $this->typeCar = $typeCar;
        $this->dateDriverLicense = $dateDriverLicense;
        $this->bonusMalus = $bonusMalus;
        $this->carYear = $carYear;
    }

    public function setIIN($iin){
        $this->iin = $iin;
    }

    public function getIIN(){
        return $this->iin;
    }

    public function setTsNum($tsNum){
        $this->tsNum = $tsNum;
    }

    public function getTsNum(){
        return $this->tsNum;
    }

    public function setTypeCar($typeCar){
        $this->typeCar = $typeCar;
    }

    public function getTypeCar(){
        return $this->typeCar;
    }

    public function setDateDriverLicense($dateDriverLicense){
        $this->dateDriverLicense = $dateDriverLicense;
    }

    public function getDateDriverLicense(){
        return $this->dateDriverLicense;
    }

    public function setBonusMalus($bonusMalus){
        $this->bonusMalus = $bonusMalus;
    }

    public function getBonusMalus(){
        return $this->bonusMalus;
    }

    public function setCarYear($carYear){
        $this->carYear = $carYear;
    }

    public function getCarYear(){
        return $this->carYear;
    }

    public function setTerritory($territory){
        $this->territory = $territory;
    }

    public function getTerritory(){
        $this->territory = $this->getRegion($this->tsNum);
        return $this->territory;
    }

    public function getTerritoryCoef(){
        $this->getTerritory();
        $this->territoryCoef = $this->TS_TER[$this->territory];
        return $this->territoryCoef;
    }

    public function getTypeCoef(){
        return $this->TS_TYPE[$this->typeCar];
    }

    public function getDriverLicenseCoef(){
        $experience = $this->getExperience($this->dateDriverLicense);
        $age = $this->getAge($this->iin);
        if($age< 25){
            if($experience<2){
                $driverLicenseCoef = 1.10;
            }
            else{
                $driverLicenseCoef = 1.05;
            }
        }
        else{
            if($experience<2){
                $driverLicenseCoef = 1.05;
            }
            else{
                $driverLicenseCoef = 1.00;
            }
        }
        return $driverLicenseCoef;
    }

    public function getVehicleYearCoef(){
        $car = $this->getCarYear();
        if($car <= 7) {
            $carYearCoef = 1.00;
        }
        else{
            $carYearCoef = 1.10;
        }
        return $carYearCoef;
    }

    public function getBonusMalusCoef(){
        return $this->BONUS_MALUS[$this->getBonusMalus()];
    }

    public function getRegion()
    {
        // TODO: Implement getRegion() method.
        $first = intval(substr($this->tsNum,6,7));
        $region = "";
        switch ($first){
            case 1: $region = "Астана"; break;
            case 2: $region = "Алматы"; break;
            case 3: $region = "Акмолинская область"; break;
            case 4: $region = "Актюбинская область"; break;
            case 5: $region = "Алматинская область"; break;
            case 6: $region = "Атырауская область"; break;
            case 7: $region = "Западно-Казахстанская область"; break;
            case 8: $region = "Жамбылская область"; break;
            case 9: $region = "Карагандинская область"; break;
            case 10: $region = "Костанайская область"; break;
            case 11: $region = "Кызылординская область"; break;
            case 12: $region = "Мангистауская область"; break;
            case 13: $region = "Южно-Казахстанская область"; break;
            case 14: $region = "Павлодарская область"; break;
            case 15: $region = "Северо-Казахстанская область"; break;
            case 16: $region = "Восточно-Казахстанская область"; break;
        }
        return $region;
    }

    public function getAge()
    {
        $time = date('Y-m-d');

        $first_time = substr($this->iin, 0, 2);
        $second_time = substr($this->iin, 2, 2);
        $third_time = substr($this->iin, 4, 2);

        if ($first_time > 21) {
            $first_final = "19" . "" . $first_time . "-" . $second_time . "-" . $third_time;
            $first = new \DateTimeImmutable($first_final);
            $now = new \DateTimeImmutable($time);
            $answer = $first->diff($now);
            return intval($answer->format('%y'));
        } else {
            $first_final = "20" . "" . $first_time . "-" . $second_time . "-" . $third_time;
            $first = new \DateTimeImmutable($first_final);
            $now = new \DateTimeImmutable($time);
            $answer = $first->diff($now);
            return intval($answer->format('%y'));
        }
    }

    public function getExperience()
    {
        $time = date('d.m.Y');
        $origin = new \DateTimeImmutable($time);
        $now = new \DateTimeImmutable($this->dateDriverLicense);
        $answer = $now->diff($origin);
        return $answer->format('%y');
    }

    public function Answer()
    {
        return self::MRP * $this->getTerritoryCoef() * $this->getTypeCoef() * $this->getDriverLicenseCoef() * $this->getVehicleYearCoef() * $this->getBonusMalusCoef();
    }

}