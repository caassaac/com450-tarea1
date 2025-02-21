<?php

namespace App\Services\Discount;

/**
 * Clase FixedDiscount
 * Implementa una estrategia de descuento que resta una cantidad fija del total.
 */
class FixedDiscount implements DiscountInterface
{
    // La cantidad fija de descuento.
    protected $fixedAmount;

    /**
     * Constructor.
     * @param float $fixedAmount La cantidad fija a descontar.
     */
    public function __construct(float $fixedAmount)
    {
        $this->fixedAmount = $fixedAmount;
    }

    /**
     * Aplica el descuento fijo a la cantidad dada.
     * Se asegura de que el total final no sea menor que cero.
     * @param float $amount La cantidad original.
     * @return float La cantidad después de aplicar el descuento.
     */
    public function applyDiscount(float $amount): float
    {
        $total = $amount - $this->fixedAmount;
        // Si el descuento excede la cantidad, retorna 0.
        return $total < 0 ? 0 : round($total, 2);
    }
}
