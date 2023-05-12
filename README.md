## Laravel Image Manipulation REST API

#### Test locally
Download [postman_collection.json](postman_collection.json) file, import it in your postman and test locally.

## Prerequisites

#### PHP Extensions
`php-mbstring php-dom php-intl php-curl php-mysql php-gd`

## Basic installation steps 
Before you start the installation process you need to have **installed composer**

1. Clone the project
2. Navigate to the project root directory using command line
3. Run `composer install`
4. Copy `.env.example` into `.env` file
5. Adjust `DB_*` parameters.<br> 
   If you want to use Mysql, make sure you have mysql server up and running. <br>
   If you want to use sqlite: 
   1. you can just delete all `DB_*` parameters except `DB_CONNECTION` and set its value to `sqlite`
   2. Then create file `database/database.sqlite`
6. Run `php artisan key:generate --ansi`
7. Run `php artisan migrate`

### Installing locally for development
Run `php artisan serve` which will start the server at http://localhost:8000 <br>

## Usage

This API provides the following endpoints:

- `GET /api/users`: Returns a list of all users.
- `GET /api/users/{id}`: Returns the user with the specified ID.
- `POST /api/users`: Creates a new user.
- `PUT /api/users/{id}`: Updates the user with the specified ID.
- `DELETE /api/users/{id}`: Deletes the user with the specified ID.

You can use any RESTful client to interact with these endpoints, or you can use a tool like Postman to test the API.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).