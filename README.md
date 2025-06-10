# Simple Ecommerce API

> **⚠️ Please Note**  
> This is just a subset of the code used on [this website](https://supps.crafted-internet.com)

> **⚠️ Please Note**  
> Front end sample is available on [this repo](https://github.com/ahmdadl/simple-ecommerce-spa)

> **⚠️ Please Note**  
> Postman Collection is available on [this link](https://github.com/ahmdadl/simple-ecommerce-api/blob/main/Postman%20Collection/Simple%20Ecommerce%20API.postman_collection.json)

## About

Simple Ecommerce API is a simple ecommerce API built with Laravel. with all the features of an ecommerce API.

## Features

-   Modular Architecture
-   Actions Pattern
-   Services for Business Logic
-   Test Driven Development
-   Multi Language Support

## Installation

-   Clone the repository
-   Copy the .env.example file to .env
-   Generate an application key

```bash
php artisan key:generate
```

-   Install dependencies

```bash
composer install
```

-   Run migrations

```bash
php artisan migrate
```

-   Run seeders (should be run only on development environment)

```bash
php artisan db:seed
```

-   Run development server

```bash
php artisan serve
```

## License

Simple Ecommerce API is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
