# Farlex Pharmaceuticals — pharmacy storefront demo

A Laravel 12 / PHP 8.2 storefront prototype for an India-focused pharmacy catalogue. The UI uses the requested navy and teal palette and includes a responsive home page, searchable/filterable product listing, product details, cart, coupon, session wishlist, checkout, demo order tracking, OTP/account shells, articles, and policy drafts.

Headings use locally hosted Plus Jakarta Sans; body text uses Inter. Their open-font licenses are included beside the files in `public/fonts`.

All 17 seeded products are clearly marked demo placeholders. Product labels, descriptions, prices, manufacturers, regulatory details, reviews, and offers must be replaced with approved information before launch.

## Run on Windows with XAMPP

The local project is already installed and migrated. Double-click `start-store.bat` from this folder, keep its window open, and visit `http://127.0.0.1:8000`.

Alternatively, open Command Prompt in the project folder and run:

```cmd
"C:\xampp\php\php.exe" artisan serve
```

The default local database is SQLite at `database/database.sqlite`. To refresh the demo catalogue:

```cmd
"C:\xampp\php\php.exe" artisan migrate --seed
```

## Update the catalogue

Products use the `Product` database model and pharmacy fields for category, brand, manufacturer, pack size, dosage form, MRP, selling price, Rx status, stock, ratings, and product details. The demo records are in `database/seeders/DatabaseSeeder.php`; replace them with reviewed product data or build an authenticated admin workflow. An image can be supplied through the product's `image_url`; otherwise the storefront renders a local CSS packaging illustration.

The header/footer logo and footer social links use the assets and public profile URLs linked from [Farlex's official website](https://www.farlex.in/). Update them in `public/images` and `config/store.php` if the official brand assets or profiles change.

Set `STORE_NAME`, `STORE_TAGLINE`, contact details, drug-licence details, and pharmacist registration in `.env` only after they have been verified. `.env`, vendor packages, and the local database are excluded from Git.

## Admin order desk

Run migrations, then create an administrator interactively—there is no default admin account or password, and the admin login does not use an email address:

```cmd
"C:\xampp\php\php.exe" artisan migrate
"C:\xampp\php\php.exe" artisan admin:create
```

The command prompts for a username and a password of at least 12 characters (then asks you to confirm it). Sign in at `http://127.0.0.1:8000/admin/login`; the protected dashboard at `/admin/dashboard` lets authorized admins search orders by order number, customer, phone, email, or product, filter by status/date, and view customer and product details. Payment totals are demo order values; the dashboard does not imply payment or fulfilment has occurred.

## Important launch requirements

This is a demo storefront, not a production-ready pharmacy operation. It intentionally does not claim a licence or pharmacist approval and does not process payments or send OTPs. Before taking real orders, complete legal and pharmacy review, configure verified business/licence details, approved product copy and images, SMS authentication, payment and fulfilment providers, delivery serviceability, prescription access/retention controls, account security, and final customer policies. Rx-labelled demo products require a prescription upload; uploads are stored on Laravel's private local disk, but production access, encryption, retention, and audit controls still need implementation.

## Tests

```cmd
"C:\xampp\php\php.exe" artisan test
```
