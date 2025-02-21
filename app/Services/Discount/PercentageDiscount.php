<?php

namespace App\Services\Discount;

/**
 * Clase PercentageDiscount
 * Implementa una estrategia de descuento que reduce el total en un porcentaje fijo.
 */
class PercentageDiscount implements DiscountInterface
{
    // Porcentaje de descuento (por ejemplo, 10 para un 10%).
    protected $percentage;

    /**
     * Constructor.
     * @param float $percentage El porcentaje de descuento.
     */
    public function __construct(float $percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * Aplica el descuento porcentual a la cantidad dada.
     * @param float $amount La cantidad original.
     * @return float La cantidad descontada.
     */
    public function applyDiscount(float $amount): float
    {
        // Calcula el descuento restando el porcentaje del monto.
        $discounted = $amount - ($amount * ($this->percentage / 100));
        return round($discounted, 2);
    }
}
