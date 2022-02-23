### Setup

- Run `./vendor/bin/sail up` to setting up Docker containers;

- Run `./vendor/bin/sail php artisan migrate`;

### To test

- Run `./vendor/bin/sail php artisan test`;

### Try yourself

- Run the curl request below:

```bash
curl -H "Content-type: application/json" -H "Accept: application/json" -d '[{"ArticleCode":"T12","ArticleName":"Tractor 2011 - XYZ","UnitPrice":200,"Quantity":3},{"ArticleCode":"T12","ArticleName":"Tractor 2011 - XYZ","UnitPrice":200,"Quantity":2}]' -X POST http://localhost/api/orders
```

### Requirements

You are working for a company in the ERP sector, one customer requests a new endpoint to create a new order and calculate the price for the order.
The calculation can handle discounts depending on the ordered amount of a specific product.


The endpoint CREATE_ORDER receives a list of order lines. 
{
	ArticleCode
	ArticleName
	UnitPrice
	Quantity
}

The endpoint CREATE_ORDER returns the following objects
{
	OrderId -> auto generated
	OrderCode -> YEAR-MONTH-ORDERID
	OrderDate -> order date
	TotalAmountWihtoutDiscount  -> the total amount withoutDiscount
	TotalAmountWithDiscount 	-> the total amount withoutDiscount
}

The endpoint needs to check for products with the same ArticleCode and aggregate them together.
The endpoint needs to create a new order object with the properties described above
The endpoint has the following discount rule:

- if the customer order between 5 and 9 units of a specific product AND the total of this product is above 500, he receives a discount of 15%.

The endpoint stores the order in the database

Write 2 unit tests to test some parts of the logic