# Project API Test #

### What is this repository for?

* Project API Test

### How do I get set up?

* Clone this repo
* Run `composer install`
* Copy `.env-example` to `.env` for environment configuration (please check below on what to change in .env for Mailer)
* Run `php artisan key:generate`
* Migrate database `php artisan migrate`
* Seed database `php artisan db:seed` to create admin user
* Run `php artisan jwt:secret` to generate JWT secret key
* Run php server `php artisan serve`

### Setup Laravel Mailer

Please update your .env for Laravel Mailer.
* `MAIL_HOST` - `smtp.googlemail.com`
* `MAIL_PORT` - `465`
* `MAIL_USERNAME` - `{INSERT_YOUR_GMAIL_HERE}`
* `MAIL_PASSWORD` - `{INSERT_YOUR_GMAIL_PASSWORD_HERE}`
* `MAIL_ENCRYPTION` - `ssl`

### API

* `/api/auth/login` - Login for all users (either admin or user role)
* `/api/invite` - Invite users by email (only admin can invite users). Users will receive email.
* `/api/auth/verifyToken` - Verify token for registration. If successful, this will return user details with id.
* `/api/auth/register/{id}` - Register user. This will update existing user's user_name and password. If successful, users will receive pin on their email. This will also return token that will be used to validate users.
* `/api/user/validate` - Validate pin from users.
* `/api/user/update` - Update user (name, user_name, and avatar).

### Libraries

* `tymon/jwt-auth` - Laravel JWT auth

### Who do I talk to? ###

* Mervin Villaceran
