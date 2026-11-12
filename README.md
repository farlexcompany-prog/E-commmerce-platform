# Everyday Goods

A Laravel 12 e-commerce starter built with PHP 8.2+. It includes a seeded product catalog, a session-based shopping cart, and checkout that saves orders to the database.

## Requirements

- PHP 8.2 or newer with `pdo_sqlite` enabled (XAMPP's PHP works)
- Composer

## Run with XAMPP on Windows

Dependencies and a local `.env` are already set up. Double-click `start-store.bat` in this folder. Leave its window open, then open `http://127.0.0.1:8000` in your browser.

Alternatively, open Command Prompt in this folder and run:

```powershell
"C:\xampp\php\php.exe" artisan serve
```

Do not double-click `php.exe` or type `artisan serve` at the `php >` prompt. The default configuration uses the included SQLite database at `database/database.sqlite`.

For a fresh checkout, install Composer for Windows and run `composer install`; if `.env` does not exist, copy `.env.example` to `.env` and run `php artisan key:generate` before migrating.

## Store features

- Product listing and starter products
- Session cart with quantity updates and item removal
- Checkout form with server-side validation
- Orders and order-item snapshots saved transactionally
- Feature tests using an in-memory SQLite database

This is a development starter, not a production-ready shop. It does not process payments, calculate tax or shipping, manage inventory, or include customer accounts or an admin dashboard. Integrate and test a payment provider before accepting real orders.

## Tests

```powershell
php artisan test
```
