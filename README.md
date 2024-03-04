## Book-API

### __Swagger Documentation__ available in http://localhost:8000/swagger

**API functionality**:
<li>Registration and login using JWT</li>
<li>CRUD operations with books</li>
<li>Adding and removing books from the "user favorites" list</li>
<li>Saving a list of books in CSV format</li>
<li>Docker support</li>

### To run:
````
docker-compose up --build -d
````
**NOTE**: After docker containers start, you may **need to wait** a few minutes (2 min avg), because the database container needs some more time to initialize itself.

### To set or unset admin status:
````
docker exec -it php-fpm sh -c "php app/Role/index.php <user id> admin"
````
or
````
docker exec -it php-fpm sh -c "php app/Role/index.php <user id> user"
````

### To save books in CSV format:
Use endpoint __/api/admin/books/save__.
To open the file use command:
````
docker exec -it php-fpm cat /var/www/nginx/books-api/books.csv
````

