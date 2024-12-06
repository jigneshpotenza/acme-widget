<?php
/**
 * Example File 
 *
 * Example file can help to how to add item to basket and get the total etc.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Basket;

$catalogue = [
    'R01' => ['name' => 'Red Widget', 'price' => 32.95, 'image' => 'images/red.png'],
    'G01' => ['name' => 'Green Widget', 'price' => 24.95, 'image' => 'images/green.png'],
    'B01' => ['name' => 'Blue Widget', 'price' => 7.95, 'image' => 'images/blue.png'],
];

$offers = [
    'R01' => true,
];

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_basket'])) {
    $_SESSION['basket'] = new Basket($catalogue, $offers);
}

if (!isset($_SESSION['basket']) || !$_SESSION['basket'] instanceof Basket) {
    $_SESSION['basket'] = new Basket($catalogue, $offers);
}

$basket = $_SESSION['basket'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product'])) {
        foreach ($_POST['product'] as $productCode) {
            $basket->add($productCode);
        }
    }
}

$total = $basket->total();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <style>
        .basket-container {
            margin-top: 20px;
        }

        .basket-container ul {
            list-style-type: none;
            padding: 0;
        }

        .basket-container li {
            margin: 5px 0;
        }

        .product-button {
            margin: 10px;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
        }

        .product-button:hover {
            background-color: #45a049;
        }

        .reset-button {
            margin-top: 20px;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #FF6347;
            color: white;
            border: none;
            font-size: 16px;
        }

        .reset-button:hover {
            background-color: #f44336;
        }

        .checkout-button {
            margin-top: 20px;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #4765ff;
            color: white;
            border: none;
            font-size: 16px;
        }

        .checkout-button:hover {
            background-color: #0c2dd3;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .product-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product {
            text-align: center;
            border: 1px solid #ddd;
            padding: 20px;
            width: 180px;
            box-sizing: border-box;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product h3 {
            margin: 10px 0;
            font-size: 18px;
        }

        .product p {
            margin: 10px 0;
            font-size: 16px;
        }

        .product-button {
            padding: 8px 8px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }

        .product-button:hover {
            background-color: #45a049;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .basket-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .basket-container th,
        .basket-container td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .basket-container th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .basket-container td {
            background-color: #fafafa;
        }

        .total p {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Product Catalogue</h1>

    <form action="" method="POST">
        <div class="product-container">
            <?php foreach ($catalogue as $productCode => $product) { ?>
                <div class="product">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="100" height="100">
                    <h3><?php echo $product['name']; ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    <button type="submit" name="product[]" value="<?php echo $productCode; ?>" class="product-button">Add to Basket</button>
                </div>
            <?php } ?>
        </div>
    </form>

    <form action="" method="POST">
        <button type="submit" name="reset_basket" class="reset-button">Reset Basket</button>
    </form>

    <div class="basket-container">
        <h2>Your Basket</h2>
        <?php if (!empty($basket->items)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $productCounts = array_count_values($basket->items);
                    foreach ($productCounts as $code => $count) {
                        $product = $catalogue[$code];
                        $price = $product['price'];
                        $totalPrice = 0;

                        if (isset($offers[$code]) && $code == 'R01' && $count >= 2) {
                            $fullPriceCount = floor($count / 2);
                            $discountedCount = $count - $fullPriceCount;

                            $productPrice = ($fullPriceCount * $price) + ($discountedCount * $price * 0.5);
                            $totalPrice = $productPrice;
                        } else {
                            $productPrice = $count * $price;
                            $totalPrice = $productPrice;
                        }

                        echo "<tr>
                                <td>{$product['name']}</td>
                                <td>{$count}</td>
                                <td>$" . number_format($price, 2) . "</td>
                                <td>$" . number_format($totalPrice, 2) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <table class="total-table">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td>$<?php echo number_format($total - $basket->getDeliveryCost($total), 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Delivery Charge:</strong></td>
                    <td>$<?php echo number_format($basket->getDeliveryCost($total), 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td>$<?php echo number_format($total, 2); ?></td>
                </tr>
            </table>

            <form action="index.php" method="POST">
                <button type="submit" name="checkout" class="checkout-button">Checkout</button>
            </form>

        <?php } else { ?>
            <p>Your basket is empty. Please add products to your basket.</p>
            <table class="total-table">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td><strong>Delivery Charge:</strong></td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td>$0.00</td>
                </tr>
            </table>
        <?php } ?>

    </div>
</body>

</html>