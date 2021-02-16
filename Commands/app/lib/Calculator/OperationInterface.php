<?php
namespace app\Calculator;
interface  OperationInterface
{
    public function evaluate(array $operands = array());

    public function getRegion();
    public function getAge();
    public function getExperience();
}