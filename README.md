# Hotel Booking API Example

A simple API that sends fulfillment mock request to Amazon’s Fulfillment Network (FBA) using the seller’s inventory, processes the order, and returns the tracking number.
## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Usage](#usage)
    - [Login](#1-login-generate-token)
    - [Get Rooms List](#2-get-rooms-list)
    - [Get Room Details](#3-get-room-details)
    - [Get Bookings List](#4-get-bookings-list)
    - [Get Booking Payments](#5-get-booking-payments)
    - [Get Customers List](#6-get-customers-list)
    - [Create New Room](#7-create-new-room)
    - [Create New Customer](#8-create-new-customer)
    - [Create New Booking](#9-create-new-booking)
    - [Create New Booking Payment](#10-create-new-booking-payment)
    - [Cancel Booking](#11-cancel-booking)
    - [Logout](#12-logout)
5. [Run Unit Tests](#run-unit-tests)

## Features

#### Token-Based Authentication:

The API utilizes bearer tokens for authentication, ensuring secure access to endpoints.

#### Authentication Endpoint (Login):

-   **POST /api/login** Generate API Token to access secured endpoints

#### Secured Endpoints:

-   **POST /api//shipping/<buyer_id>/<order_id>** Process order shipment through Amazon FBA for <buyer_id> and <order_id>
-   **POST /api/logout** Remove API Token for Authenticated User

## Requirements

-   Docker

    > If you are using Windows, please make sure to install Docker Desktop.
    > Next, you should ensure that Windows Subsystem for Linux 2 (WSL2) is installed and enabled.

-   [Postman](https://www.postman.com) or other HTTP client for testing the API.
-   [Composer](https://getcomposer.org/)
- If you’re using Windows or macOS, the easiest way to run your application is to install [Laravel Herd](https://herd.laravel.com).

## Installation

### 1. Clone the project

```bash
git clone https://github.com/tmjaga/fba-shipping-api.git
```

### 2. Navigate into the project folder using terminal

```bash
cd fba-shipping-api
```

### 3. Install the project dependencies

In the project folder run:

```bash
composer install
```

### 4. Create .env file 

In the project folder run:

```bash
cp .env.example .env
```

### 5. Generate application key

In the project folder run:

```bash
php artisan key:generate
```

### 6. Create DB and import data

In the project folder run:

```bash
php artisan migrate --seed
```

### 7. Start fba-shipping-api application 

In the project folder run:
- For Docker

```bash
docker-compose up -d
```
Api will be avaliable at http://localhost:8080/api

- For Laravel Herd: start Laravel Herd application

Api will be avaliable at http://fba-shipping-api.test/api


## Usage

### 1. Login (Generate Token)

-   URL: http://localhost/api/login
-   Method: POST
-   Request Body:

```json
{
    "email": "admin@mail.com",
    "password": "password"
}
```

-   Response

```json
{
    "message": "Logged In",
    "api-token": "19|wMbuZAerqMOI3gVTrJmnsD9xw0OwDQEgw89YMVBx75a1544a"
}
```

Copy api-token value and
add `Authorization: Bearer <api_token>` header to Secured Endpoints requests

> If you are using Posman HTTP client for testing, you can import FBA Shipping Collection from
> `FBA Shipping.postman_collection.json` file and configurate api_token Global Environment variable with copied api-token value 
> and base_url variable with `http://fba-shipping-api.test/api` or `http://localhost:8080/api`
> Don't forget to press Save button.

### 2. Get Rooms List

-   URL: http://localhost/api/rooms
-   Method: GET
-   Request Body Parameters (optional):

```
number - integer|must exist in rooms table
room_type_id - integer|integer|must exist in room_types table
status - free|busy
```

-   Request Body example:

```json
{
    "number": 101,
    "room_type_id": 1,
    "status": "free"
}
```

-   Response Example

```json
[
    {
        "id": 1,
        "number": 101,
        "room_type": "Deluxe Room",
        "price_per_night": 1855,
        "status": "free"
    }
]
```

### 3. Get Room Details

-   URL: http://localhost/api/rooms/<room_number>
-   Method: GET
-   Parameter:

```
<room_number> - required|integer
```

If Room can not be found, **404 Not Found** response status will be returned with body:

```json
{
    "message": "Room not found"
}
```

-   Response Example

```json
{
    "id": 1,
    "number": 101,
    "room_type": "Deluxe Room",
    "price_per_night": 1855,
    "created_by": 1,
    "status": "free",
    "booking": [
        {
            "id": 2,
            "check_in_date": "2024-05-31",
            "check_out_date": "2024-05-31",
            "total_price": 507
        }
    ]
}
```

### 4. Get Bookings List

-   URL: http://localhost/api/bookings
-   Method: GET
-   Request Body Parameters (optional):

```
customer - alphanumeric and spaces
number - integer|must exist in rooms table
```

-   Request Body example:

```json
{
    "customer": "Cusomer",
    "number": 101
}
```

-   Response Example

```json
[
    {
        "id": 2,
        "room": {
            "id": 1,
            "number": 101,
            "room_type_id": 3,
            "price_per_night": 1855,
            "created_by": 1,
            "created_at": "2024-05-04T06:52:38.000000Z",
            "updated_at": "2024-05-04T06:52:38.000000Z",
            "room_type_name": "Deluxe Room",
            "roomtype": {
                "id": 3,
                "type": "Deluxe Room"
            }
        },
        "customer": {
            "id": 2,
            "customer_name": "Customer 2",
            "email": "customer2@mail.com",
            "phone_number": "158689017",
            "created_by": 1,
            "created_at": "2024-05-04T06:52:38.000000Z",
            "updated_at": "2024-05-04T06:52:38.000000Z"
        },
        "check_in_date": "2024-05-31",
        "check_out_date": "2024-05-31",
        "total_price": 507
    }
]
```

### 5. Get Booking Payments

-   URL: http://localhost/api/bookings/<booking_id>/payments
-   Method: GET
-   Parameter:

```
<booking_id> - required|integer
```

If Booking can not be found, **404 Not Found** response status will be returned with body:

```json
{
    "message": "Booking not found"
}
```

-   Response Example

```json
[
    {
        "id": 1,
        "booking_id": 2,
        "amount": 2.2,
        "payment_date": "2024-05-29 13:10:00",
        "status": "pending"
    }
]
```

### 6. Get Customers List

-   URL: http://localhost/api/customers
-   Method: GET
-   Request Body Parameters (optional):

```
customer_name - alphanumeric and spaces
email - valid email address
phone_number - integer
```

-   Request Body example:

```json
{
    "customer_name": "Cusomer",
    "email": "customer2@mail.com",
    "phone_number": 158689017
}
```

-   Response Example

```json
[
    {
        "id": 2,
        "customer_name": "Customer 2",
        "email": "customer2@mail.com",
        "phone_number": "158689017",
        "created_by": 1,
        "bookings": [
            {
                "id": 2,
                "room": {
                    "id": 1,
                    "number": 101,
                    "room_type_id": 3,
                    "price_per_night": 1855,
                    "created_by": 1,
                    "created_at": "2024-05-04T06:52:38.000000Z",
                    "updated_at": "2024-05-04T06:52:38.000000Z",
                    "room_type_name": "Deluxe Room",
                    "roomtype": {
                        "id": 3,
                        "type": "Deluxe Room"
                    }
                },
                "check_in_date": "2024-05-31",
                "check_out_date": "2024-05-31",
                "total_price": 507
            }
        ]
    }
]
```

### 7. Create New Room

-   URL: http://localhost/api/rooms
-   Method: POST
-   Request Body Parameters:

```
number - required|integer|digits length 3|unique
room_type_id' - required|integer|must exist room_types table
price_per_night - required|integer| > 0
```

On Validation Error, **422 Unprocessable Content** response status will be returned.

-   Validation Error Response Example:

```json
{
    "message": "The number field must be 3 digits. (and 1 more error)",
    "errors": {
        "number": ["The number field must be 3 digits."],
        "room_type_id": ["The selected room type id is invalid."]
    }
}
```

-   Request Body example:

```json
{
    "number": 602,
    "room_type_id": 1,
    "price_per_night": 1000
}
```

-   Response Example

```json
{
    "id": 51,
    "number": "602",
    "room_type": "Accessible Room",
    "price_per_night": "1000",
    "status": "free"
}
```

### 8. Create New Customer

-   URL: http://localhost/api/customers
-   Method: POST
-   Request Body Parameters:

```
customer_name - required|alphanumeric and spaces
email - required|valid email address|unique
phone_number - required|integer
```

On Validation Error, **422 Unprocessable Content** response status will be returned.

-   Validation Error Response Example:

```json
{
    "message": "The email field must be a valid email address. (and 1 more error)",
    "errors": {
        "email": ["The email field must be a valid email address."],
        "phone_number": ["The phone number field must be an integer."]
    }
}
```

-   Request Body example:

```json
{
    "customer_name": "Test Customer",
    "email": "test_cust@mail.com",
    "phone_number": "111111111"
}
```

-   Response Example

```json
{
    "id": 3,
    "customer_name": "Test Customer",
    "email": "test_cust@mail.com",
    "phone_number": "111111111",
    "created_by": 2,
    "bookings": []
}
```

### 9. Create New Booking

-   URL: http://localhost/api/bookings
-   Method: POST
-   Request Body Parameters:

```
room_id - required|integer|must exist in rooms table
customer_id - required|integer|must exist in customers table
check_in_date - required|date|must be a date after or equal today
check_out_date - required|date|must be a date after or equal to check_in_date
```

On Validation Error, **422 Unprocessable Content** response status will be returned.

-   Validation Error Response Example:

```json
{
    "message": "The selected customer id is invalid. (and 2 more errors)",
    "errors": {
        "customer_id": ["The selected customer id is invalid."],
        "check_in_date": [
            "The check in date field must be a valid date.",
            "The check in date field must be a date after or equal to today."
        ]
    }
}
```

If room already has a reservation (Booking) for provided check_in_date - check_out_date period, Error, **430** response status will be returned with following body:

```json
{
    "message": "This Room is not available for this period"
}
```

> On Booking created event a new mail notification with New Booking Details will be sent in the background to all Users. To check emails, start processing jobs on the queue as a daemon before sending request:
>
> ```json
> ./vendor/bin/sail artisan queue:work
> ```

-   Request Body example:

```json
{
    "room_id": 1,
    "customer_id": 1,
    "check_in_date": "2024-05-10",
    "check_out_date": "2024-05-10"
}
```

-   Response Example

```json
{
    "id": 3,
    "room": {
        "id": 1,
        "number": 101,
        "room_type_id": 3,
        "price_per_night": 1855,
        "created_by": 1,
        "created_at": "2024-05-04T06:52:38.000000Z",
        "updated_at": "2024-05-04T06:52:38.000000Z",
        "room_type_name": "Deluxe Room",
        "roomtype": {
            "id": 3,
            "type": "Deluxe Room"
        }
    },
    "customer": {
        "id": 1,
        "customer_name": "Customer 1",
        "email": "customer1@mail.com",
        "phone_number": "162556776",
        "created_by": 1,
        "created_at": "2024-05-04T06:52:38.000000Z",
        "updated_at": "2024-05-04T06:52:38.000000Z"
    },
    "check_in_date": "2024-05-10",
    "check_out_date": "2024-05-10",
    "total_price": 1855
}
```

### 10. Create New Booking Payment

-   URL: http://localhost/api/bookings/<booking_id>/payments
-   Method: GET
-   Parameter:

```
<booking_id> - required|integer
```

If Booking can not be found, **404 Not Found** response status will be returned with body:

```json
{
    "message": "Booking not found"
}
```

-   Request Body Parameters:

```
amount - required|decimal with 2 decimal places
payment_date - required|valid date time in format Y-m-d H:i:s|after or equal today
status - required|pending|failed|processed
```

On Validation Error, **422 Unprocessable Content** response status will be returned.

-   Validation Error Response Example:

```json
{
    "message": "The amount field must have 2 decimal places. (and 1 more error)",
    "errors": {
        "amount": ["The amount field must have 2 decimal places."],
        "status": [
            "Incorrect Payment Status value. Only pending, failed, processed values allowed"
        ]
    }
}
```

-   Response Example

```json
{
    "id": 9,
    "booking_id": 2,
    "amount": "2.20",
    "payment_date": "2024-05-11 13:10:00",
    "status": "pending"
}
```

### 11. Cancel Booking

-   URL: http://localhost/api/bookings/<booking_id>
-   Method: DELETE
-   Parameter:

```
<booking_id> - required|integer
```

If Booking can not be found, **404 Not Found** response status will be returned with body:

```json
{
    "message": "Booking not found"
}
```

> On Booking deleted event a new mail notification with Canceled Booking Details will be sent in the background to all Users. To check emails, start processing jobs on the queue as a daemon before sending request:
>
> ```json
> ./vendor/bin/sail artisan queue:work
> ```

-   Response Example

```json
{
    "message": "Booking canceled"
}
```

### 12. Logout

-   URL: http://localhost/api/logout
-   Method: POST
-   Response

```json
{
    "message": "Logged Out"
}
```

## Run Unit Tests

In the project root folder run:

```bash
./vendor/bin/sail artisan test
```
