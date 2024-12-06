<?php
/**
 * Acme Widget Co Basket
 *
 * The Acme Widget Co basket class stores basket data and active discount offers as well as handling customer sessions and some basket related urls.
 * The basket class also has a price calculation function.
 *
 */
namespace App;

use Exception;

/**
 * Basket class.
 */
class Basket
{
    /**
	 * Contains an array of basket items.
	 *
	 * @var array
	 */
    private $catalogue;

    /**
	 * Contains an array of offers basket items so we can make discount offers them if needed.
	 *
	 * @var array
	 */
    private $offers;

    /**
	 * Contains an array of added basket items.
	 *
	 * @var array
	 */
    public $items = [];

    /**
	 * Constructor for the basket class. Loads catalogue basket items and offers.
	 */
    public function __construct($catalogue, $offers = [])
    {
        $this->catalogue = $catalogue;
        $this->offers = $offers;
    }


    /**
	 * Add a item to the basket.
	 *
	 * @throws Exception throw an exception to prevent adding to basket.
	 * @param array $productCode item code we want to add into basket.
	 * @return array|string $this->items
	 */
    public function add($productCode)
    {
        if (isset($this->catalogue[$productCode])) {
            $this->items[] = $productCode;
        } else {
            throw new Exception("Product code not found.");
        }
    }

    /**
	 * Calculate totals for the items in the basket.
	 */
    public function total()
    {
        $subtotal = 0;
        $productCounts = array_count_values($this->items);

        $redWidgetCount = isset($productCounts['R01']) ? $productCounts['R01'] : 0;
        
        foreach ($productCounts as $code => $count) {
            $product = $this->catalogue[$code];
            $price = $product['price'];

            if (isset($this->offers[$code]) && $redWidgetCount >= 2) {
                $fullPriceCount = floor($count / 2);
                $discountedCount = $count - $fullPriceCount;

                $productPrice = ($fullPriceCount * $price) + ($discountedCount * $price * 0.5);
                $subtotal += $productPrice;
            } else {
                $productPrice = $count * $price;
                $subtotal += $productPrice;
            }
        }

        $deliveryCost = $this->getDeliveryCost($subtotal);

        $total = $subtotal + $deliveryCost;

        return $total;
    }

    /**
	 * Calculate Delivery Cost.
	 */
    public function getDeliveryCost($subtotal)
    {
        if ($subtotal >= 90) {
            return 0;
        } elseif ($subtotal >= 50) {
            return 2.95;
        } else {
            return 4.95;
        }
    }
}
