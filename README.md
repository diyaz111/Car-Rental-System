Project Name

Introduction

This is a Laravel-based project designed with two roles: Admin and User. Below are the instructions to set up, configure, and run the project.

Requirements

PHP >= 8.0

Composer

MySQL or another supported database

Installation

Clone this repository:

git clone <repository-url>

Navigate to the project directory:

cd <project-folder>

Install dependencies:

composer install

Copy the .env file:

cp .env.example .env

Generate the application key:

php artisan key:generate

Run the migrations and seed the database:

php artisan migrate --seed

Generate the JWT secret:

php artisan jwt:secret

Optimize the autoloader:

composer dump-autoload

Roles and Credentials

The project supports two roles: Admin and User. Use the following credentials to log in as an admin:

Admin Credentials:

Email: superadmin@gmail.com

Password: 12341234

Running the Application

Start the queue worker and scheduler:

php artisan schedule:work

Configure your SMTP settings in the .env file to enable email functionality:

MAIL_MAILER=smtp
MAIL_HOST=<your-smtp-host>
MAIL_PORT=<your-smtp-port>
MAIL_USERNAME=<your-smtp-username>
MAIL_PASSWORD=<your-smtp-password>
MAIL_ENCRYPTION=<tls/ssl>
MAIL_FROM_ADDRESS=<your-email>
MAIL_FROM_NAME="<your-name>"

Caching

This project uses the database for caching. Ensure your database connection is properly configured in the .env file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<your-database>
DB_USERNAME=<your-username>
DB_PASSWORD=<your-password>

Running Tests

To run the unit tests, execute the following command:

php artisan test

License

This project is licensed under the MIT License.

Feel free to contribute and create pull requests to improve the project! For any issues or inquiries, please contact the repository maintainer.

