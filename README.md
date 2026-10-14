# Almas Clothing Brand

AlmasClothing-brand-final is an online clothing store with product browsing and order management.

![PHP](https://img.shields.io/badge/PHP-2563EB?style=flat-square) ![MySQL](https://img.shields.io/badge/MySQL-0F172A?style=flat-square)

## Overview

The data model covers `admins`, `categories`, `orders`, `order_items`, `products`, `reviews`, `users`.

## Application areas

| Area | Pages in the repository |
| --- | --- |
| Admin | [`admin/add_product.php`](admin/add_product.php), [`admin/admin_category.php`](admin/admin_category.php), [`admin/admin_home.php`](admin/admin_home.php), [`admin/admin_login.php`](admin/admin_login.php), [`admin/admin_navbar.php`](admin/admin_navbar.php) |
| Root Pages | [`login.php`](login.php), [`register.php`](register.php) |
| User | [`user/add_to_cart.php`](user/add_to_cart.php), [`user/checkout.php`](user/checkout.php), [`user/my_orders.php`](user/my_orders.php), [`user/order_confirmation.php`](user/order_confirmation.php), [`user/place_order.php`](user/place_order.php) |

## Getting started

### 1. Get the project

```bash
git clone https://github.com/nasirabbas827/AlmasClothing-brand-final.git
cd AlmasClothing-brand-final
```

### 2. Configure PHP and the database

1. Use a local PHP server with MySQL or MariaDB, such as XAMPP.
2. Import [`Database/almas_db.sql`](Database/almas_db.sql) into your local database server.
3. Set your local database connection details in [`admin/config.php`](admin/config.php), [`config.php`](config.php), [`user/config.php`](user/config.php).
4. Keep the existing asset and upload directory paths intact.

### 3. Start the application

With the database running, start a local development server from the repository root:

```bash
php -S 127.0.0.1:8000
```

Open http://127.0.0.1:8000/index.php. If using Apache/XAMPP instead, place the project in its document root and open the matching localhost folder.

## Repository guide

| Path | Purpose |
| --- | --- |
| [`Database`](Database) | Database scripts |
| [`config.php`](config.php) | Application/database configuration |
| [`css`](css) | Stylesheets |
| [`images`](images) | Image assets |
| [`index.php`](index.php) | Web entry page |

## Development notes

These setup instructions are based on the repository source. The application has not been executed as part of this documentation update; verify dependencies and configuration in your local environment.
