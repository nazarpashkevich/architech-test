# Test Task

### How to Run the Project

To run the project, simply execute the `index.php` file using the PHP CLI:

```bash
php index.php
```


### How it works?

- All data is stored in JSON files (this is just a placeholder for what would typically be extracted 
from a database). This data is then transferred into data classes because it's much easier and more reliable to work with data objects within the code.

- The core logic is located in the Basket.php file. This class is responsible for managing products in 
the basket.

- To calculate the total amount (including products and delivery), a separate factory is used. 
This approach helps to avoid overloading and complicating the Basket class.

- Delivery costs are calculated based on predefined rules. These rules are stored as key-value 
pairs, where each key represents a price threshold and the corresponding value is the delivery fee for that price range.

- Offers and discounts are applied within the basket using separate processors and a resolver. 
This design allows you to separate the logic for handling offers, making it easier to add new functionalities or promotions without modifying the core code.
