<?php

namespace App\Services;

use App\Models\Item;
use App\Services\Discount\DiscountInterface;

/**
 * Clase Cart
 * Esta clase de servicio representa un carrito de compras.
 * Se encarga de gestionar una colección de artículos, calcular el subtotal y aplicar estrategias de descuento.
 */
class Cart
{
    // Array protegido que almacenará los objetos Item añadidos al carrito.
    protected $items = [];

    /**
     * Agregar un artículo al carrito.
     * Si el artículo (basado en su nombre) ya existe en el carrito, se actualiza su cantidad.
     * De lo contrario, se añade como un nuevo elemento.
     * @param Item $item El artículo a agregar.
     */
    public function addItem(Item $item): void
    {
        // Recorre los artículos existentes para verificar si ya está presente.
        foreach ($this->items as $existingItem) {
            if ($existingItem->name === $item->name) {
                // Si existe, se incrementa la cantidad.
                $existingItem->quantity += $item->quantity;
                return;
            }
        }
        // Si no existe, se agrega el nuevo artículo.
        $this->items[] = $item;
    }

    /**
     * Eliminar un artículo del carrito por su nombre.
     * @param string $itemName El nombre del artículo a eliminar.
     */
    public function removeItem(string $itemName): void
    {
        foreach ($this->items as $index => $item) {
            if ($item->name === $itemName) {
                unset($this->items[$index]);
                // Reindexa el array para mantener el orden.
                $this->items = array_values($this->items);
                return;
            }
        }
    }

    /**
     * Obtener todos los artículos actuales del carrito.
     * @return array Un array de objetos Item.
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Calcular el subtotal de todos los artículos en el carrito.
     * @return float La suma de (precio * cantidad) de cada artículo.
     */
    public function getSubtotal(): float
    {
        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += $item->price * $item->quantity;
        }
        return $subtotal;
    }

    /**
     * Aplicar una estrategia de descuento al subtotal y devolver el total final.
     * Este método acepta cualquier implementación de descuento que cumpla con DiscountInterface.
     * @param DiscountInterface $discount La estrategia de descuento a aplicar.
     * @return float El total final después de aplicar el descuento.
     */
    public function applyDiscount(DiscountInterface $discount): float
    {
        $subtotal = $this->getSubtotal();
        return $discount->applyDiscount($subtotal);
    }
}
