<?php

namespace App\ValueObjects;

use App\Measurements\MeasurementEnum;
use Brick\Math\BigDecimal;

class ConvertableUnit
{
    public MeasurementEnum $unit;

    public BigDecimal $quantity;

    public function __construct(MeasurementEnum $unit, mixed $quantity = 1)
    {
        $this->unit     = $unit;
        $this->quantity = BigDecimal::of($quantity);
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function getUnit(): MeasurementEnum
    {
        return $this->unit;
    }

    public function getQuantity(): BigDecimal
    {
        return $this->quantity;
    }

    public function getQuantityAsString(): string
    {
        return (string) $this->quantity;
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function convertTo(MeasurementEnum $unit): self
    {
        if (get_class($this->unit) != get_class($unit)) {
            throw new \Exception('Measurement System Mismatch');
        }

        $by = $unit->conversionFactor()->exactlyDividedBy($this->unit->conversionFactor());

        $newQuantity = $this->quantity->exactlyDividedBy($by);

        return new self($unit, $newQuantity);
    }

    /**
     * Returns a new object with quantity converted to the base unit of the system.
     * UsWeight -> oz, UsVolume -> floz
     *
     * @return $this
     */
    public function convertToBase(): self
    {
        $factor = $this->unit->conversionFactor();

        $newQuantity = $this->quantity->multipliedBy($factor);

        return new self($this->unit->getBaseUnit(), $newQuantity);
    }

    public function litersToFloz()
    {
        // 1 Liter = 33.81413 floz
        return $this->quantity->multipliedBy('33.81413');
    }

    public function flozToLiters()
    {
        // 1floz = 0.02957344 L
        return $this->quantity->multipliedBy('0.02957344');
    }

    public function ozToGram()
    {

    }

    public function gramToOz()
    {

    }

}
