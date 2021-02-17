<?php


namespace App;


/**
 * Class Calculator
 * @package App
 */
class Calculator
{
    const MRP = 2778;

    const VEHICLE_REGIONS_COEFFICIENTS = [];

    const VEHICLE_TYPES_COEFFICIENTS = [];

    /**
     * @var
     */
    private $iin;

    /**
     * @var
     */
    private $vehicleNumber;

    /**
     * Calculator constructor.
     * @param string $iin
     * @param string $vehicleNumber
     */
    public function __construct(string $iin, string $vehicleNumber)
    {
        $this->iin = $iin;
        $this->vehicleNumber = $vehicleNumber;
    }

    /**
     * @return int
     */
    public function getPolicyPrice() : int
    {
        return 42;
    }
}
