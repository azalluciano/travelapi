
# TRIP-API

A Laravel-based REST API for managing travel destinations.

## Features

- Create, read, update, and delete destinations
- Filter destinations by name
- Basic authentication for administrators
- Protected backoffice functionality
- RESTful API design
- Export destinations to CSV via Symfony command

## Requirements

- PHP 8.2+
- Laravel 12.3
- MySQL 8.0
- Composer

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/azalluciano/travelapi.git
   cd travelapi
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Configure your database in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tripapi
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Generate JWT secret:
   ```bash
   php artisan jwt:secret
   ```

7. Run migrations and seed database:
   ```bash
   php artisan migrate --seed
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## API Documentation

### Public Endpoints

#### List Destinations
- **URL**: `/api/destinations`
- **Method**: GET
- **Parameters**:
  - `name` (optional) - Filter destinations by name
- **Example**: `GET /api/destinations?name=Paris`

#### Get Destination Details
- **URL**: `/api/destinations/{id}`
- **Method**: GET
- **Example**: `GET /api/destinations/1`

### Authentication Endpoints

#### Register Admin
- **URL**: `/api/auth/register`
- **Method**: POST
- **Body**:
  ```json
  {
    "name": "Admin Name",
    "email": "admin@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```

#### Login
- **URL**: `/api/auth/login`
- **Method**: POST
- **Body**:
  ```json
  {
    "email": "admin@example.com",
    "password": "password123"
  }
  ```
- **Response**: JWT token and user details

#### Get Current User
- **URL**: `/api/auth/me`
- **Method**: GET
- **Headers**: Authorization: Bearer {token}

#### Logout
- **URL**: `/api/auth/logout`
- **Method**: POST
- **Headers**: Authorization: Bearer {token}

#### Refresh Token
- **URL**: `/api/auth/refresh`
- **Method**: POST
- **Headers**: Authorization: Bearer {token}

### Protected Admin Endpoints

#### Create Destination
- **URL**: `/api/destinations`
- **Method**: POST
- **Headers**: Authorization: Bearer {token}
- **Body**:
  ```json
  {
    "name": "Destination Name",
    "description": "Destination Description",
    "price": 199.99,
    "duration": 7,
    "image": "destination.jpg"
  }
  ```

#### Update Destination
- **URL**: `/api/destinations/{id}`
- **Method**: PUT
- **Headers**: Authorization: Bearer {token}
- **Body**: Any of the fields from the create endpoint

#### Delete Destination
- **URL**: `/api/destinations/{id}`
- **Method**: DELETE
- **Headers**: Authorization: Bearer {token}

#### Admin Dashboard - Destinations
- **URL**: `/api/admin/destinations`
- **Method**: GET
- **Headers**: Authorization: Bearer {token}

#### Admin Dashboard - Statistics
- **URL**: `/api/admin/statistics`
- **Method**: GET
- **Headers**: Authorization: Bearer {token}

## Console Commands

### Export Destinations to CSV
```bash
php artisan destinations:export [filename]
```
This command calls the API to get all destinations and exports them to a CSV file.

## Web Routes

### Authentication Routes
- **Login**: `GET /admin/login` (AdminAuthController@showLoginForm)
- **Login POST**: `POST /admin/login` (AdminAuthController@login)
- **Register**: `GET /admin/register` (AdminAuthController@showRegistrationForm)
- **Register POST**: `POST /admin/register` (AdminAuthController@register)
- **Logout**: `POST /admin/logout` (AdminAuthController@logout)

### Admin Routes
- **Destinations CRUD**: `Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () { Route::resource('destinations', DestinationWebController::class); });`

### Fallback Route
```php
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
```

## Testing

Run the test suite:
```bash
php artisan test
```

## CI/CD

This project includes a GitHub Actions workflow for continuous integration, which:
- Sets up a testing environment
- Installs dependencies
- Runs migrations
- Executes tests

The workflow configuration is in `.github/workflows/laravel-test.yml`.
