<?php

namespace App\Models;

/**
 * Clase Item
 * Esta clase representa un producto o artículo que se puede agregar al carrito de compras.
 * Contiene información básica como el nombre, precio y cantidad del artículo.
 */
class Item
{
    // Propiedades públicas que almacenan los datos del artículo.
    public $name;      // Nombre del artículo
    public $price;     // Precio por unidad del artículo
    public $quantity;  // Cantidad del artículo (por defecto 1)

    /**
     * Constructor.
     * @param string $name     El nombre del artículo.
     * @param float  $price    El precio por unidad.
     * @param int    $quantity La cantidad (valor predeterminado 1).
     */
    public function __construct(string $name, float $price, int $quantity = 1)
    {
        $this->name     = $name;
        $this->price    = $price;
        $this->quantity = $quantity;
    }
}
