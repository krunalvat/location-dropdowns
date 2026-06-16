# Laravel Location Dropdowns 🌍

A beautiful, lightweight, and automated Laravel package to sync and manage global Countries, States, and Cities in your database. 

Perfect for marketing teams, e-commerce checkouts, and SaaS platforms that need reliable location dropdowns with zero manual data entry!

## Features ✨
- **Automated Sync**: A powerful Artisan command that instantly downloads and updates thousands of locations from a reliable open-source community database.
- **Dynamic Country Flags**: Automatically generates and attaches `flagcdn` URLs to your API responses, allowing you to display beautiful flags in your frontend UI without bloating your database!
- **Proxy Flag Images**: Built-in proxy routes to serve flags directly from your own domain, perfectly hiding the external CDN from your frontend.
- **Fully Normalized Tables**: Clean and highly-optimized database architecture (Countries -> States -> Cities).
- **API Ready**: Comes with fast, built-in JSON API endpoints out-of-the-box.
- **Zero Configuration**: The data source is securely obfuscated within the codebase, requiring no messy `.env` setups to get started.

## Installation 🚀

You can install the package via composer:

```bash
composer require krunalvat/location-dropdowns
```

## Getting Started

1. **Publish the Migrations & Config (Optional)**
```bash
php artisan vendor:publish --tag="location-migrations"
php artisan vendor:publish --tag="location-config"
```

2. **Run the Migrations**
Create the necessary tables in your database:
```bash
php artisan migrate
```

3. **Sync the Data!**
Run the automated sync command to populate your database with the latest global location data:
```bash
php artisan locations:sync
```

## Usage 💻

Once synced, your application will immediately have access to three new API routes:

- `GET /api/locations/countries` (Returns all countries with dynamic flag URLs attached!)
- `GET /api/locations/states/{country_id}`
- `GET /api/locations/cities/{state_id}`

### Example Frontend Response
```json
{
  "id": 101,
  "name": "India",
  "iso2": "IN",
  "phonecode": "91",
  "flag_url": "http://yourdomain.com/api/locations/flags/in"
}
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
