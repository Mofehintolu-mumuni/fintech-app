# wafi-app
This application shows how a traditional banking application works. It was developed using laravel. There is an in-app sql lite database that holds data and api's that hold the core implementation of the application.

#Application setup

This application is running on laravel version ^8.54, PHP ^8.0 and Node v16.0.0 . Kindly ensure that your system meets this specification.

Ensure that your .env file is an exact replica of the .env.example file.

The application was decoupled into Repositories, traits, and Interfaces this helped to maintain very light controllers and promote maintainability.


#This application has the following server app setup

  # Wafi App
  
  Run composer install

  Run php artisan migrate

  Run npm install

  Run php artisan serve --port=8000

  The application API documentation can be accessed via http://localhost:8000/api/documentation

  Unit tests can be ran using the following command vendor/bin/phpunit