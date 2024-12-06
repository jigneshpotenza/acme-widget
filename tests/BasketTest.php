<?php
/**
 * BasketTest class 
 *
 * @package Acme Widget Co
 */
use PHPUnit\Framework\TestCase;
use App\Basket;

/**
 * BasketTest class 
 */
class BasketTest extends TestCase
{
    /**
	 * Contains an object of basket class.
	 *
	 * @var object
	 */
    private $basket;

    /**
	 * Contains an array of basket items.
	 *
	 * @var array
	 */
    private $catalogue;

    protected function setUp(): void
    {
        // Example catalog with product codes and their prices
        $this->catalogue = [
            'P001' => 10.99,
            'P002' => 5.49,
            'P003' => 2.99,
        ];
        
        $this->basket = new Basket($this->catalogue);
    }

    /**
     * This method is call to test add item to basket is valid
     */
    public function testAddProductToBasket()
    {
        $this->basket->add('P001');
        $this->assertCount(1, $this->basket->items, "Basket should have one item.");
        $this->assertEquals('P001', $this->basket->items[0], "The added product code should be 'P001'.");
    }

    /**
     * This method is call to test add item to basket is valid
     */
    public function testAddInvalidProductThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Product code not found.");
        $this->basket->add('INVALID_CODE');
    }

    /**
     * This method is call to test add item to basket is empty
     */
    public function testEmptyBasketInitially()
    {
        $this->assertEmpty($this->basket->items, "Basket should be empty initially.");
    }
}
