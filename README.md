#  RESTful API for orderapp
Rest API for creating, fetching and taking the order.

## Environment Used

- [Docker](https://www.docker.com/) as the container service to isolate the environment.
- [Php](https://php.net/) to develop backend support.
- [Laravel](https://laravel.com) as the server framework / controller layer
- [MySQL](https://mysql.com/) as the database layer
- [NGINX](https://docs.nginx.com/nginx/admin-guide/content-cache/content-caching/) as a proxy / content-caching layer

## Steps to follow

1.  Clone the repo. all source files are in `code` folder.
2.  We have used Google Maps API where you need API key.
    Go to the link https://cloud.google.com/maps-platform/routes/ and get the API key.
    update 'GOOGLE_API_KEY' in environment file located in ./code/.env file
3.  There is "start.sh" file root folder. Run `sh start.sh` to build docker containers, executing migration and PHPunit test cases
4.  After starting container following will be executed automatically:
	- Table migrations using artisan migrate command.
	- Dummy Data imports using artisan db:seed command.
	- Unit and Integration test cases execution.

## For Migrating tables and Data Seeding

1. For running migrations manually `docker exec manage_order_php php artisan migrate`
2. For seeding the database with dummy data `docker exec manage_order_php php artisan db:seed`

## For manually running the docker and test Cases

1. You can run `docker-compose up` from terminal
2. Server is accessible at `http://localhost:8080`
3. Run manual testcase suite:
	- Integration Tests: `docker exec manage_order_php php ./vendor/phpunit/phpunit/phpunit /var/www/html/tests/Feature/OrderFeatureTest.php` &
	- Unit Tests: `docker exec manage_order_php php ./vendor/phpunit/phpunit/phpunit /var/www/html/tests/Unit`

## Code Structure
code folder contain application code.

**./tests**

- There is test caeses in \tests\Unit\OrderControllerTest.php.

**./app**

- Folder contains all the framework configuration file, controllers and models
- migration files are present inside the database/migrations/ folder
	- To run manually migrations use this command `docker exec manage_order_php php artisan migrate`
- For seeding DB with dummy dataset under the database/seeds we have the seeder files 
	- To run manually data import use this command `docker exec manage_order_php php artisan db:seed`
- `OrderController` contains all the api's methods :
    1. localhost:8080/orders?page=1&limit=4 - GET url to fetch orders with page and limit
    2. localhost:8080/orders - POST method to insert new order with origin and distination
    3. localhost:8080/orders - PATCH method to update status for taken.(handled the concurrent request for taking the order. If order already taken then other request will get response status 409)
- We have created `OrderService` as service for orders.


**.env**

- env file contain all application configuration like databse etc.Here we have set GOOGLE_API_KEY too.

## Swagger integration

1. Open URL for API demo `http://localhost:8080/api-docs`
2. Here you can perform all API operations like GET, UPDATE, POST

## API Reference Documentation

-  Find below API details for the help.
1. `localhost:8080/orders?page=:page&limit=:limit` :

    GET Method - to fetch orders with page number and limit
    1. Header :
        - GET /orders?page=1&limit=10
        - Host: localhost:8080
        - Content-Type: application/json

    2. Responses :
		- We will get response with id,distance and status values.

2. `localhost:8080/orders` :

    POST Method - To create new order with origin and distination
    1. Header :
        - POST /orders
        - Host: localhost:8080
        - Content-Type: application/json

    2. Post-Data :
		-Sourse and destination will be provided as request parameters.

    3. Responses :
		- We will get id, distance and status in response.

3. `localhost:8080/orders/:id` :

    PATCH method to update status for taken.(To take an order only once.)
    1. Header :
        - PATCH /orders/2
        - Host: localhost:8080
        - Content-Type: application/json
    2. Post-Data :
		- Satus as TAKEN will be provided as request paramets.

    3. Responses :
		- We will get status as SUCCESS in response.