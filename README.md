### Setup

- Run `./vendor/bin/sail up` to setting up Docker containers;

- Run `./vendor/bin/sail php artisan migrate`;

### To test

- Run `./vendor/bin/sail php artisan migrate:fresh`;

- Run `./vendor/bin/sail php artisan test`;

### Try yourself

- Run the curl request below:

```bash
curl -H "Content-type: application/json" -H "Accept: application/json" -d '[{"ArticleCode":"T12","ArticleName":"Tractor 2011 - XYZ","UnitPrice":200,"Quantity":3},{"ArticleCode":"T12","ArticleName":"Tractor 2011 - XYZ","UnitPrice":200,"Quantity":2}]' -X POST http://localhost/api/orders
```

### Frontend Repo

https://github.com/wederfabricio/sell2brazil_front

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

------------

UPDATE

-> Você está trabalhando para uma empresa do setor de ERP, um cliente solicita um novo endpoint para criar um novo pedido, calcular o preço do pedido e atualizar outros servidores com o pedido. Todos dados transmitidos estarão em JSON.
Para verificar o funcionamento do endpoint, o cliente quer uma interface no navegador que o permita criar pedidos com dados fictícios e ver o que o servidor retorna. A interface deve ser desenvolvida usando Vue.js.

O endpoint CREATE_ORDER recebe uma lista de objetos no seguinte formato:
{
	ArticleCode: string,
	ArticleName: string,
	UnitPrice:   float,
	Quantity:    int
}

O endpoint CREATE_ORDER retorna um objeto no seguinte formato:
{
	OrderId:                    int,    # id gerado automaticamente
	OrderCode:                  string, # código no formato YYYY-MM-OrderId
	OrderDate:                  string, # data do pedido no formato YYYY-MM-DD
	TotalAmountWihtoutDiscount: float,  # preço total sem desconto
	TotalAmountWithDiscount:    float   # preço total com desconto
}

O endpoint precisa:
  - verificar produtos com o mesmo ArticleCode e agregá-los;
  - criar um novo objeto de pedido com as propriedades descritas acima;
  - seguir a seguinte regra de desconto:
    Se o cliente encomendar pelo menos 5 e no máximo 9 unidades de um determinado produto e o preço total da encomenda for superior a 500, ele recebe um desconto de 15%;
  - armazenar o pedido no banco de dados.

Para atualizar os outros servidores, é necessário enviar no body de uma request POST, para cada endpoint listado a seguir, os dados no formato correspondente:
- https://localhost:9001/order
  {
    OrderId:                    int,    # id gerado automaticamente
    OrderCode:                  string, # código no formato YYYY-MM-OrderId
    OrderDate:                  string, # data do pedido no formato YYYY-MM-DD
    TotalAmountWihtoutDiscount: float,  # preço total sem desconto
    TotalAmountWithDiscount:    float   # preço total com desconto
  }
- https://localhost:9002/v1/order
  {
    id:       int,    # id gerado automaticamente
    code:     string, # código no formato YYYY-MM-OrderId
    date:     string, # data do pedido no formato YYYY-MM-DD
    total:    float,  # preço total sem desconto
    discount: float   # desconto
  }
- https://localhost:9003/web_api/order
  {
    id:                      int,    # id gerado automaticamente
    code:                    string, # código no formato YYYY-MM-OrderId
    date:                    string, # data do pedido no formato YYYY-MM-DD
    totalAmount:             float,  # preço total sem desconto
    totalAmountWithDiscount: float   # preço total com desconto
  }
