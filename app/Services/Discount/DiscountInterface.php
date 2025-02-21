<?php

namespace App\Services\Discount;

/**
 * Interfaz DiscountInterface
 * Esta interfaz define el contrato para las estrategias de descuento.
 * Cualquier clase que implemente esta interfaz debe proveer una implementación del método applyDiscount.
 */
interface DiscountInterface
{
    /**
     * Aplicar un descuento a una cantidad dada.
     * @param float $amount La cantidad original.
     * @return float La cantidad después de aplicar el descuento.
     */
    public function applyDiscount(float $amount): float;
}
