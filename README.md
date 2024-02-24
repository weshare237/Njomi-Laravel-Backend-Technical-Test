## About
A simple banking API

## How to Run
### Requirements
- PHP 8.x
- MySQL 8.0
- Composer installed

### Steps to run
- Update the MySQL database connection parameters in .env
- Run DB migration ```php artisan migrate```
- Seed DB for default users ```php artisan db:seed```
- Run tests ```php artisan test```
- Publish documentations ```php artisan scribe:generate```
- Run server ```php artisan serve```

The AI docs is available under **/docs** endpoint
