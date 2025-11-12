# Fba Shipping api 

A simple API that sends fulfillment mock request to Amazon’s Fulfillment Network (FBA) using the seller’s inventory, processes the order, and returns the tracking number.
## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Usage](#usage)
    - [Login](#1-login-generate-token)
    - [Fulfill Seller Order](#2-fulfill-seller-order)
    - [Logout](#3-logout)
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
    "email": "test@mail.com",
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

### 2. Fulfill Seller Order

-   URL: /shipping/<buyer_id>/<order_id>
-   Method: POST
-   Parameters:

```
<booking_id> - required|integer
<order_id> - required|integer
```
-   Response Example

```json
{
    "success": true,
    "message": "Order fulfilled successfully.",
    "data": {
        "tracking_number": "FBA-QG1HCNRLJP"
    }
}
```
### 3. Logout

-   URL: /logout
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
php artisan test
```
