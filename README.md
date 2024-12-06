# acme-widget

## Requirements

Create a cart feature for three kind of products: Red Widget, Green Widget and Blue Widget with CODE as RO1, G01, B01 and PRICE as $32.95, $24.95 and $7.95 respectively.

To incentivise customers to spend more, delivery costs are reduced based on the amount spent. Orders under $50 cost $4.95. For orders under $90, delivery costs $2.95. Orders of $90 or more have free delivery.

Implement special offers as “buy one red widget, get the second half price”.


The job is to implement the basket which needs to have the following interface
• It is initialised with the product catalogue, delivery charge rules, and offers
• It has an add method that takes the product code as a parameter.
• It has a total method that returns the total cost of the basket, taking into account the delivery and offer rules.


## Usage

Add autoloader:
require_once __DIR__ . '/vendor/autoload.php';


Use the Basket class:

use App\Basket;

$catalogue = [
    'R01' => ['name' => 'Red Widget', 'price' => 32.95, 'image' => 'src/images/red.png'],
    'G01' => ['name' => 'Green Widget', 'price' => 24.95, 'image' => 'src/images/green.png'],
    'B01' => ['name' => 'Blue Widget', 'price' => 7.95, 'image' => 'src/images/blue.png'],
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


Add product to basket:

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product'])) {
        foreach ($_POST['product'] as $productCode) {
            $basket->add($productCode);
        }
    }
}


Total basket amount:
$total = $basket->total();

## Examples

You can check the example file at following location: "examples/acme.php"


## License

MIT
