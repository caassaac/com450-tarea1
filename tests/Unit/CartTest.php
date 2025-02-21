<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Item;
use App\Services\Cart;
use App\Services\Discount\PercentageDiscount;
use App\Services\Discount\FixedDiscount;

/**
 * Clase CartTest
 * Esta clase de pruebas unitarias verifica la funcionalidad de la clase Cart y las estrategias de descuento.
 * Cada método de prueba cubre un escenario específico.
 */
class CartTest extends TestCase
{
    // Instancia de la clase Cart que se usará en las pruebas.
    protected $cart;

    /**
     * Configuración del entorno de prueba.
     * Este método se ejecuta antes de cada método de prueba.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->cart = new Cart();
    }

    /**
     * Prueba que al agregar un artículo, el subtotal aumenta correctamente.
     */
    public function testAddItemIncreasesSubtotal()
    {
        // Crea un artículo: "Manzana", precio 1.50, cantidad 2.
        $item = new Item('Manzana', 1.50, 2);
        $this->cart->addItem($item);

        // Subtotal esperado: 1.50 * 2 = 3.00.
        $this->assertEquals(3.00, $this->cart->getSubtotal());

        // Ejemplo de prueba que falla (el subtotal es 3.00, no 4.00).
        // $this->assertEquals(4.00, $this->cart->getSubtotal());
    }

    /**
     * Prueba que al agregar el mismo artículo, se actualiza la cantidad en lugar de duplicarlo.
     */
    public function testAddingSameItemUpdatesQuantity()
    {
        // Crea dos artículos con el mismo nombre "Platano".
        $item1 = new Item('Platano', 0.50, 3);
        $item2 = new Item('Platano', 0.50, 2);

        $this->cart->addItem($item1);
        $this->cart->addItem($item2);

        // Cantidad esperada para "Platano": 3 + 2 = 5.
        $this->assertEquals(5, $this->cart->getItems()[0]->quantity);
        // Subtotal esperado: 0.50 * 5 = 2.50.
        $this->assertEquals(2.50, $this->cart->getSubtotal());

        // Ejemplo de prueba opuesta que falla (la cantidad esperada no es 6. es 5).
        // $this->assertEquals(6, $this->cart->getItems()[0]->quantity);
    }

    /**
     * Prueba que eliminar un artículo del carrito disminuye el subtotal.
     */
    public function testRemoveItemDecreasesSubtotal()
    {
        // Agrega dos artículos distintos.
        $item1 = new Item('Naranja', 1.00, 4);   // Total: 4.00
        $item2 = new Item('Uvas', 2.00, 1);    // Total: 2.00
        $this->cart->addItem($item1);
        $this->cart->addItem($item2);

        // Subtotal esperado: 4.00 + 2.00 = 6.00.
        $this->assertEquals(6.00, $this->cart->getSubtotal());

        // Elimina "Orange" del carrito.
        $this->cart->removeItem('Naranja');
        // Subtotal esperado después de la eliminación: 2.00.
        $this->assertEquals(2.00, $this->cart->getSubtotal());

        // Ejemplo de prueba opuesta (el subtotal ya no es 6.00).
        // $this->assertEquals(6.00, $this->cart->getSubtotal());
    }

    /**
     * Prueba que se aplica correctamente un descuento porcentual.
     */
    public function testApplyPercentageDiscount()
    {
        // Agrega dos artículos para obtener un subtotal de $30.
        $this->cart->addItem(new Item('Item1', 10));
        $this->cart->addItem(new Item('Item2', 20));

        // Crea una estrategia de descuento del 10%.
        $discount = new PercentageDiscount(10);

        // Total esperado: $30 - ($30 * 0.10) = $27.00.
        $this->assertEquals(27.00, $this->cart->applyDiscount($discount));

        // Ejemplo de prueba que falla ()el total no es 28.00).
        // $this->assertEquals(28.00, $this->cart->applyDiscount($discount));
    }

    /**
     * Prueba que se aplica correctamente un descuento fijo.
     */
    public function testApplyFixedDiscount()
    {
        // Crea un carrito con un subtotal de $50.
        $this->cart->addItem(new Item('Item1', 20));
        $this->cart->addItem(new Item('Item2', 30));

        // Crea un descuento fijo de $15.
        $discount = new FixedDiscount(15);

        // Total esperado: $50 - $15 = $35.
        $this->assertEquals(35.00, $this->cart->applyDiscount($discount));

        // Ejemplo de prueba que falla (el total no es 30.00).
        // $this->assertEquals(30.00, $this->cart->applyDiscount($discount));
    }

    /**
     * Prueba que un descuento fijo no reduzca el total a un valor negativo.
     */
    public function testFixedDiscountCannotReduceTotalBelowZero()
    {
        // Crea un carrito con un subtotal de $10.
        $this->cart->addItem(new Item('ItemBarato', 10));

        // Aplica un descuento fijo mayor que el subtotal.
        $discount = new FixedDiscount(20);

        // Total esperado: debe ser 0 (no negativo).
        $this->assertEquals(0, $this->cart->applyDiscount($discount));

        // Ejemplo de prueba que falla (el total no puede ser negativo).
        // $this->assertEquals(-10, $this->cart->applyDiscount($discount));
    }

    /**
     * Prueba que asegura que la lista de artículos no esté vacía después de agregar un artículo.
     */
    public function testItemsAreNotEmptyAfterAddingItem()
    {
        $item = new Item('Durazno', 1.00);
        $this->cart->addItem($item);

        // Asegura que la lista de artículos no esté vacía.
        $this->assertNotEmpty($this->cart->getItems());

        // Ejemplo de prueba que falla (la lista de artículos no está vacía).
        // $this->assertEmpty($this->cart->getItems());
    }

    /**
     * Prueba que asegura que el subtotal no sea nulo.
     */
    public function testSubtotalIsNotNull()
    {
        $item = new Item('Pina', 3.00);
        $this->cart->addItem($item);

        // Asegura que el subtotal no sea nulo después de agregar un artículo.
        $this->assertNotNull($this->cart->getSubtotal());

        // Ejemplo de prueba que falla (el subtotal no es nulo).
        // $this->assertNull($this->cart->getSubtotal());
    }

    /**
     * Prueba que asegura que el subtotal sea mayor que cero después de agregar un artículo.
     */
    public function testSubtotalIsGreaterThanZeroAfterAddingItem()
    {
        $item = new Item('Mango', 2.00);
        $this->cart->addItem($item);

        // Asegura que el subtotal sea mayor que cero.
        $this->assertGreaterThan(0, $this->cart->getSubtotal());

        // Ejemplo de prueba que falla (el subtotal es mayor que cero).
        // $this->assertLessThanOrEqual(0, $this->cart->getSubtotal());
    }

    /**
     * Prueba que asegura que se arroje una excepción al intentar eliminar un artículo que no existe.
     */
    public function testRemoveNonExistentItemDoesNotChangeSubtotal()
    {
        $item = new Item('Cerezas', 1.50);
        $this->cart->addItem($item);

        // Guarda el subtotal antes de intentar eliminar un artículo inexistente.
        $subtotalBefore = $this->cart->getSubtotal();
        $this->cart->removeItem('NonExistentItem');

        // Asegura que el subtotal no cambie después de intentar eliminar un artículo que no existe.
        $this->assertEquals($subtotalBefore, $this->cart->getSubtotal());

        // Ejemplo de prueba que falla (el subtotal no cambia).
        // $this->assertNotEquals($subtotalBefore, $this->cart->getSubtotal());
    }
}