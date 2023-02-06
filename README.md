# esb-invoice

## Setup
1. Open Cmd at this file's location.
2. Create a new database named `esb_invoice` in MySQL.
3. Run `composer install` to install all dependencies.
4. Run `php yii migrate` to execute the migration.
5. Run `php yii serve` to start the application.
6. Open `localhost:8080` at your browser.

The API example of this project can be found by importing `ESB Invoice.postman_collection.json` to Postman.

## API Endpoints
### GET api/invoice
Retrieves all invoices
Response example:
```
GET http://localhost:8080/api/invoice
[
    {
        "id": 1,
        "invoice_id": "0001",
        "issue_date": "06 February 2023",
        "due_date": "13 February 2023",
        "subject": "Invoice 1",
        "is_paid": false,
        "party_from": "Company A",
        "party_to": "Company B"
    },
    {
        "id": 2,
        "invoice_id": "0002",
        "issue_date": "06 February 2023",
        "due_date": "13 February 2023",
        "subject": "Invoice 2",
        "is_paid": false,
        "party_from": "Company B",
        "party_to": "Company C"
    }
]
```

### GET api/invoice/{invoiceID}
Retrieves invoice with ID `{invoiceID}`
Response example:
```
GET http://localhost:8080/api/invoice/1
{
    "id": 1,
    "invoice_id": "0001",
    "issue_date": "06 February 2023",
    "due_date": "13 February 2023",
    "subject": "Invoice 1",
    "is_paid": false,
    "party_from": "Company A",
    "party_to": "Company B",
    "items": [
        {
            "id": 1,
            "item_type": "Service",
            "description": "Item 1",
            "quantity": 5,
            "unit_price": 100,
            "amount": 500,
            "invoice_id": 1
        }
    ],
    "subtotal": 500,
    "tax": 50,
    "payments": -550
}
```